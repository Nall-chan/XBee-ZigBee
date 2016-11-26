<?

/*
 * @addtogroup xbeezigbee
 * @{
 *
 * @package       XBeeZigBee
 * @file          module.php
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2016 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 *
 */

require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

/**
 * XBZBSplitter ist die Klasse für einen remote XBee. (Router oder EndDevice) 
 * Erweitert ipsmodule 
 *
 * @package       XBeeZigBee
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2016 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 * @example <b>Ohne</b>
 */
class XBZBSplitter extends IPSModule
{

    use DebugHelper,
//        Semaphore,
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
        $this->SetSummary($this->ReadPropertyString('NodeName'));
    }

################## PRIVATE     
################## Send APIData to Parent

    /**
     * 
     * @param TXB_API_Command $APIData
     * @return TXB_API_Command 
     * @throws Exception
     */
    private function Send(TXB_API_Data $APIData)
    {
        try
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
                $this->SendDebug('Response', 'No valid answer', 0);
                return NULL;
            }
            $APIResponse = unserialize($anwser);
//            if ($APIData->FrameID === 0)
//                return $APIResponse;
            $this->SendDebug('Response', $APIResponse, 1);
            return $APIResponse;
        }
        catch (Exception $exc)
        {
            trigger_error($exc->getMessage(), E_USER_NOTICE);
            return NULL;
        }
    }

################## Send buffer-data from here to Parent

    /**
     * 
     * @param string $Data
     * @return boolean
     * @throws Exception
     */
    private function RequestSendData(string $Data)
    {
        $APIData = new TXB_API_Data();
        $APIData->APICommand = TXB_API_Commands::Transmit_Request;
        $APIData->Data = chr(0x00) . chr(0x00) . $Data;
        $this->SendDebug('Transmit_Request', $Data, 1);
        try
        {
            $APIResponse = $this->Send($APIData);
        }
        catch (Exception $exc)
        {
            throw new Exception($exc);
        }
        if ($APIResponse->APICommand != TXB_API_Commands::Transmit_Status)
        {
            throw new Exception("Wrong response in frame.");
        }
        $this->SendDebug('TX_Status_Received(8B):Retry', $APIResponse->Data[0], 1);
        $this->SendDebug('TX_Status_Received(8B):Status', $APIResponse->Data[1], 1);
        $this->SendDebug('TX_Status_Received(8B):Discovery', $APIResponse->Data[2], 1);
        if ($APIResponse->Data[1] == TXB_Transmit_Status::OK)
        {
            $this->SendDebug('TX_Status', 'OK', 0);
            return true;
        }
        $this->SendDebug('TX_Status', 'Error: ' . TXB_Transmit_Status::ToString($APIResponse->Data[1]), 0);
        throw new Exception('Error on Transmit:' . TXB_Transmit_Status::ToString($APIResponse->Data[1]));
    }

################## DATAPOINT RECEIVE FROM CHILD
//NEW

    /**
     * 
     * @param string $JSONString
     * @return boolean|string
     */
    public function ForwardData($JSONString)
    {
        // Prüfen und aufteilen nach ForwardDataFromChild und ForwardDataFromDevcie
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
                    return false;
                }
        }
    }

    /**
     * 
     * @param string $Data
     * @return boolean
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

################## Send buffer-Data from here to Child

    /**
     * 
     * @param string $Data
     */
    private function SendDataToChild(string $Data)
    {
        $JSONString = json_encode(Array("DataID" => "{018EF6B5-AB94-40C6-AA53-46943E824ACF}", "Buffer" => utf8_encode($Data)));
        $this->SendDataToChildren($JSONString);
    }

################## Send API-Data from here to Child

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
     * 
     * @param string $JSONString
     * @return boolean
     */
    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        $APIData = new TXB_API_Data($Data);
        $this->SendDebug('Receive', $APIData, 1);
        switch ($APIData->APICommand)
        {
            case TXB_API_Commands::Transmit_Status:
            case TXB_API_Commands::Remote_AT_Command_Responde:
                $this->SendDebug('WARN', 'Late Receive', 0);
                break;
            case TXB_API_Commands::Receive_Paket:
                $Receive_Status = $APIData->Data[0];
                if ((ord($Receive_Status) & ( TXB_Receive_Status::Packet_Acknowledged)) == TXB_Receive_Status::Packet_Acknowledged)
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
