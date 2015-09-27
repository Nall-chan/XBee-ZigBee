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
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -4);
        $this->RegisterVariableString("BufferOUT", "BufferOUT", "", -2);
        $this->RegisterVariableBoolean("WaitForResponse", "WaitForResponse", "", -1);
//        $this->RegisterVariableBoolean("ReplyEvent", "ReplyEvent", "", -5);
        $this->RegisterVariableBoolean("Connected", "Connected", "", -3);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        IPS_SetHidden($this->GetIDForIdent('BufferOUT'), true);
        IPS_SetHidden($this->GetIDForIdent('WaitForResponse'), true);
        IPS_SetHidden($this->GetIDForIdent('Connected'), true);
        if ($this->ReadPropertyString('NodeName')=='')
            $this->SetSummary (202);
        else
            $this->SetStatus (102);
          $this->SetSummary($this->ReadPropertyString('NodeName'));
    }

################## PRIVATE     
//---------------------------HELPER-FUNCTIONS-----------------------------------
//------------------------------------------------------------------------------

    private function RequestSendData($Data)
{
    /*
function TIPSXBZBSplitter.RequestSendData( Text: String): boolean;
var data : TXB_API_Data;
begin
  //Transmit Request
  result:= false;
  try
    fFrameIDLock.Enter;
    if fFrameID = MAXBYTE then fFrameID:=1
    else inc(fFrameID);
  finally
    fFrameIDLock.Leave;
  end;
  data.FrameID:=fFrameID;
  fReadyToSend.ResetEvent();
  data.APICommand:= XB_API_Transmit_Request;
  data.Data:= chr($00)+chr($00)+Text;
  if  SendToParent(Data) then// raise EIPSModuleObject.Create('Send Data Error')
  begin // erfolgreich gesendet =>
    if fDataReadyToReadReply.WaitFor(1000)=wrSignaled then   //warte auf Reply
    begin
      fDataReadyToReadReply.ResetEvent;
      fReadyToSend.SetEvent();
      if fTransmitStatus = XB_Transmit_OK then
      begin
{$IFDEF DEBUG}        Senddata('TX_Status','OK');{$ENDIF}
        result:= true;
      end else begin
        Senddata('TX_Status','Error: '+ XB_Transmit_Status_to_String(fTransmitStatus));
        raise EIPSModuleObject.Create(XB_Transmit_Status_to_String(fTransmitStatus));
      end;
    end else begin
      Senddata('TX_Status','Timeout');
      raise EIPSModuleObject.Create('Send Data Timeout')
    end;
  end;
end;
     */
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
            if (!$Sendok)
                throw new Exception('Error on forward Data');
        }
    }
    
    private function SendDataToChild($Data)
    {
        
    }
    
################## DATAPOINTS DEVICE

    
//--- IXBZBSendCMD implementation
//--- { Data Points } //vom XB-ZB Device
    private function ForwardDataFromDevice(TXB_Command_Data $ATData)
    {
        /*
         * // Sendet Remote AT Commandos umgesetzt in API-Frames an den Coordinator (Gateway)
function TIPSXBZBSplitter.SendXBZBCMDData(ATData: TXB_Command_Data):boolean; stdcall;
var APIdata : TXB_API_Data;
begin
  result:= false;
  if fKernelRunlevel<>KR_READY then exit;
  if not HasActiveParent() then
  begin
    raise EIPSModuleObject.Create('Instance has no active Parent Instance!');
    exit;
  end;
  APIdata.APICommand := XB_API_Remote_AT_Command;
  APIdata.FrameID:= ATdata.FrameID;
  APIdata.data := chr($02)+XB_ATCommandToString(ATdata.ATCommand)+ATdata.data;
  result := SendToParent(APIdata);
end;

         */
    }

    private function SendDataToDevice(TXB_Command_Data $ATData)
    {
        $Data = $ATData->ToJSONString('{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}');
        IPS_SendDataToChildren($this->InstanceID, $Data);
    }
        
    
    
################## Datapoints PARENT

    //--- IXBZBSplitter implementation
    //--- { Data Points } //vom XB-ZB Gateway
    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        if ($Data->DataID <> '{0C541DDF-CE0F-4113-A76F-B4836015212B}')
            return false;        
        
        /*
function TIPSXBZBSplitter.ReceiveXBZBAPIData(APIData: TXB_API_Data):boolean; stdcall;
var Receive_Status : TXB_Receive_Status;
    ATData         : TXB_Command_Data;
begin
  if APIdata.NodeName = GetProperty('NodeName') then
  begin
    case APIData.APICommand of
      XB_API_Transmit_Status:
      begin
{$IFDEF DEBUG}        SendData('TX_Status('+inttohex(ord(APIData.APICommand),2)+')',APIdata.Data);{$ENDIF}
        try
          fTransmitStatusLock.Enter;
          fTransmitStatus := TXB_Transmit_Status(APIdata.data[2]);
        finally
          fTransmitStatusLock.Leave;
        end;
        fDataReadyToReadReply.SetEvent;
      end;
      XB_API_Receive_Paket:
      begin
        Receive_Status := TXB_Receive_Status(ord(APIdata.data[1]));
        delete(APIdata.data,1,1); // Receive Status raus
        if (ord(Receive_Status) and ord(XB_Receive_Packet_Acknowledged)) = ord(XB_Receive_Packet_Acknowledged) then
        begin
          SendData('Receive_Paket(OK)',APIdata.data);
          SendToChild(APIdata.data);
        end else begin
          SendData('ReceivePaket(Error:'+inttohex(ord(Receive_Status),1)+')',APIdata.data);
        end;
      end;
      XB_API_Remote_AT_Command_Responde:
      begin                         // CMD[2] / STATUS[1] / DATA
        ATData.FrameID:=APIdata.FrameID;
        ATData.ATCommand := XB_StringToATCommand(copy(APIdata.data,1,2));
        ATData.Status:=TXB_Command_Status(ord(APIdata.data[3]));
        delete(APIdata.data,1,3); // CMD und Status raus
        ATData.Data := APIData.Data;
        SendData('Remote_AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',ATData.Data);
        SendToDevice(ATData);
      end;
      XB_API_IO_Data_Sample_Rx:
      begin
        SendData('IO_Data_Sample_Rx('+inttohex(ord(APIData.APICommand),2)+')',APIdata.data);
//        APIData.Data:=XB_ATCommandToString(XB_AT_IS)+chr(0)+APIdata.data;
        SendToIODevice(APIdata);
      end
      else
      begin
        SendData('unhandle('+inttohex(ord(APIData.APICommand),2)+')',APIdata.Data);
      end;
    end;
    result:=true;
  end else begin
    result:=false;
  end;
end;
         */
    }
    // TODO
    protected function SendDataToParent($Data)
    {
//Semaphore setzen
        if (!$this->HasActiveParent())
            throw new Exception("Instance has no active Parent.");
        if (!$this->lock("ToParent"))
        {
            throw new Exception("Can not send to Parent");
        }
// Daten senden
        try
        {
            IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{5971FB22-3F96-45AE-916F-AE3AC8CA8782}", "Buffer" => utf8_encode($Data))));
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