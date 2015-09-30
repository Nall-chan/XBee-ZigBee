<?
require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

class XBZBGateway extends IPSModule
{
    public function Create()
    {
        parent::Create();
        $this->RequireParent("{6DC3D946-0D31-450F-A8C6-C42DB8D7D4F1}");
        $this->RegisterPropertyInteger("NDInterval", 60);
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->RegisterVariableString("Nodes", "Nodes", "", -5);
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -4);
        IPS_SetHidden($this->GetIDForIdent('Nodes'), true);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        $this->RegisterTimer('NodeDiscovery', $this->ReadPropertyInteger('NDInterval'), 'XBee_NodeDiscovery($_IPS[\'TARGET\']);');
        if ($this->CheckParents())
            $this->RequestNodeDiscovery();
    }

################## PRIVATE     

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

    private function RequestNodeDiscovery()
    {
        //if fKernelRunlevel <> KR_READY then exit;
        $this->SendDataToParent(chr(TXB_API_Command::XB_API_AT_Command) . chr(1) . TXB_AT_Command::XB_AT_ND);
        $this->SendDataToParent(chr(TXB_API_Command::XB_API_AT_Command) . chr(2) . TXB_AT_Command::XB_AT_NI);
    }

    private function DecodeData($Frame)
    {
        $checksum = ord($Frame[strlen($Frame) - 1]);
        //Checksum bilden
        for ($x = 0; $x < strlen($Frame); $x++)
        {
            $checksum = checksum + ord($Frame[$x]);
        }
        //Auf Byte begrenzen
        $checksum = $checksum and 0xff;
        //Checksum NOK?
        if ($checksum <> 0xff)
        {
            IPS_LogMessage('Receive - Checksum Error', bin2hex($Frame));
            return;
        }
        //API CmdID extrahieren
        //  senddata('Receive',data);
        $APIData = new TXB_API_Data();
        $APIData->APICommand = ord($Frame[0]);
        $Frame = substr($Frame, 2, -1);
        switch ($APIData->APICommand)
        {
            case TXB_API_Command::XB_API_AT_Command_Responde:
                // FERTIG
                $ATData = new TXB_Command_Data();
                $ATData->FrameID = ord($Frame[0]);
                $ATData->ATCommand = substr($Frame, 1, 2);
                $ATData->Status = ord($Frame[3]);
                $ATData->Data = substr($Frame, 4);
                switch ($ATData->ATCommand)
                {
                    case TXB_AT_Command::XB_AT_ND:
                        if ($ATData->Status == TXB_Command_Status::XB_Command_OK)
                        {
                            if ($ATData->Data <> '')
                            {
                                $Node = new TXB_Node();
                                $Node->NodeAddr16 = substr($ATData->Data, 0, 2);
                                $Node->NodeAddr64 = substr($ATData->Data, 2, 8);
                                $ATData->Data = substr($ATData->Data, 10);
                                $end = strpos($ATData->Data, chr(0));
                                $Node->NodeName = substr($ATData->Data, 0, $end);
                                //  SendData('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',Node.NodeName+' ' + inttohex(Node.NodeAddr16,4) + ' '
                                //  + inttohex(Int64Rec(Node.NodeAddr64).Hi,8) + inttohex(Int64Rec(Node.NodeAddr64).Lo,8));
                                $this->AddOrReplaceNode($Node);
                            }
                        }
                        else
                        {
                            //  senddata('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')','Error: '+XB_Command_Status_To_String(ATData.Status));
                        }
                        break;
                    case TXB_AT_Command::XB_AT_NI:
                        if ($ATData->Status == TXB_Command_Status::XB_Command_OK)
                        {
                            $end = strpos($ATData->Data, chr(0));
                            $this->SetSummary(substr($ATData->Data, 0, $end));
                        }
                        else
                        {
                            //  senddata('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')','Error: '+XB_Command_Status_To_String(ATData.Status));
                        }
                        break;
                    default:
                        //  SendData('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',data);                        
                        $this->SendDataToDevice($ATData);
                        break;
                }
                break;
            case TXB_API_Command::XB_API_Modem_Status:
                //FERTIG
                //senddata('Modem_Status('+inttohex(ord(APIData.APICommand),2)+')',XB_ModemStatusToString(TXB_Modem_Status(ord(data[1]))));
                IPS_LogMessage('XBee ModemStatus(' . bin2hex(ord($APIData->APICommand)) . ')', $Frame[1]);
                break;
            case TXB_API_Command::XB_API_Transmit_Status:
                //FERTIG
                $Node = $this->GetNodeByAddr16(substr($Frame, 1, 2));
                if ($Node === false) //unbekannter node
                {
                    // senddata('TX_Status('+inttohex(ord(APIData.APICommand),2)+') unknow Node',data);
                }
                else
                {
                    $APIData->NodeName = $Node->NodeName;
                    $APIData->FrameID = ord($Frame[0]);
                    $APIData->Data = substr($Frame, 2);
                    //  SendData('TX_Status('+inttohex(ord(APIData.APICommand),2)+')',data);
                    $this->SendDataToSplitter($APIData);
                }
                break;
            case TXB_API_Command::XB_API_Receive_Paket:
                //FERTIG
                $Node1 = $this->GetNodeByAddr64(substr($Frame, 0, 8));
                $Node2 = $this->GetNodeByAddr16(substr($Frame, 8, 2));
                if (($Node1 === false) or ( $Node2 === false) or ( $Node1 <> $Node2)) //unbekannter node
                {
                    //  senddata('TX_Status('+inttohex(ord(APIData.APICommand),2)+') unknow Node',data);
                }
                else
                {
                    $APIData->NodeName = $Node1->NodeName;
                    $APIData->FrameID = 0;
                    $APIData->Data = substr($Frame, 10);
                    $this->SendDataToSplitter($APIData);
                    //  SendData('Receive_Paket('+inttohex(ord(APIData.APICommand),2)+')',data);
                }
                break;
            case TXB_API_Command::XB_API_Node_Identification_Indicator:
                $Node = new TXB_Node();
                $Node->NodeAddr64 = substr($Frame, 0, 8);
                $Node->NodeAddr16 = substr($Frame, 8, 2);
                $Frame = substr($Frame, 22);
                $end = strpos($Frame, chr(0));
                $Node->NodeName = substr($Frame, 0, $end);
                //  SendData('Node_Identification_Indicator('+inttohex(ord(APIData.APICommand),2)+')',Node.NodeName+' ' + inttohex(Node.NodeAddr16,4) + ' '
                //  + inttohex(Int64Rec(Node.NodeAddr64).Hi,8) + inttohex(Int64Rec(Node.NodeAddr64).Lo,8));
                $this->AddOrReplaceNode($Node);
                break;
            case TXB_API_Command::XB_API_Remote_AT_Command_Responde:
                //FERTIG        
                $APIData->FrameID = $Frame[0];
                $Node1 = $this->GetNodeByAddr64(substr($Frame, 1, 8));
                $Node2 = $this->GetNodeByAddr16(substr($Frame, 9, 2));
                if (($Node1 === false) or ( $Node2 === false) or ( $Node1 <> $Node2)) //unbekannter node
                {
                    //  senddata('Remote_AT_Command_Responde('+inttohex(ord(APIData.APICommand),2)+') unknow Node',data);
                }
                else
                {
                    $APIData->NodeName = $Node1->NodeName;
                    $APIData->Data = substr($Frame, 11);
                    //  SendData('Remote_AT_Command_Responde('+inttohex(ord(APIData.APICommand),2)+')',data);
                    $this->SendDataToSplitter($APIData);
                }
                break;
            case TXB_API_Command::XB_API_IO_Data_Sample_Rx:
                // FERTIG
                $Node1 = $this->GetNodeByAddr64(substr($Frame, 0, 8));
                $Node2 = $this->GetNodeByAddr16(substr($Frame, 8, 2));
                if (($Node1 === false) or ( $Node2 === false) or ( $Node1 <> $Node2)) //unbekannter node
                {
                    //  senddata('Receive_IO_Sample('+inttohex(ord(APIData.APICommand),2)+') unknow Node',data);
                }
                else
                {
                    $APIData->NodeName = $Node1->NodeName;
                    $APIData->Data = substr($Frame, 10);
                    $APIData->FrameID = 0;
                    //  SendData('Receive_IO_Sample('+inttohex(ord(APIData.APICommand),2)+')',data);                            
                    $this->SendDataToSplitter($APIData);
                }

                break;
            default:
                //  senddata('Ungültiger API Frame('+inttohex(ord(APIData.APICommand),2)+')',data);
                break;
        }
    }

################## NODE-Management

    private function AddOrReplaceNode(TXB_Node $Node)
    {
        $NodeVarID = $this->GetIDForIdent('Nodes');
        if ($NodeVarID === false)
            throw new Exception("NodeList not exists.");
        $Nodes = json_decode(GetValueString($NodeVarID), 1);
        if ($Nodes === NULL)
        {
            $Nodes = array();
        }
        else
        {
            $i = array_search($Node->NodeName, array_column($Nodes, 'NodeName'));
            if ($i !== false)
            {
                // Name gefunden
                if (($Nodes[$i]['NodeAddr16'] == $Node->NodeAddr16) and ( $Nodes[$i]['NodeAddr64'] == $Node->NodeAddr64))
                    return;
                // Daten sind anders, also löschen.
                unset($Nodes[$i]);
            }
            $i = array_search($Node->NodeAddr16, array_column($Nodes, 'NodeAddr16'));
            if ($i !== false)
                unset($Nodes[$i]);
            $i = array_search($Node->NodeAddr64, array_column($Nodes, 'NodeAddr64'));
            if ($i !== false)
                unset($Nodes[$i]);
            // Neu anlegen:
        }
        array_push($Nodes, $Node);
        if ($this->lock('Nodes') === false)
            throw new Exception("NodeList is locked.");
        SetValueString($NodeVarID, json_encode(array_values($Nodes)));
        $this->unlock('Nodes');
    }

    private function GetNodeByAddr16($Addr16)
    {
        $NodeVarID = $this->GetIDForIdent('Nodes');
        if ($NodeVarID === false)
            throw new Exception("NodeList not exists.");
        $Nodes = json_decode(GetValueString($NodeVarID), 1);
        if ($Nodes === NULL)
            return false;
        $i = array_search($Addr16, array_column($Nodes, 'NodeAddr16'));
        if ($i === false)
            return false;
        return (object) $Nodes[$i];
    }

    private function GetNodeByAddr64($Addr64)
    {
        $NodeVarID = $this->GetIDForIdent('Nodes');
        if ($NodeVarID === false)
            throw new Exception("NodeList not exists.");
        $Nodes = json_decode(GetValueString($NodeVarID), 1);
        if ($Nodes === NULL)
            return false;
        $i = array_search($Addr64, array_column($Nodes, 'NodeAddr64'));
        if ($i === false)
            return false;
        return (object) $Nodes[$i];
    }

################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    public function NodeDiscovery()
    {
        $this->RequestNodeDiscovery();
    }

################## DATAPOINT RECEIVE FROM CHILD

    public function ForwardData($JSONString)
    {
        // Prüfen und aufteilen nach ForwardDataFromSplitter und ForwardDataFromDevcie
        $Data = json_decode($JSONString);
        switch ($Data->DataID)
        {
            case "{5971FB22-3F96-45AE-916F-AE3AC8CA8782}": //API
                $APIData = new TXB_API_Data();
                $APIData->GetDataFromJSONObject($Data);
                $this->ForwardDataFromSplitter($APIData);
                break;
            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //CMD
                $ATData = new TXB_Command_Data();
                $ATData->GetDataFromJSONObject($Data);
                $this->ForwardDataFromDevice($ATData);
                break;
        }
    }

################## DATAPOINTS SPLITTER

    private function ForwardDataFromSplitter(TXB_API_Data $APIData)
    {
        $Node = $this->GetNodeByName($APIData->NodeName);
        if ($Node === false)
            throw new Exception('Unknown NodeName');
        $Frame = chr($APIData->APICommand) . chr($APIData->FrameID);
        $Frame.=$Node->NodeAddr64 . $Node->NodeAddr16 . $APIData->Data;
        $this->SendDataToParent($Frame);
    }

    private function SendDataToSplitter(TXB_API_Data $APIData)
    {
        $Data = $APIData->ToJSONString('{0C541DDF-CE0F-4113-A76F-B4836015212B}');
        IPS_SendDataToChildren($this->InstanceID, $Data);
    }

################## DATAPOINTS DEVICE

    private function ForwardDataFromDevice(TXB_Command_Data $ATData)
    {
        $Frame = chr(TXB_API_Command::XB_API_AT_Command) . chr($ATData->FrameID) . $ATData->ATCommand . $ATData->Data;
        $this->SendDataToParent($Frame);
    }

    private function SendDataToDevice(TXB_Command_Data $ATData)
    {
        $Data = $ATData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
        IPS_SendDataToChildren($this->InstanceID, $Data);
    }

################## DATAPOINTS PARENT

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        $bufferID = $this->GetIDForIdent("BufferIN");
        // Empfangs Lock setzen
        if (!$this->lock("ReceiveLock"))
            throw new Exception("ReceiveBuffer is locked");

        // Datenstream zusammenfügen
        $head = GetValueString($bufferID);
        SetValueString($bufferID, '');
        // Stream in einzelne Pakete schneiden
        $stream = $head . utf8_decode($data->Buffer);
        $start = strpos($stream, chr(0x7e));
        //Anfang suchen
        if ($start === false)
        {
            IPS_LogMessage('XBeeZigBee Gateway', 'Frame without 0x7e');
            $stream = '';
        }
        elseif ($start > 0)
        {
            IPS_LogMessage('XBeeZigBee Gateway', 'Frame do not start with 0x7e');
            $stream = substr($stream, $start);
        }
        //Paket suchen
        if (strlen($stream) < 5)
        {
            IPS_LogMessage('XBeeZigBee Gateway', 'Frame to short');

            SetValueString($bufferID, $stream);
            $this->unlock("ReceiveLock");
            return;
        }
        $len = ord($stream[1]) * 256 + ord($stream[2]);
        if (strlen($stream) < $len + 4)
        {
            IPS_LogMessage('XBeeZigBee Gateway', 'Frame must have ' . $len . ' Bytes. ' . strlen($stream) . ' Bytes given.');
            SetValueString($bufferID, $stream);
            $this->unlock("ReceiveLock");
            return;
        }
        $packet = substr($stream, 3, $len + 1);
        // Ende wieder in den Buffer werfen
        $tail = substr($stream, $len + 4);
        SetValueString($bufferID, $tail);
        $this->unlock("ReceiveLock");
        $this->DecodeData($packet);
        // Ende war länger als 4 ? Dann nochmal Packet suchen.
        if (strlen($tail) > 4)
            $this->ReceiveData(json_encode(array('Buffer' => '')));
        return true;
    }

    protected function SendDataToParent($Data)
    {
        //Parent ok ?
        if (!$this->HasActiveParent())
            throw new Exception("Instance has no active Parent.");
        // Frame bauen
        //Laenge bilden
        $len = strlen($Data);
        //Startzeichen
        $frame = chr(0x7e);
        //Laenge
        $frame .= chr(floor($len / 256)) . chr($len % 256);
        //Daten
        $frame.=$Data;
        //Checksum
        $check = 0;
        for ($x = 0; $x < $len; $x++)
        {
            $check = $check + ord($Data[$x]);
        }
        $check = $check and 0xff;
        $check = 0xff - $check;
        $frame = $frame . chr($check);
        //Semaphore setzen
        if (!$this->lock("ToParent"))
        {
            throw new Exception("Can not send to Parent");
        }
        // Daten senden
        try
        {
            IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => utf8_encode($frame))));
        }
        catch (Exception $exc)
        {
        // Senden fehlgeschlagen
            $this->unlock("ToParent");
            throw new Exception($exc);
        }
        $this->unlock("ToParent");
        return true;
    }

################## DUMMYS / WOARKAROUNDS - protected

    protected function HasActiveParent()
    {
        IPS_LogMessage(__CLASS__, __FUNCTION__); //          
        $instance = IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] > 0)
        {
            $parent = IPS_GetInstance($instance['ConnectionID']);
            if ($parent['InstanceStatus'] == 102)
                return true;
        }
        return false;
    }

    protected function RegisterTimer($Name, $Interval, $Script)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id === false)
            $id = 0;
        if ($id > 0)
        {
            if (!IPS_EventExists($id))
                throw new Exception("Ident with name " . $Name . " is used for wrong object type");

            if (IPS_GetEvent($id)['EventType'] <> 1)
            {
                IPS_DeleteEvent($id);
                $id = 0;
            }
        }
        if ($id == 0)
        {
            $id = IPS_CreateEvent(1);
            IPS_SetParent($id, $this->InstanceID);
            IPS_SetIdent($id, $Name);
        }
        IPS_SetName($id, $Name);
        IPS_SetHidden($id, true);
        IPS_SetEventScript($id, $Script);
        if ($Interval > 0)
        {
            IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $Interval);
            IPS_SetEventActive($id, true);
        }
        else
        {
            IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, 1);
            IPS_SetEventActive($id, false);
        }
    }

    protected function UnregisterTimer($Name)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id > 0)
        {
            if (!IPS_EventExists($id))
                throw new Exception('Timer not present');
            IPS_DeleteEvent($id);
        }
    }

    protected function SetTimerInterval($Name, $Interval)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id === false)
            throw new Exception('Timer not present');
        if (!IPS_EventExists($id))
            throw new Exception('Timer not present');
        $Event = IPS_GetEvent($id);
        if ($Interval < 1)
        {
            if ($Event['EventActive'])
                IPS_SetEventActive($id, false);
        }
        else
        {
            if ($Event['CyclicTimeValue'] <> $Interval)
                IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $Interval);
            if (!$Event['EventActive'])
                IPS_SetEventActive($id, true);
        }
    }

    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus'])
            parent::SetStatus($InstanceStatus);
    }

    protected function SetSummary($data)
    {
        IPS_LogMessage(__CLASS__, __FUNCTION__ . "Data:" . $data); //                   
    }

################## SEMAPHOREN Helper  - private  

    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter("XBZB_" . (string) $this->InstanceID . (string) $ident, 1))
            {
                return true;
            }
            else
            {
                IPS_Sleep(mt_rand(1, 5));
            }
        }
        return false;
    }

    private function unlock($ident)
    {
        IPS_SemaphoreLeave("XBZB_" . (string) $this->InstanceID . (string) $ident);
    }

}

?>