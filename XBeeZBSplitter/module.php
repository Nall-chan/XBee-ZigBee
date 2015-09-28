<?

class XBZBSplitter extends IPSModule
{

    public function Create()
    {

        parent::Create();
        $this->RequireParent("{B92E4FAA-1754-4FDC-8F7F-957C65A7ABB8}");
        $this->RegisterPropertyString("NodeName", "");
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -5);
        $this->RegisterVariableString("BufferOUT", "BufferOUT", "", -4);
        $this->RegisterVariableInteger("TransmitStatus", "TransmitStatus", "", -3);
        $this->RegisterVariableInteger("FrameID", "FrameID", "", -2);
//        $this->RegisterVariableBoolean("WaitForResponse", "WaitForResponse", "", -2);
//        $this->RegisterVariableBoolean("ReplyEvent", "ReplyEvent", "", -5);
//        $this->RegisterVariableBoolean("Connected", "Connected", "", -1);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        IPS_SetHidden($this->GetIDForIdent('BufferOUT'), true);
        IPS_SetHidden($this->GetIDForIdent('TransmitStatus'), true);
        IPS_SetHidden($this->GetIDForIdent('FrameID'), true);
        if ($this->ReadPropertyString('NodeName') == '')
            $this->SetSummary(202);
        else
            $this->SetStatus(102);
        $this->SetSummary($this->ReadPropertyString('NodeName'));
    }

################## PRIVATE     
//---------------------------HELPER-FUNCTIONS-----------------------------------
//------------------------------------------------------------------------------

    private function RequestSendData($Data)
    {
        $APIData = new TXB_API_Data;

        //FrameID festlegen.
        $FrameID = $this->GetIDForIdent('FrameID');
        $TransmitStatusID = $this->GetIDForIdent('TransmitStatus');
        if (!$this->lock('RequestSendData'))
            throw new Exception('RequestSendData is locked');
        $Frame = GetValueInteger($FrameID);
        if ($Frame == 255)
            $Frame = 1;
        else
            $Frame++;
        SetValueInteger($FrameID, $Frame);


        if (!$this->lock('TransmitStatus'))
        {
            $this->unlock('RequestSendData');
            throw new Exception('Receive Transmit Status is locked');
        }
        SetValueInteger($TransmitStatusID, 0xff);
        $this->unlock('TransmitStatus');


        $APIData->FrameID = $Frame;
        $APIData->APICommand = TXB_API_Command::XB_API_Transmit_Request;
        $APIData->Data = chr(0x00) . chr(0x00) . $Data;
        try
        {
            $this->SendDataToParent($APIData);
        }
        catch (Exception $exc)
        {
            $this->unlock('RequestSendData');
            throw new Exception($exc);
        }
        $TransmitStatus = $this->WaitForResponse();
        if ($TransmitStatus === false)
        {
//          Senddata('TX_Status','Timeout');
            $this->unlock('RequestSendData');
            throw new Exception('Send Data Timeout');
        }
        if ($TransmitStatus === TXB_Transmit_Status::XB_Transmit_OK)
        {
//            Senddata('TX_Status','OK')
            $this->unlock('RequestSendData');
            return true;
        }
//        Senddata('TX_Status','Error: '+ XB_Transmit_Status_to_String(fTransmitStatus));
        $this->unlock('RequestSendData');

        throw new Exception('Error on Transmit:' . ord($TransmitStatus));
    }

################## DATAPOINT RECEIVE FROM CHILD

    public function ForwardData($JSONString)
    {
        // Prüfen und aufteilen nach ForwardDataFromChild und ForwardDataFromDevcie
        $Data = json_decode($JSONString);
        switch ($Data->DataID)
        {
            case "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}": //SendText
                $this->ForwardDataFromChild(utf8_decode($Data->Buffer));
                break;
            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //CMD
                $ATData = new TXB_Command_Data();
                $ATData->GetDataFromJSONObject($Data);
                $this->ForwardDataFromDevice($ATData);
                break;
        }
    }

################## DATAPOINTS 'String'-Child
    //--- IIPSSendString implementation
//--- { Data Points }  // von String-Child
    private function ForwardDataFromChild($Data)
    {

        if ($this->HasActiveParent() === false)
            throw new Exception('Instance has no active Parent Instance!');
        $Max = 66;
        if (strlen($Data) < $Max)
        {
            try
            {
                $this->RequestSendData($Data);
            }
            catch (Exception $ex)
            {
                unset($ex);
                throw new Exception('Error on forward Data');
            }
        }
        else
        {
            $SendOk = true;
            while (strlen($Data) > 0)
            {
                try
                {
                    $this->RequestSendData(substr($Data, 0, $Max));
                }
                catch (Exception $exc)
                {
                    unset($exc);
                    $SendOk = FALSE;
                }

                $Data = substr($Data, $Max);
                if (strlen($Data) < $Max)
                    $Max = strlen($Data);
            }
            if (!$SendOk)
                throw new Exception('Error on forward Data');
        }
        return true;
    }

    private function SendDataToChild($Data)
    {
        $JSONString = json_encode(Array("DataID" => "{018EF6B5-AB94-40C6-AA53-46943E824ACF}", "Buffer" => utf8_encode($Data)));
        IPS_SendDataToChildren($this->InstanceID, $JSONString);
    }

################## DATAPOINTS DEVICE
//--- IXBZBSendCMD implementation
//--- { Data Points } //vom XB-ZB Device
    // Sendet Remote AT Commandos umgesetzt in API-Frames an den Coordinator (Gateway)    
    private function ForwardDataFromDevice(TXB_Command_Data $ATData)
    {
        if ($this->HasActiveParent() === false)
            throw new Exception('Instance has no active Parent Instance!');
        $APIData = new TXB_API_Data();
        $APIData->APICommand = TXB_API_Command::XB_API_Remote_AT_Command;
        $APIData->FrameID = $ATData->FrameID;
        $APIData->Data = chr(0x02) . $ATData->ATCommand . $ATData->Data;
        return $this->SendDataToParent($APIData);
    }

    private function SendDataToDevice($ATorIOData)
    {
        $Data = $ATorIOData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
        IPS_SendDataToChildren($this->InstanceID, $Data);
    }

################## Datapoints PARENT
    //--- IXBZBSplitter implementation
    //--- { Data Points } //vom XB-ZB Gateway
    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        // Nur API Daten annehmen.
        if ($Data->DataID <> '{0C541DDF-CE0F-4113-A76F-B4836015212B}')
            return false;
        $APIData = new TXB_API_Data();
        $APIData->GetDataFromJSONObject($Data);
        // Auf NodeNamen prüfen
        if ($APIData->NodeName <> $this->ReadPropertyString('NodeName'))
            return false;
        switch ($APIData->APICommand)
        {
            case TXB_API_Command::XB_API_Transmit_Status:
                $TransmitStatusID = $this->GetIDForIdent("TransmitStatus");
                if (!$this->lock('Transmit_Status'))
                    throw new Exception('Receive Transmit Status is locked');
                SetValueInteger($TransmitStatusID, ord($APIData->Data[1]));
                $this->unlock('Transmit_Status');
                break;
            case TXB_API_Command::XB_API_Receive_Paket:
                $Receive_Status = $APIData->Data[0];
                if ((ord($Receive_Status) and ( ord(TXB_Receive_Status::XB_Receive_Packet_Acknowledged))) == ord(TXB_Receive_Status::XB_Receive_Packet_Acknowledged))
                {
//                  SendData('Receive_Paket(OK)',APIdata.data);
                    $this->SendDataToChild(substr($APIData->Data, 1));
                }
                else
                {
//                SendData('ReceivePaket(Error:'+inttohex(ord(Receive_Status),1)+')',APIdata.data);
                }
                break;
            case TXB_API_Command::XB_API_Remote_AT_Command_Responde:
                $ATData = new TXB_Command_Data();
                $ATData->ATCommand = substr($APIData->Data, 0, 2);
                $ATData->Status = $APIData->Data[2];
                $ATData->Data = substr($APIData->Data, 3);
//        SendData('Remote_AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',ATData.Data);                
                $this->SendDataToDevice($ATData);
                break;
            case TXB_API_Command::XB_API_IO_Data_Sample_Rx:
                $IOSample = new TXB_API_IO_Sample();
                $IOSample->Sample = $APIData->Data;
                $this->SendDataToDevice($IOSample);
                /*
                  SendData('IO_Data_Sample_Rx('+inttohex(ord(APIData.APICommand),2)+')',APIdata.data);
                  //        APIData.Data:=XB_ATCommandToString(XB_AT_IS)+chr(0)+APIdata.data;
                  SendToIODevice(APIdata);

                 */
                break;
            default:
                /*
                  SendData('unhandle('+inttohex(ord(APIData.APICommand),2)+')',APIdata.Data);

                 */
                break;
        }
        return true;
    }

    protected function SendDataToParent($Data)
    {
        // API-Daten verpacken und dann versenden.
        $JSONString = $Data->ToJSONString('{5971FB22-3F96-45AE-916F-AE3AC8CA8782}');
// Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        return true;
    }

################## DUMMYS / WOARKAROUNDS - protected

    private function WaitForResponse()
    {
        $TransmitStatusID = $this->GetIDForIdent('TransmitStatus');
        for ($i = 0; $i < 500; $i++)
        {
            if (GetValueInteger($TransmitStatusID) == 0xff)
                IPS_Sleep(10);
            else
            {
                if ($this->lock('TransmitStatus'))
                {
                    $ret = GetValueInteger($TransmitStatusID);
                    $this->unlock('TransmitStatus');
                    return $ret;
                }
                return false;
            }
        }
        return false;
    }

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

    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus'])
            parent::SetStatus($InstanceStatus);
    }

    protected function LogMessage($data, $cata)
    {
        
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
//                IPS_LogMessage((string)$this->InstanceID,"Lock:LMS_" . (string) $this->InstanceID . (string) $ident);
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
//                IPS_LogMessage((string)$this->InstanceID,"Unlock:LMS_" . (string) $this->InstanceID . (string) $ident);

        IPS_SemaphoreLeave("XBZB_" . (string) $this->InstanceID . (string) $ident);
    }

}

?>