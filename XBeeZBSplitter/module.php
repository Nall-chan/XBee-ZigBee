<?

/**
 * @addtogroup xbeezigbee
 * @{
 *
 * @package       XBeeZigBee
 * @file          module.php
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2018 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       2.2
 *
 */
require_once(__DIR__ . "/../libs/XBeeZBClass.php");  // diverse Klassen

/**
 * XBZBSplitter ist die Klasse für einen remote XBee. (Router oder EndDevice) 
 * Erweitert ipsmodule 
 */
class XBZBSplitter extends IPSModule
{

    use DebugHelper,
        InstanceStatus;

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function Create()
    {
        parent::Create();
        $this->ConnectParent("{B92E4FAA-1754-4FDC-8F7F-957C65A7ABB8}");
        $this->RegisterPropertyString("NodeName", "");
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetReceiveDataFilter('.*"NodeName":"' . $this->ReadPropertyString('NodeName') . '".*');
        if (IPS_GetKernelRunlevel() != KR_READY)
            return;
        $this->UnregisterVariable("TransmitStatus");
        $this->UnregisterVariable("FrameID");
        if ($this->ReadPropertyString('NodeName') == '')
            $this->SetStatus(202);
        else
            $this->SetStatus(102);
        $this->SetSummary($this->ReadPropertyString('NodeName') . ' (unknown)');
    }

################## Forward Serial Data

    /**
     * Nimmt Nutzdaten entgegen und versendet diese an die serielle Schnittstelle des Ziel-Node.
     *
     * @access private
     * @param string $Data Der ByteString mit Nutzdaten.
     * @return boolean True wenn Gegenseite alle Daten empfangen und quittiert hat, sonst false.
     */
    private function ForwardDataFromChild(string $Data)
    {
        if ($this->HasActiveParent() === false)
        {
            trigger_error('Instance has no active Parent Instance!', E_USER_NOTICE);
            return false;
        }
        $Max = 66;
        if (strlen($Data) < $Max)
            $Max = strlen($Data);
        $SendOk = true;
        while (strlen($Data) > 0)
        {
            try
            {
                $this->SendDebug('Forward', substr($Data, 0, $Max), 1);
                $this->RequestSendData(substr($Data, 0, $Max));
            }
            catch (Exception $ex)
            {
                trigger_error($ex->getMessage(), E_USER_NOTICE);
                $SendOk = FALSE;
            }
            $Data = substr($Data, $Max);
            if (strlen($Data) < $Max)
                $Max = strlen($Data);
        }
        return $SendOk;
    }

    /**
     * Nimmt bis zu 66 Byte Nutzdaten entgegen und versendet diese an die serielle Schnittstelle des Ziel-Node.
     *
     * @access private
     * @param string $Data Der ByteString mit Nutzdaten.
     * @return boolean True wenn Gegenseite den Empfang quittiert hat.
     * @throws Exception Enthält den Fehler, wenn das Paket nicht erfolgreich übertragen wurde.
     */
    private function RequestSendData(string $Data)
    {
        $APIData = new TXB_API_Data();
        $APIData->APICommand = TXB_API_Commands::Transmit_Request;
        $APIData->Data = chr(0x00) . chr(0x00) . $Data;
        $this->SendDebug('Transmit_Request', $Data, 1);
        $APIResponse = $this->Send($APIData);
        if (is_null($APIResponse))
            return;
        if ($APIResponse->APICommand != TXB_API_Commands::Transmit_Status)
        {
            throw new Exception("Wrong response in frame.");
        }
        $this->SendDebug('TX_Status_Received:Retry', $APIResponse->Data[0], 1);
        $this->SendDebug('TX_Status_Received:Status', TXB_Transmit_Status::ToString(ord($APIResponse->Data[1])), 1);
        $this->SendDebug('TX_Status_Received:Discovery', $APIResponse->Data[2], 1);
        if (ord($APIResponse->Data[1]) == TXB_Transmit_Status::OK)
        {
            //$this->SendDebug('TX_Status', 'OK', 0);
            return true;
        }
        //$this->SendDebug('TX_Status', 'Error: ' . TXB_Transmit_Status::ToString(ord($APIResponse->Data[1])), 0);
        throw new Exception('Error on Transmit:' . TXB_Transmit_Status::ToString(ord($APIResponse->Data[1])));
    }

    ################## PRIVATE         

    /**
     * Versendet ein TXB_API_Data-Objekt und empfängt die Antwort.
     * 
     * @access private
     * @param TXB_API_Data $APIData Das Objekt welches versendet werden soll.
     * @result TXB_API_Data|null Enthält die Antwort oder NULL im Fehlerfall.
     */
    private function Send(TXB_API_Data $APIData)
    {
        if (!$this->HasActiveParent())
            throw new Exception('Intance has no active parent.', E_USER_NOTICE);
        if ($this->ReadPropertyString('NodeName') == '')
            throw new Exception('NodeName not set.', E_USER_NOTICE);
        $APIData->NodeName = $this->ReadPropertyString('NodeName');
        $this->SendDebug('Send', $APIData, 0);
        $JSONString = $APIData->ToJSONString('{5971FB22-3F96-45AE-916F-AE3AC8CA8782}');
        $anwser = $this->SendDataToParent($JSONString);
        if ($anwser === false)
        {
            $this->SendDebug('Response', 'No valid answer or timeout', 0);
            return NULL;
        }
        $APIResponse = unserialize($anwser);
        $this->SendDebug('Response', $APIResponse, 1);
        return $APIResponse;
    }

################## DATAPOINT RECEIVE FROM CHILD

    /**
     * Nimmt das API-Paket eines Childs, versendet diese und gibt die Antwort zurück.
     * 
     * @access public
     * @param type $JSONString Der JSON-kodierten String vom IPS-Datenaustausch.
     * @return bool|string Die seriellisierte Antwort oder false im Fehlerfall.
     */
    public function ForwardData($JSONString)
    {
        $Data = json_decode($JSONString);
        switch ($Data->DataID)
        {
            case "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}": //SendText
                return $this->ForwardDataFromChild(utf8_decode($Data->Buffer));
            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //CMD
                $APIData = new TXB_API_Data($Data);
                try
                {
                    if ($APIData->APICommand == TXB_API_Commands::AT_Command)
                    {
                        $APIData->APICommand = TXB_API_Commands::Remote_AT_Command;
                        $APIData->Data = chr(0x02) . $APIData->Data;
                    }
                    $APIResponse = $this->Send($APIData);
                    return serialize($APIResponse);
                }
                catch (Exception $exc)
                {
                    trigger_error($exc->getMessage(), E_USER_NOTICE);
                    return serialize(NULL);
                }
        }
    }

################## DATAPOINT VARIOUS IPS-SPLITTER

    /**
     * Versendet den dekodierten Byte-Streams an die Childs (div. Splitter, Gateway, RegVar etc.)
     * 
     * @access private
     * @param string $Data
     */
    private function SendDataToChild(string $Data)
    {
        $JSONString = json_encode(Array("DataID" => "{018EF6B5-AB94-40C6-AA53-46943E824ACF}", "Buffer" => utf8_encode($Data)));
        $this->SendDataToChildren($JSONString);
    }

################## DATAPOINTS DEVICE

    /**
     * 
     * @param TXB_API_Data $APIData
     */
    private function SendDataToDevice(TXB_API_Data $APIData)
    {
        $JSONString = $APIData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
        $this->SendDataToChildren($JSONString);
    }

################## Datapoints PARENT

    /**
     * Empfängt Daten vom Parent (Gateway).
     * 
     * @access public
     * @param string $JSONString Das empfangene API-Objekt als JSON-kodierter String vom Parent.
     * @result bool Immer True
     */
    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        $APIData = new TXB_API_Data($Data);
        $this->SendDebug('Receive', $APIData, 1);
        switch ($APIData->APICommand)
        {
            case TXB_API_Commands::Node_Identification_Indicator:
                $Parent = bin2hex($APIData->ExtractNodeAddr16());
                $Typ = ord($APIData->Data[0]);
                if ($Typ == 1)
                    $Typ = 'Router';
                elseif ($Typ == 2)
                    $Typ = 'End Device';
                else
                    $Typ = 'unknown';
                $Value = ' (' . $Typ . ' over ' . $Parent . ')';
                $this->SetSummary($this->ReadPropertyString('NodeName') . $Value);

                break;
            case TXB_API_Commands::Transmit_Status:
            case TXB_API_Commands::Remote_AT_Command_Responde:
                $this->SendDebug('WARN', 'Late Receive', 0);
                break;
            case TXB_API_Commands::Receive_Paket:
                $Receive_Status = ord($APIData->Data[0]);
                $this->SendDebug('Receive_Status', TXB_Receive_Status::ToString($Receive_Status), 1);
                if (($Receive_Status & TXB_Receive_Status::Packet_Acknowledged) == TXB_Receive_Status::Packet_Acknowledged)
                {
                    $this->SendDebug('Receive_Paket(OK)', substr($APIData->Data, 1), 1);
                    $this->SendDataToChild(substr($APIData->Data, 1));
                }
                else
                {
                    $this->SendDebug('ReceivePaket(Error:' . bin2hex($Receive_Status) . ')', substr($APIData->Data, 1), 1);
                }
                break;
            case TXB_API_Commands::IO_Data_Sample_Rx:
            default:
                $this->SendDataToDevice($APIData);
                break;
        }
        return true;
    }

}

/** @} */
