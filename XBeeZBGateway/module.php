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
 * XBZBGateway ist die Klasse für einen Coordinator XBee.
 * Erweitert ipsmodule 
 *
 * @package       XBeeZigBee
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2016 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 * @example <b>Ohne</b>
 * @property string $Buffer Receive Buffer.
 * @property TXB_API_DataList $TransmitBuffer Liste mit allen Daten im SendQueue für den Coordinator (ohne Nodes!).
 * @property TXB_NodeList $NodeList Liste mit allen bekannten Nodes.
 */
class XBZBGateway extends IPSModule
{

    use DebugHelper,
        Semaphore,
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
        $this->Buffer = "";
        $this->TransmitBuffer = new TXB_API_DataList();
        $this->NodeList = new TXB_NodeList();
    }

    /**
     * Interne Funktion des SDK.
     *
     * @access public
     */
    public function ApplyChanges()
    {
        parent::ApplyChanges();
        if (IPS_GetKernelRunlevel() != KR_READY)
            return;
        $this->UnregisterVariable("Nodes");
        $this->UnregisterVariable("BufferIN");
        $this->TransmitBuffer = new TXB_API_DataList();
        if ($this->ReadPropertyInteger('NDInterval') > 5)
            $this->RegisterTimer('NodeDiscovery', $this->ReadPropertyInteger('NDInterval') * 1000, 'XBee_NodeDiscovery($_IPS[\'TARGET\']);');
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

    public function NodeDiscovery()
    {
        try
        {
            $this->RequestNodeDiscovery();
        }
        catch (Exception $exc)
        {
            IPS_LogMessage($this->InstanceID, 'Error in NodeDiscovery. Maybe no Node present.');
            trigger_error('Error in NodeDiscovery. Maybe no Node present.', E_USER_NOTICE);
            return false;
        }
        return true;
    }

################## PRIVATE     

    /** Prüft auf falschen Parent und trennt dann die Verbindung.
     * 
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
        return $result;
    }

    /** Leseanfrage des eigenen Identifier.
     * 
     * @throws Exception
     */
    private function RequestNodeIdentifier()
    {
        $APIData = new TXB_API_Data(TXB_API_Commands::AT_Command, TXB_AT_Commands::AT_NI);
        $APIResponse = $this->Send($APIData);
        if (is_null($APIResponse))
            return false;
        return $this->ProcessAT_Command_Responde(new TXB_CMD_Data($APIResponse->Data));
    }

    /** Startet ein Discovery
     * 
     * @throws Exception
     */
    private function RequestNodeDiscovery()
    {
        $APIData = new TXB_API_Data(TXB_API_Commands::AT_Command, TXB_AT_Commands::AT_ND);

        $APIResponse = $this->Send($APIData);
        if (is_null($APIResponse))
            return false;
        return $APIResponse;
    }

    private function ProcessAPIData(TXB_API_Data $APIData)
    {
        if ($APIData->Checksum === false)
        {
            $this->SendDebug('Receive Checksum Error', $APIData, 0);
            return;
        }
        $this->SendDebug('Received', $APIData, 0);
        switch ($APIData->APICommand)
        {
            case TXB_API_Commands::AT_Command_Responde:
                if ($this->UpdateTransmitBuffer($APIData) === false)
                    $this->ProcessAT_Command_Responde($APIData);
                break;
            case TXB_API_Commands::Modem_Status:
                $this->SendDebug('API_Modem_Status: ', $APIData->Data, 1);
                $this->SetState('ModemStatus', $APIData->Data);
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
                if (($Node1 === false) or ( $Node2 === false) or ( $Node1->NodeName <> $Node2->NodeName)) //unbekannter node
                {
                    if ($Node1 === false)
                        $this->SendDebug('unkown NodeAddr64', $NodeAddr64, 1);
                    if ($Node2 === false)
                        $this->SendDebug('unkown NodeAddr16', $NodeAddr16, 1);
                    if ($Node1->NodeName <> $Node2->NodeName)
                    {
                        $this->SendDebug('NodeAddr64 <> NodeAddr16', $NodeAddr64, 1);
                        $this->SendDebug('NodeAddr64 <> NodeAddr16', $NodeAddr16, 1);
                    }
                }
                else
                {
                    $APIData->NodeName = $Node1->NodeName;
                    $this->SendDebug(TXB_API_Commands::ToString($APIData->APICommand), $APIData->Data, 1);
                    if ($APIData->APICommand == TXB_API_Commands::Remote_AT_Command_Responde)
                        $this->UpdateTransmitBuffer($APIData);
                    else
                        $this->SendDataToSplitter($APIData);
                }
                break;
            default:
                $this->SendDebug('Ungültiger API Frame(' . bin2hex(chr($APIData->APICommand)) . ')', $APIData->Data, 1);
                break;
        }
    }

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
                $CMDData->Data = substr($CMDData->Data, 10); // Bytes wegwerfen....
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
                return true;
            case TXB_AT_Commands::AT_NI:
                $this->SetSummary($CMDData->ExtractString());
                return true;
        }
        return false;
    }

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
        $this->SendDebug('WARN', 'Frame not found in TransmitBuffer', 0);
        return false;
    }

################## DATAPOINT RECEIVE FROM CHILD

    public function ForwardData($JSONString)
    {
        // Prüfen und aufteilen nach ForwardDataFromSplitter und ForwardDataFromDevcie
        $Data = json_decode($JSONString);
//        IPS_LogMessage('ForwardDataFrom???:'.$this->InstanceID,  print_r($Data,1));
//        switch ($Data->DataID)
//        {
//            case "{5971FB22-3F96-45AE-916F-AE3AC8CA8782}": //Splitter ankommend
//                $APIData = new TXB_API_Data();
//                $APIData->GetDataFromJSONObject($Data);
//                $this->ForwardDataFromSplitter($APIData);
//                break;
//            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //Device ankommend
//                $ATData = new TXB_Command_Data();
//                $ATData->GetDataFromJSONObject($Data);
//                $this->ForwardDataFromDevice($ATData);
//                break;
//        }
        $APIData = new TXB_API_Data($Data);
        $NodeList = $this->NodeList;
        $Node = $NodeList->GetByNodeName($APIData->NodeName);
        if ($Node === false)
        {
            $this->SendDebug('unkown NodeName', $APIData->NodeName, 0);
            trigger_error('Unknown NodeName', E_USER_NOTICE);
            return serialize(NULL);
        }
        $this->SendDebug('Forward', $APIData, 0);
        $APIResponse = $this->Send($APIData, $Node);
        return serialize($APIResponse);
    }

################## DATAPOINTS SPLITTER

    /** fertig
     * 
     * @param TXB_API_Data $APIData
     * @throws Exception
     */
    /* private function ForwardDataFromSplitter(TXB_API_Data $APIData)
      {
      $NodeList = $this->NodeList;
      $Node = $NodeList->GetByNodeName($APIData->NodeName);
      if ($Node === false)
      {
      $this->SendDebug('unkown NodeName', $APIData->NodeName, 0);
      throw new Exception('Unknown NodeName');
      }
      $APIResponse = $this->Send($APIData, $Node);
      return serialize($APIResponse);
      } */

    /** fertig
     * 
     * @param TXB_API_Data $APIData
     */
    private function SendDataToSplitter(TXB_API_Data $APIData)
    {
        $Data = $APIData->ToJSONString('{0C541DDF-CE0F-4113-A76F-B4836015212B}');
        $this->SendDataToChildren($Data);
    }

################## DATAPOINTS DEVICE

    /** fertig
     * 
     * @param TXB_API_Data $APIData
     */
    /*
      private function SendDataToDevice(TXB_API_Data $APIData)
      {
      $JSONString = $APIData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
      $this->SendDataToChildren($JSONString);
      } */

################## DATAPOINTS PARENT

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        // Empfangs Lock setzen
        if (!$this->lock("ReceiveLock"))
            throw new Exception("ReceiveBuffer is locked");

        // Datenstream zusammenfügen
        $head = $this->Buffer;
        // Stream in einzelne Pakete schneiden
        $stream = $head . utf8_decode($data->Buffer);
        $start = strpos($stream, chr(0x7e));
        //Anfang suchen
        if ($start === false)
        {
            $this->SendDebug('Frame without 0x7e', $stream, 1);
            $stream = '';
        }
        elseif ($start > 0)
        {
            $this->SendDebug('Frame do not start with 0x7e', $stream, 1);
            $stream = substr($stream, $start);
        }
        //Paket suchen
        if (strlen($stream) < 5)
        {
            $this->SendDebug('Frame to short', $stream, 1);
            $this->Buffer = $stream;
            $this->unlock("ReceiveLock");
            return;
        }
        $len = ord($stream[1]) * 256 + ord($stream[2]);
        if (strlen($stream) < $len + 4)
        {
            $this->SendDebug('WAIT', 'Frame must have ' . $len . ' Bytes. ' . strlen($stream) . ' Bytes given.', 0);
            $this->Buffer = $stream;
            $this->unlock("ReceiveLock");
            return;
        }
        $packet = substr($stream, 3, $len + 1);
        // Ende wieder in den Buffer werfen
        $tail = substr($stream, $len + 4);
        if ($tail === false)
            $tail = '';
        $this->Buffer = $tail;
        $this->unlock("ReceiveLock");
        $APIData = new TXB_API_Data($packet);
        $this->ProcessAPIData($APIData);
        // Ende war länger als 4 ? Dann nochmal Packet suchen.
        if (strlen($tail) > 4)
            $this->ReceiveData(json_encode(array('Buffer' => '')));
        return true;
    }

    private function Send(TXB_API_Data $APIData, TXB_Node $Node = NULL)
    {
        try
        {
            if ($this->HasActiveParent() === false)
                throw new Exception('Instance has no active Parent Instance!');

            if (($APIData->FrameID !== 0) and ( $APIData->Data !== TXB_AT_Commands::AT_ND))
            {
                if (!$this->lock('TransmitBuffer'))
                    throw new Exception('TransmitBuffer is locked');
                $TransmitBuffer = $this->TransmitBuffer;
                $APIData->FrameID = $TransmitBuffer->Add();
                $this->TransmitBuffer = $TransmitBuffer;
                $this->unlock('TransmitBuffer');
            }
            $this->SendDebug('Send', $APIData, 0);
            $Frame = $APIData->ToFrame($Node);
            $this->SendDataToParent(json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => utf8_encode($Frame))));

            if (($APIData->FrameID === 0) or ( $APIData->Data === TXB_AT_Commands::AT_ND))
                return true;

            $APIResponse = $this->WaitForResponse($APIData->FrameID);
            $this->SendDebug('Response', $APIResponse, 1);
            return $APIResponse;
        }
        catch (Exception $ex)
        {
            trigger_error($ex->getMessage(), E_USER_NOTICE);
            return NULL;
        }
    }

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
//                        $this->SendDebug('TransmitBuffer', $Data, 1);
                        $this->unlock('TransmitBuffer');
                        return $Data;
                    }
                }
//                }
//                else
//                {
//                    $this->SendDebug('ERROR', 'TransmitBuffer is locked.', 0);
//                    throw new Exception('TransmitBuffer is locked.');
//                }
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
            throw new Exception($exc);
        }
    }

}

/** @} */