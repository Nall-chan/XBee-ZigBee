<?

/**
 * @addtogroup xbeezigbee
 * @{
 *
 * @package       XBeeZigBee
 * @file          module.php
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2016 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 */
require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

/**
 * XBZBGateway ist die Klasse für einen Coordinator XBee.
 * Erweitert ipsmodule 
 *
 * @property string $Buffer Receive Buffer.
 * @property TXB_API_DataList $TransmitBuffer Liste mit allen Daten im SendQueue für den Coordinator (ohne Nodes!).
 * @property TXB_NodeList $NodeList Liste mit allen bekannten Nodes.
 * @property int $Parent Aktueller IO-Parent.
 */
class XBZBGateway extends IPSModule
{

    use DebugHelper,
        Semaphore,
        Profile,
        InstanceStatus;

    /**
     * Wert einer Eigenschaft aus den InstanceBuffer lesen.
     * 
     * @access public
     * @param string $name Propertyname
     * @return mixed Value of Name
     */
    public function __get($name)
    {
        return unserialize($this->GetBuffer($name));
    }

    /**
     * Wert einer Eigenschaft in den InstanceBuffer schreiben.
     * 
     * @access public
     * @param string $name Propertyname
     * @param mixed Value of Name
     */
    public function __set($name, $value)
    {
        $this->SetBuffer($name, serialize($value));
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function Create()
    {
        parent::Create();
        $this->RequireParent("{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}");
        $this->RegisterPropertyInteger("NDInterval", 60);
        $this->RegisterPropertyBoolean("API2", false);
        $this->Buffer = "";
        $this->TransmitBuffer = new TXB_API_DataList();
        $this->NodeList = new TXB_NodeList();
    }

    /**
     * Nachrichten aus der Nachrichtenschlange verarbeiten.
     *
     * @access public
     * @param int $TimeStamp
     * @param int $SenderID
     * @param int $Message
     * @param array|int $Data
     */
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        switch ($Message)
        {
            case IPS_KERNELMESSAGE:
                if ($Data[0] == KR_READY)
                {
                    try
                    {
                        $this->KernelReady();
                    }
                    catch (Exception $exc)
                    {
                        return;
                    }
                }
                break;
            case DM_CONNECT:
            case DM_DISCONNECT:
                $this->ForceRefresh();
                break;
            case IM_CHANGESTATUS:
                if (($SenderID == @IPS_GetInstance($this->InstanceID)['ConnectionID']) and ( $Data[0] == IS_ACTIVE))
                    $this->ForceRefresh();
                break;
        }
    }

    /**
     * Wird ausgeführt wenn der Kernel hochgefahren wurde.
     * 
     * @access protected
     */
    protected function KernelReady()
    {
        $this->ApplyChanges();
    }

    /**
     * Wird ausgeführt wenn sich der Parent ändert.
     * 
     * @access protected
     */
    protected function ForceRefresh()
    {
        $this->ApplyChanges();
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function ApplyChanges()
    {
        $this->RegisterMessage(0, IPS_KERNELMESSAGE);
        $this->RegisterMessage($this->InstanceID, DM_CONNECT);
        $this->RegisterMessage($this->InstanceID, DM_DISCONNECT);
        // Wenn Kernel nicht bereit, dann warten... KR_READY kommt ja gleich
        parent::ApplyChanges();
        if (IPS_GetKernelRunlevel() != KR_READY)
            return;
        $this->UnregisterVariable("Nodes");
        $this->UnregisterVariable("BufferIN");
        $this->TransmitBuffer = new TXB_API_DataList();
        if (!IPS_VariableProfileExists('XBeeZB.ModemStatus'))
            $this->RegisterProfileIntegerEx('XBeeZB.ModemStatus', "Gear", "", "", Array(
                Array(0, 'Hardware reset', '', -1),
                Array(1, 'Watchdog timer reset', '', -1),
                Array(2, 'Joined Network', '', -1),
                Array(3, 'Disassociated', '', -1),
                Array(4, 'Config error / sync lost', '', -1),
                Array(5, 'Coordinator realignment', '', -1),
                Array(6, 'Coordinator started', '', -1),
                Array(7, 'Network security key updated', '', -1),
                Array(0x0B, 'Network woke up', '', -1),
                Array(0x0C, 'Network went to sleep', '', -1),
                Array(0x0D, 'Voltage supply limit exceed', '', -1),
                Array(0x11, 'Modem config changed while join', '', -1),
                Array(0x80, 'Stack error', '', -1),
                Array(0x82, 'Send/Join command without connecting from AP', '', -1),
                Array(0x83, 'AP not found', '', -1),
                Array(0x84, 'PSK not configured', '', -1),
                Array(0x87, 'SSID not found', '', -1),
                Array(0x88, 'Failed to join with security enabled', '', -1),
                Array(0x8A, 'Invalid channel', '', -1),
                Array(0x8E, 'Failed to join AP', '', -1)
            ));

        if ($this->ReadPropertyInteger('NDInterval') >= 5)
            $this->RegisterTimer('NodeDiscovery', $this->ReadPropertyInteger('NDInterval') * 1000, 'XBee_NodeDiscovery($_IPS[\'TARGET\']);');
        else
            $this->RegisterTimer('NodeDiscovery', 0, 'XBee_NodeDiscovery($_IPS[\'TARGET\']);');

        if (($this->ReadPropertyInteger('NDInterval') < 5) and ( $this->ReadPropertyInteger('NDInterval') != 0))
        {
            echo 'Invalid Interval.';
        }

        if ($this->CheckParents())
        {

            try
            {
                $this->RequestNodeIdentifier();
                $this->RequestNodeDiscovery();
            }
            catch (Exception $exc)
            {
                trigger_error($exc, E_USER_NOTICE);
                return false;
            }
        }
    }

    ################## PUBLIC

    /**
     * IPS-Instanzefunktion XBee_NodeDiscovery($InstanceID)
     * Startet ein Node Discovery im Netzwerk
     * 
     * @access public
     * @return bool True wenn erfolgreich gestartet, sonst false.
     */
    public function NodeDiscovery()
    {
        return $this->RequestNodeDiscovery();
    }

################## PRIVATE     

    /**
     * Prüft auf falschen Parent und trennt dann die Verbindung.
     * 
     * @access private
     * @return boolean True wenn Parent Aktiv, sonst false.
     */
    private function CheckParents()
    {
        $result = $this->HasActiveParent();
        if ($result)
        {
            $instance = IPS_GetInstance($this->InstanceID);
            $parentGUID = IPS_GetInstance($instance['ConnectionID'])['ModuleInfo']['ModuleID'];
            if ($parentGUID == '{61051B08-5B92-472B-AFB2-6D971D9B99EE}')
            {
                IPS_DisconnectInstance($this->InstanceID);
                IPS_LogMessage('XBee-ZigBee Gateway', 'XB-ZB Gateway has invalid Parent.');
                $result = false;
            }
        }
        $this->GetParentData();
        return $result;
    }

    /**
     *  Leseanfrage des eigenen Identifier vom Coordinator.
     * 
     * @access private
     * @return bool true bei Erflog, sonst false.
     */
    private function RequestNodeIdentifier()
    {
        $APIData = new TXB_API_Data(TXB_API_Commands::AT_Command, TXB_AT_Commands::AT_NI);
        $APIResponse = $this->Send($APIData);
        if (is_null($APIResponse))
            return false;
        return $this->ProcessAT_Command_Responde(new TXB_CMD_Data($APIResponse->Data));
    }

    /** Startet das Node Discovery.
     * 
     * @access private
     * @return bool true bei Erflog, sonst false.
     */
    private function RequestNodeDiscovery()
    {
        $APIData = new TXB_API_Data(TXB_API_Commands::AT_Command, TXB_AT_Commands::AT_ND);

        $APIResponse = $this->Send($APIData);
        if (is_null($APIResponse))
            return false;
        return $APIResponse;
    }

    /**
     * Setzt die IPS-Variable vom Modem Status
     * 
     * @access private
     * @param int $State Der neue Modem Status
     */
    private function SetState(int $State)
    {
        $VarID = @$this->GetIDForIdent('ModemStatus');
        if ($VarID === false)
            $this->RegisterVariableInteger('ModemStatus', 'ModemStatus', 'XBeeZB.ModemStatus');
        SetValueInteger($VarID, $State);
    }

    /**
     * Wertet eine empfangenes API-Paket aus
     * 
     * @access private
     * @param TXB_API_Data $APIData Das auszuwertende API-Paket.
     * @throws Exception Wenn die NodeListe nicht aktualisiert werden konnte.
     */
    private function ProcessAPIData(TXB_API_Data $APIData)
    {
        $this->SendDebug('Received', $APIData, 0);
        switch ($APIData->APICommand)
        {
            case TXB_API_Commands::AT_Command_Responde:
                if ($this->UpdateTransmitBuffer($APIData) === false)
                {
                    $this->ProcessAT_Command_Responde(new TXB_CMD_Data($APIData->Data));
                    $this->SendDataToDevice($APIData);
                }
                break;
            case TXB_API_Commands::Modem_Status:
                $this->SendDebug('API_Modem_Status: ', TXB_Modem_Status::ToString(ord($APIData->Data[0])), 1);
                $this->SetState(ord($APIData->Data[0]));
                break;
            case TXB_API_Commands::Transmit_Status:
                $NodeAddr16 = $APIData->ExtractNodeAddr16();
                $NodeList = $this->NodeList;
                $Node = $NodeList->GetByNodeAddr16($NodeAddr16);
                if ($Node === false)
                    $this->SendDebug('unkown NodeAddr16', $NodeAddr16, 1);
                else
                {
                    $APIData->NodeName = $Node->NodeName;
                    $this->SendDebug('Transmit_Status: ', $APIData->Data, 1);
                    $this->UpdateTransmitBuffer($APIData);
                }
                break;
            case TXB_API_Commands::Node_Identification_Indicator:
                $Node = new TXB_Node();
                $Node->NodeAddr64 = $APIData->ExtractNodeAddr64();
                $Node->NodeAddr16 = $APIData->ExtractNodeAddr16();
                $APIData->Data = substr($APIData->Data, 11); // Bytes wegwerfen....
                $Node->NodeName = $APIData->ExtractString();
                $this->SendDebug('Node_Identification_Indicator: Addr64', $Node->NodeAddr64, 1);
                $this->SendDebug('Node_Identification_Indicator: Addr16', $Node->NodeAddr16, 1);
                $this->SendDebug('Node_Identification_Indicator: Name', $Node->NodeName, 0);
                if ($this->lock('NodeList') === false)
                    throw new Exception("NodeList is locked.");
                $NodeList = $this->NodeList;
                $NodeList->Update($Node);
                $this->NodeList = $NodeList;
                $this->unlock('NodeList');
                $APIData->NodeName = $Node->NodeName;
                $this->SendDataToSplitter($APIData);
                break;
            case TXB_API_Commands::Receive_Paket:
            case TXB_API_Commands::IO_Data_Sample_Rx:
            case TXB_API_Commands::Remote_AT_Command_Responde:
                $NodeAddr64 = $APIData->ExtractNodeAddr64();
                $NodeAddr16 = $APIData->ExtractNodeAddr16();
                $NodeList = $this->NodeList;
                $Node1 = $NodeList->GetByNodeAddr64($NodeAddr64);
                $Node2 = $NodeList->GetByNodeAddr16($NodeAddr16);
                if ($Node1 === false)
                {
                    $this->SendDebug('unkown NodeAddr64', $NodeAddr64, 1);
                    break;
                }
                if ($Node2 === false)
                {
                    $this->SendDebug('unkown NodeAddr16', $NodeAddr16, 1);
                    break;
                }

                if ($Node1->NodeName <> $Node2->NodeName)
                {
                    $this->SendDebug('NodeAddr64 <> NodeAddr16', $NodeAddr64, 1);
                    $this->SendDebug('NodeAddr64 <> NodeAddr16', $NodeAddr16, 1);
                    break;
                }
                $APIData->NodeName = $Node1->NodeName;
                $this->SendDebug(TXB_API_Commands::ToString($APIData->APICommand), $APIData->Data, 1);
                if ($APIData->APICommand == TXB_API_Commands::Remote_AT_Command_Responde)
                    $this->UpdateTransmitBuffer($APIData);
                else
                    $this->SendDataToSplitter($APIData);
                break;
            default:
                $this->SendDebug('Ungültiger API Frame(' . bin2hex(chr($APIData->APICommand)) . ')', $APIData->Data, 1);
                break;
        }
    }

    /**
     * Wertet eine empfangenes AT-Paket aus
     * 
     * @access private
     * @param TXB_CMD_Data $CMDData Das auszuwertende AT-Paket.
     * @return bool True wenn das Commando verarbeitet wurde, sonst false.
     */
    private function ProcessAT_Command_Responde(TXB_CMD_Data $CMDData)
    {
        $this->SendDebug('Decode ATCommand', $CMDData, 0);

        if ($CMDData->Status != TXB_AT_Command_Status::OK)
        {
            $this->SendDebug('AT_Command_Responde: Status ERROR', TXB_AT_Command_Status::ToString($CMDData->Status), 0);
            return false;
        }
        switch ($CMDData->ATCommand)
        {
            case TXB_AT_Commands::AT_ND:
                $Node = new TXB_Node();
                $Node->NodeAddr16 = $CMDData->ExtractNodeAddr16();
                $Node->NodeAddr64 = $CMDData->ExtractNodeAddr64();
                $Node->NodeName = $CMDData->ExtractString();
                $this->SendDebug('AT_Command_Responde::XB_AT_ND', $Node->NodeName, 0);
                $this->SendDebug('Command_Responde_ND: Addr64', $Node->NodeAddr64, 1);
                $this->SendDebug('Command_Responde_ND: Addr16', $Node->NodeAddr16, 1);
                $this->SendDebug('Command_Responde_ND: Name', $Node->NodeName, 0);
                if ($this->lock('NodeList') === false)
                    throw new Exception("NodeList is locked.");
                $NodeList = $this->NodeList;
                $NodeList->Update($Node);
                $this->NodeList = $NodeList;
                $this->unlock('NodeList');
                $APIData = new TXB_API_Data(TXB_API_Commands::Node_Identification_Indicator, $CMDData->Data);
                $APIData->NodeName = $Node->NodeName;
                $this->SendDataToSplitter($APIData);
                return true;
            case TXB_AT_Commands::AT_NI:
                $this->SetSummary($CMDData->ExtractString());
                return true;
        }
        return false;
    }

    /**
     * Fügt ein empfangenes API-Paket in den Transmit-Buffer ein.
     * 
     * @access private
     * @param TXB_API_Data $APIData Das einzufügene API-Paket.
     * @return boolean True wenn erfolgreich, sonst false
     */
    private function UpdateTransmitBuffer(TXB_API_Data $APIData)
    {
        if (!$this->lock('TransmitBuffer'))
        {
            trigger_error('TransmitBuffer is locked', E_USER_NOTICE);
            return false;
        }
        $TransmitBuffer = $this->TransmitBuffer;
        if ($TransmitBuffer->Update($APIData))
        {
            $this->TransmitBuffer = $TransmitBuffer;
            $this->unlock('TransmitBuffer');
            return true;
        }
        $this->unlock('TransmitBuffer');
        return false;
    }

################## DATAPOINT RECEIVE FROM CHILD

    /**
     * Nimmt das API-Paket eines Childs, versendet diese und gibt die Antwort zurück.
     * 
     * @access public
     * @param type $JSONString Der JSON-kodierten String vom IPS-Datenaustausch.
     * @return string Die seriellisierte Antwort.
     */
    public function ForwardData($JSONString)
    {
        $Data = json_decode($JSONString);
        $APIData = new TXB_API_Data($Data);
        switch ($Data->DataID)
        {
            case "{5971FB22-3F96-45AE-916F-AE3AC8CA8782}": //Splitter
                if ($APIData->NodeName == '')
                {
                    $this->SendDebug('NodeName ist empty', $APIData, 0);
                    trigger_error('NodeName ist empty', E_USER_NOTICE);
                    return serialize(NULL);
                }
                $NodeList = $this->NodeList;
                $Node = $NodeList->GetByNodeName($APIData->NodeName);
                if ($Node === false)
                {
                    $this->SendDebug('unkown NodeName', $APIData->NodeName, 0);
                    trigger_error('Unknown NodeName', E_USER_NOTICE);
                    return serialize(NULL);
                }
                break;
            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //CMD
                if ($APIData->NodeName == '')
                    $Node = NULL;
                break;
            default:
                return serialize(NULL);
        }

        $this->SendDebug('Forward', $APIData, 0);
        $APIResponse = $this->Send($APIData, $Node);
        return serialize($APIResponse);
    }

################## DATAPOINTS SPLITTER

    /**
     * Versendet ein API-Paket an die Splitter
     * 
     * @access private
     * @param TXB_API_Data $APIData
     */
    private function SendDataToSplitter(TXB_API_Data $APIData)
    {
        $Data = $APIData->ToJSONString('{0C541DDF-CE0F-4113-A76F-B4836015212B}');
        $this->SendDataToChildren($Data);
    }

################## DATAPOINTS DEVICE

    /**
     * Versendet ein API-Paket an die Devices
     * 
     * @access private
     * @param TXB_API_Data $APIData
     */
    private function SendDataToDevice(TXB_API_Data $APIData)
    {
        $JSONString = $APIData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
        $this->SendDataToChildren($JSONString);
    }

################## DATAPOINTS PARENT

    /**
     * Empfängt Daten vom Parent (IO).
     * Dekodierte API-Pakete werden an ProcessAPIData übergeben.
     * 
     * @access public
     * @param string $JSONString Der empfangene JSON-kodierte Byte-String vom Parent.
     * @result bool Immer True
     */
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        $head = $this->Buffer;
        $stream = $head . utf8_decode($data->Buffer);
        $start = strpos($stream, chr(0x7e));
        if ($start === false)
        {
            $this->SendDebug('Frame without 0x7e', $stream, 1);
            $stream = '';
        }
        elseif ($start > 0)
        {
            $this->SendDebug('Frame do not start with 0x7e', $stream, 1);
        }
        //Paket suchen
        if (strlen($stream) < 5)
        {
            $this->SendDebug('Frame to short', $stream, 1);
            $this->Buffer = $stream;
            return true;
        }

        $packets = explode(chr(0x7E), $stream);
        unset($packets[0]);
        $escaped = array("\x7d\x31", "\x7d\x33", "\x7d\x5e", "\x7d\x5d");
        $unescaped = array("\x11", "\x13", "\x7e", "\x7d");
        $doescape = $this->ReadPropertyBoolean('API2');
        foreach ($packets as $i => $rawpacket)
        {
            if ($doescape)
                $packet = str_replace($escaped, $unescaped, $rawpacket);
            else
                $packet = $rawpacket;
            $len = ord($packet[0]) * 256 + ord($packet[1]);
            if (strlen($packet) < $len + 3)
            {
                if ($i == count($packets))
                {
                    $this->SendDebug('WAIT', 'Frame must have ' . $len . ' Bytes. ' . strlen($packet) - 3 . ' Bytes given.', 0);
                    $this->Buffer = $rawpacket;
                    return true;
                }
                else
                {
                    $this->SendDebug('ERROR', 'Frame must have ' . $len . ' Bytes. ' . strlen($packet) - 3 . ' Bytes given.', 0);
                    continue;
                }
            }
            $packet = substr($packet, 2);
            $checksum = ord($packet[strlen($packet) - 1]);
            for ($x = 0; $x < (strlen($packet) - 1); $x++)
            {
                $checksum = $checksum + ord($packet[$x]);
            }

            if (($checksum & 0xff) != 0xff)
            {
                $this->SendDebug('ERROR', 'Checksumm error.', 0);
                continue;
            }
            $APIData = new TXB_API_Data($packet);
            $this->ProcessAPIData($APIData);
        }
        $this->Buffer = '';

        return true;
    }

    /**
     * Versendet ein TXB_API_Data-Objekt und empfängt die Antwort.
     * 
     * @access private
     * @param TXB_API_Data $APIData Das Objekt welches versendet werden soll.
     * @param TXB_Node|NULL $Node Der Node an welchen der Frame adressiert wird.
     * @result TXB_API_Data|bool|null Enthält die Antwort oder true/false bei Paketen ohne Quittung oder NULL im Fehlerfall.
     */
    private function Send(TXB_API_Data $APIData, TXB_Node $Node = NULL)
    {
        try
        {
            if ($this->HasActiveParent() === false)
                throw new Exception('Instance has no active Parent Instance!');

            if ($APIData->FrameID !== 0)
            {
                if (!$this->lock('TransmitBuffer'))
                    throw new Exception('TransmitBuffer is locked');
                $TransmitBuffer = $this->TransmitBuffer;
                $APIData->FrameID = $TransmitBuffer->Add();
                if ($APIData->Data === TXB_AT_Commands::AT_ND)
                    $TransmitBuffer->Remove($APIData->FrameID);
                $this->TransmitBuffer = $TransmitBuffer;
                $this->unlock('TransmitBuffer');
            }
            $this->SendDebug('Send', $APIData, 0);
            $Frame = $APIData->ToFrame($this->ReadPropertyBoolean('API2'), $Node);
            $this->SendDataToParent(json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => utf8_encode($Frame))));

            if ($APIData->FrameID !== 0)
            {
                if ($APIData->Data === TXB_AT_Commands::AT_ND)
                    return true;
                $APIResponse = $this->WaitForResponse($APIData->FrameID);
                $this->SendDebug('Response', $APIResponse, 1);
                return $APIResponse;
            }
            return true;
        }
        catch (Exception $ex)
        {
            trigger_error($ex->getMessage(), E_USER_NOTICE);
            return NULL;
        }
    }

    /**
     * Wartet auf eine Antwort.
     * 
     * @access private
     * @param int $FrameID Die Frame-ID auf die gewartet wird.
     * @result TXB_API_Data Enthält das API-Paket mit der Antwort.
     * @throws Exception Wenn Frame nicht gefunden wurde.
     */
    private function WaitForResponse(int $FrameID)
    {
        try
        {
            for ($i = 0; $i < 500; $i++)
            {
                $TransmitBuffer = $this->TransmitBuffer;
                $Data = $TransmitBuffer->Get($FrameID);
                if ($Data === false)
                {
                    $this->unlock('TransmitBuffer');
                    $this->SendDebug('ERROR', 'Frame ' . $FrameID . ' not found in TransmitBuffer.', 0);
                    throw new Exception('Frame ' . $FrameID . ' not found in TransmitBuffer.');
                }
                if ($Data !== NULL)
                {
                    if ($this->lock('TransmitBuffer'))
                    {
                        $TransmitBuffer = $this->TransmitBuffer;
                        $TransmitBuffer->Remove($FrameID);
                        $this->TransmitBuffer = $TransmitBuffer;
                        $this->unlock('TransmitBuffer');
                        return $Data;
                    }
                }
                IPS_Sleep(10);
            }
            if ($this->lock('TransmitBuffer'))
            {
                $TransmitBuffer = $this->TransmitBuffer;
                $TransmitBuffer->Remove($FrameID);
                $this->TransmitBuffer = $TransmitBuffer;
                $this->unlock('TransmitBuffer');
            }
            $this->SendDebug('ERROR', 'Wait for response timed out.', 0);
            throw new Exception('Wait for response timed out.');
        }
        catch (Exception $exc)
        {
            throw $exc;
        }
    }

}

/** @} */