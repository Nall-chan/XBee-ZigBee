<?

require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

class XBZBDevice extends IPSModule
{
   private $DPin_Name = array('D0','D1','D2','D3','D4','D5','D6','D7','','','P0','P1','P2');
   private $APin_Name =  array('AD0','AD1','AD2','AD3','','','','VSS');

    private $AT_WriteCommand = array(
        TXB_AT_Command::XB_AT_D0,
        TXB_AT_Command::XB_AT_D1,
        TXB_AT_Command::XB_AT_D2,
        TXB_AT_Command::XB_AT_D3,
        TXB_AT_Command::XB_AT_D4,
        TXB_AT_Command::XB_AT_D5,
        TXB_AT_Command::XB_AT_D6,
        TXB_AT_Command::XB_AT_D7,
        TXB_AT_Command::XB_AT_P0,
        TXB_AT_Command::XB_AT_P1,
        TXB_AT_Command::XB_AT_P2,
        TXB_AT_Command::XB_AT_ID,
        TXB_AT_Command::XB_AT_SC,
        TXB_AT_Command::XB_AT_SD,
        TXB_AT_Command::XB_AT_ZS,
        TXB_AT_Command::XB_AT_NJ,
        TXB_AT_Command::XB_AT_DH,
        TXB_AT_Command::XB_AT_DL,
        TXB_AT_Command::XB_AT_NI,
        TXB_AT_Command::XB_AT_NH,
        TXB_AT_Command::XB_AT_BH,
        TXB_AT_Command::XB_AT_AR,
        TXB_AT_Command::XB_AT_DD,
        TXB_AT_Command::XB_AT_NT,
        TXB_AT_Command::XB_AT_NO,
        TXB_AT_Command::XB_AT_CR,
        TXB_AT_Command::XB_AT_SE,
        TXB_AT_Command::XB_AT_DE,
        TXB_AT_Command::XB_AT_CI,
        TXB_AT_Command::XB_AT_PL,
        TXB_AT_Command::XB_AT_PM,
        TXB_AT_Command::XB_AT_EE,
        TXB_AT_Command::XB_AT_EO,
        TXB_AT_Command::XB_AT_KY,
        TXB_AT_Command::XB_AT_NK,
        TXB_AT_Command::XB_AT_BD,
        TXB_AT_Command::XB_AT_NB,
        TXB_AT_Command::XB_AT_SB,
        TXB_AT_Command::XB_AT_RO,
        TXB_AT_Command::XB_AT_AP,
        TXB_AT_Command::XB_AT_AO,
        TXB_AT_Command::XB_AT_CT,
        TXB_AT_Command::XB_AT_GT,
        TXB_AT_Command::XB_AT_CC,
        TXB_AT_Command::XB_AT_SM,
        TXB_AT_Command::XB_AT_ST,
        TXB_AT_Command::XB_AT_SP,
        TXB_AT_Command::XB_AT_SN,
        TXB_AT_Command::XB_AT_SO,
        TXB_AT_Command::XB_AT_PO,
        TXB_AT_Command::XB_AT_PR,
        TXB_AT_Command::XB_AT_LT,
        TXB_AT_Command::XB_AT_RP,
        TXB_AT_Command::XB_AT_DO,
        TXB_AT_Command::XB_AT_IR,
        TXB_AT_Command::XB_AT_IC,
        TXB_AT_Command::XB_AT_VV);
    private $AT_ReadCommand = array(
        TXB_AT_Command::XB_AT_DN,
        TXB_AT_Command::XB_AT_ND,
        TXB_AT_Command::XB_AT_D0,
        TXB_AT_Command::XB_AT_D1,
        TXB_AT_Command::XB_AT_D2,
        TXB_AT_Command::XB_AT_D3,
        TXB_AT_Command::XB_AT_D4,
        TXB_AT_Command::XB_AT_D5,
        TXB_AT_Command::XB_AT_D6,
        TXB_AT_Command::XB_AT_D7,
        TXB_AT_Command::XB_AT_P0,
        TXB_AT_Command::XB_AT_P1,
        TXB_AT_Command::XB_AT_P2,
        TXB_AT_Command::XB_AT_IS,
        TXB_AT_Command::XB_AT_ID,
        TXB_AT_Command::XB_AT_SC,
        TXB_AT_Command::XB_AT_SD,
        TXB_AT_Command::XB_AT_ZS,
        TXB_AT_Command::XB_AT_NJ,
        TXB_AT_Command::XB_AT_JN,
        TXB_AT_Command::XB_AT_OP,
        TXB_AT_Command::XB_AT_OI,
        TXB_AT_Command::XB_AT_CH,
        TXB_AT_Command::XB_AT_NC,
        TXB_AT_Command::XB_AT_SH,
        TXB_AT_Command::XB_AT_SL,
        TXB_AT_Command::XB_AT_MY,
        TXB_AT_Command::XB_AT_MP,
        TXB_AT_Command::XB_AT_DH,
        TXB_AT_Command::XB_AT_DL,
        TXB_AT_Command::XB_AT_NI,
        TXB_AT_Command::XB_AT_NH,
        TXB_AT_Command::XB_AT_BH,
        TXB_AT_Command::XB_AT_AR,
        TXB_AT_Command::XB_AT_DD,
        TXB_AT_Command::XB_AT_NT,
        TXB_AT_Command::XB_AT_NO,
        TXB_AT_Command::XB_AT_NP,
        TXB_AT_Command::XB_AT_CR,
        TXB_AT_Command::XB_AT_SE,
        TXB_AT_Command::XB_AT_DE,
        TXB_AT_Command::XB_AT_CI,
        TXB_AT_Command::XB_AT_PL,
        TXB_AT_Command::XB_AT_PM,
        TXB_AT_Command::XB_AT_PP,
        TXB_AT_Command::XB_AT_EE,
        TXB_AT_Command::XB_AT_EO,
        TXB_AT_Command::XB_AT_KY,
        TXB_AT_Command::XB_AT_NK,
        TXB_AT_Command::XB_AT_BD,
        TXB_AT_Command::XB_AT_NB,
        TXB_AT_Command::XB_AT_SB,
        TXB_AT_Command::XB_AT_RO,
        TXB_AT_Command::XB_AT_AP,
        TXB_AT_Command::XB_AT_AO,
        TXB_AT_Command::XB_AT_CT,
        TXB_AT_Command::XB_AT_GT,
        TXB_AT_Command::XB_AT_CC,
        TXB_AT_Command::XB_AT_SM,
        TXB_AT_Command::XB_AT_ST,
        TXB_AT_Command::XB_AT_SP,
        TXB_AT_Command::XB_AT_SN,
        TXB_AT_Command::XB_AT_SO,
        TXB_AT_Command::XB_AT_PO,
        TXB_AT_Command::XB_AT_PR,
        TXB_AT_Command::XB_AT_LT,
        TXB_AT_Command::XB_AT_RP,
        TXB_AT_Command::XB_AT_DO,
        TXB_AT_Command::XB_AT_IR,
        TXB_AT_Command::XB_AT_IC,
        TXB_AT_Command::XB_AT_VV,
        TXB_AT_Command::XB_AT_VR,
        TXB_AT_Command::XB_AT_HV,
        TXB_AT_Command::XB_AT_AI,
        TXB_AT_Command::XB_AT_DB,
        TXB_AT_Command::XB_AT_VSS);

    public function Create()
    {
        parent::Create();
        $this->RequireParent("{B92E4FAA-1754-4FDC-8F7F-957C65A7ABB8}");
        $this->RegisterPropertyBoolean("EmulateStatus", false);
        $this->RegisterPropertyInteger("Interval", 0);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->RegisterVariableString("ReplyATData", "ReplyATData", "", -3);
        $this->RegisterVariableInteger("FrameID", "FrameID", "", -2);
        IPS_SetHidden($this->GetIDForIdent('ReplyATData'), true);
        IPS_SetHidden($this->GetIDForIdent('FrameID'), true);

        // if fKernelRunlevel = KR_READY then


        $this->RegisterTimer('RequestPinState', $this->ReadPropertyInteger('Interval'), 'XBee_RequestState($_IPS[\'TARGET\']);');
        $this->ReadPinConfig();
        $this->RequestPinState();

        /*
          fFrameID: byte;  // integer
          fFrameIDLock : TCriticalSection;  //Lock
          fReadyToSend : TEvent; // wird Lock
          fDataReadyToReadReply: TEvent; // Wenn ReplyATData <> ""
          fDelayTimerActive: boolean; //später ?
          fReplyATData : TXB_Command_Data; // String JSON
          fReplyATDataLock : TCriticalSection;  // Lock
         */
    }

################## PRIVATE     
################## ActionHandler

    public function ActionHandler($StatusVariableIdent, $Value)
    {
        
    }
    
    private function ActionHandlerDPin($StatusVariableIdent, $Value)
    {
        /*
procedure TIPSXBZBDevice.ActionHandlerDPin(StatusVariable: String; Value: Variant);
var IPSVarID: word;
//    cVariable : TIPSVariable;
begin
  if StatusVariable =emptyStr then exit;
  try
    IPSVarID := GetStatusVariableID(StatusVariable);
  except
    IPSVarID := 0;
  end;
  if IPSVarID = 0 then exit;
  if  not HasActiveParent then
  begin
    raise EIPSModuleObject.Create('Instance has no active Parent Instance!');
    exit;
  end;
  if not VarIsType(Value,varBoolean) then
  begin
    raise EIPSModuleObject.Create('Wrong Datatype for '+ IntToStr(IPSVarID));
    exit;
  end;
  WriteBoolean(StatusVariable,Variants.VarAsType(Value,varBoolean));
end;
*/
}
################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    public function RequestState()
    {
        $this->RequestPinState();
    }

    public function ReadConfig()
    {
        $this->ReadPinConfig();
    }

    public function WriteBoolean(string $Pin, boolean $Value)
    {
        /*
        procedure TIPSXBZBDevice.WriteBoolean(Pin:String; Value: Boolean); stdcall;
var IPSVarID       : word;
    ValueStr       : byte;
    cVariable      : TIPSVariable;
    ATdata         : TXB_Command_Data;
begin
  if Pin =emptyStr then
  begin
    raise EIPSModuleObject.Create('Pin is not Set!');
    exit;
  end;
  try
    IPSVarID := GetStatusVariableID(Pin);
  except
    IPSVarID := 0;
  end;
  if IPSVarID = 0 then
  begin
    raise EIPSModuleObject.Create('Pin not exists! Try WriteParameter.');
    exit;
  end;
  if  not HasActiveParent then
  begin
    raise EIPSModuleObject.Create('Instance has no active Parent Instance!');
    exit;
  end;
  if IPSVarID<> 0 then
     begin
       cVariable :=fKernel.VariableManager.GetVariable(IPSVarID);
       if cVariable.VariableValue.ValueType <> vtBoolean then
       begin
         raise EIPSModuleObject.Create('Wrong Datatype for '+ IntToStr(IPSVarID));
       end else begin
         if Value then ValueStr:=$05
           else ValueStr:=$04;
         ATData.ATCommand:=XB_StringToATCommand(Pin);
         ATdata.Data:= chr(ValueStr);
         if SendCommand(ATdata).Status <> XB_Command_OK then
           raise EIPSModuleObject.Create('Error on Send Command '+ IntToStr(IPSVarID))
           else if GetProperty('EmulateStatus') = true then fKernel.VariableManager.WriteVariableBoolean(IPSVarID,Value);
       end;
       cVariable.free;
     end;
   end;

         */
    }

    public function WriteParameter(string $Parameter, string $Value)
    {
/*
 procedure TIPSXBZBDevice.WriteParameter(Parameter: String; Value: String); stdcall;
var ATData : TXB_Command_Data;
    ATCMD  : TXB_AT_Command;
//    I      : integer;
    Valid  : boolean;
begin
  Valid:=false;
  if Value = emptyStr then
  begin
    raise EIPSModuleObject.Create('Value is empty!');
    exit;
  end;
  for ATCMD in AT_WriteCommand do
  begin
    if Parameter = XB_ATCommandToString(ATCMD) then
    begin
      Valid:=true;
      break;
    end;
  end;
  if  Valid then
  begin
    ATData.ATCommand:=ATCMD;
    ATData.Data:=Value;
    SendCommand(ATData);
  end else begin
    raise EIPSModuleObject.Create('Unknown Parameter: '+ Parameter);
  end;
end;

 */        
    }

    public function ReadParameter(string $Parameter)
    {
/*
function TIPSXBZBDevice.ReadParameter(Parameter: String):String; stdcall;
var ATData : TXB_Command_Data;
    ATCMD  : TXB_AT_Command;
//    I      : integer;
    Valid  : boolean;
begin
  Valid:=false;
  for ATCMD in AT_ReadCommand do
  begin
    if Parameter = XB_ATCommandToString(ATCMD) then
    begin
      Valid:=true;
      break;
    end;
  end;
  if Valid then
  begin
    ATData.ATCommand:=ATCMD;
    ATData.Status:=XB_Command_Tx_Failure;
    ATData.Data:='';
    ATData := SendCommand(ATData);
    if ATData.Status = XB_Command_OK then
    begin // Ergebnis lesen und zurückgeben; Fehlermeldung wurde schon vorher erzeugt schon
      Result:=ATData.Data;
    end else begin
      raise EIPSModuleObject.Create('Error on Read Parameter ('+Parameter+'): '+ XB_Command_Status_To_String(ATData.Status));
    end;
  end else begin
    raise EIPSModuleObject.Create('Unknown Parameter: '+ Parameter)
 end;
end;

 */        
    }

################## Datapoints

    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        if ($Data->DataID <> '{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}')
            return false;
        if (property_exists($Data, 'ATCommand'))
        {
            $ATData = new TXB_Command_Data();
            $ATData->GetDataFromJSONObject($Data);
            $this->ReceiveCMDData($ATData);
            return true;
        }
        if (property_exists($Data, 'Sample'))
        {
            $IOSample = new TXB_API_IO_Sample();
            $IOSample->GetDataFromJSONObject($Data);
            $this->ReceiveIOSample($IOSample);
            return true;
        }
        return false;
    }

    private function ReceiveCMDData(TXB_Command_Data $ATData)
    {
        /*
        var  IOSample       : TXB_API_IO_Sample;
     VarID          : TIPSID;
begin
  try
    fReplyATDataLock.Enter;
    fReplyATData:= ATData;
  finally
    fReplyATDataLock.Leave;
  end;
  SendData('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',ATData.data);
  fDataReadyToReadReply.SetEvent();
  if  fReplyATData.Status = XB_Command_OK then
  begin
    case ATData.ATCommand of
      XB_AT_D0,
      XB_AT_D1,
      XB_AT_D2,
      XB_AT_D3,
      XB_AT_D4,
      XB_AT_D5,
      XB_AT_D6,
      XB_AT_D7,
      XB_AT_P0,
      XB_AT_P1,
      XB_AT_P2:
      begin
        // Neuen Wert darstellen und Variable anlegen und Schaltbar machen wenn Value 4 oder 5 sonst nicht schaltbar
        if Length(ATData.data) = 1 then
        begin
          case ord(ATData.data[1]) of
            0,
            1:
            begin
              try
                VarID := GetStatusVariableID(XB_ATCommandToString(ATData.ATCommand));
              except
                VarID := 0;
              end;
              if VarID <> 0 then
              begin
                fKernel.VariableManagerEx.SetVariableAction(VarID, 0);
                fKernel.VariableManagerEx.SetVariableProfile(VarID,'');
              end;
            end;
            2:
            begin
              GetOrCreateAPinVariable('A'+XB_ATCommandToString(ATData.ATCommand));
              try
                VarID := GetStatusVariableID(XB_ATCommandToString(ATData.ATCommand));
              except
                VarID := 0;
              end;
              if VarID <> 0 then
              begin
                fKernel.VariableManagerEx.SetVariableAction(VarID, 0);
                fKernel.VariableManagerEx.SetVariableProfile(VarID,'');
              end;
            end;
            3:
            begin
              VarID := GetOrCreateDPinVariable(XB_ATCommandToString(ATData.ATCommand));
              fKernel.VariableManagerEx.SetVariableAction(VarID, 0);
              fKernel.VariableManagerEx.SetVariableProfile(VarID,'');
            end;
            4:
            begin
              VarID := GetOrCreateDPinVariable(XB_ATCommandToString(ATData.ATCommand),ActionHandlerDPin);
              fKernel.VariableManagerEx.SetVariableProfile(VarID,'~Switch');
              fKernel.VariableManagerEx.SetVariableAction(VarID, fInstanceID);
              fKernel.VariableManager.WriteVariableBoolean(VarID,false);
            end;
            5:
            begin
              VarID := GetOrCreateDPinVariable(XB_ATCommandToString(ATData.ATCommand),ActionHandlerDPin);
              fKernel.VariableManagerEx.SetVariableProfile(VarID,'~Switch');
              fKernel.VariableManagerEx.SetVariableAction(VarID, fInstanceID);
              fKernel.VariableManager.WriteVariableBoolean(VarID,true);
            end;
          end;
        end;
      end;
      XB_AT_IS:
      begin
        if not fDelayTimerActive then
        begin
          IOSample.Status:=XB_Receive_Packet_Acknowledged;
          IOSample.Sample:= ATData.Data;
          DecodeIOSample(IOSample);
        end;
      end;
    end;
  end;
end;

         */
    }
    private function ReceiveIOSample(TXB_API_IO_Sample $IOSample)
    {
        /*
           senddata('Receive_IO_Sample(92)',Sample);
  IOSample.Status:=TXB_Receive_Status(ord(Sample[1]));
  delete(Sample,1,1); // Receive Status raus
  IOSample.Sample:= Sample;
  DecodeIOSample(IOSample);

         */
    }
    
    private function DecodeIOSample($IOSample)
    {
     /*
procedure TIPSXBZBDevice.DecodeIOSample(IOSample: TXB_API_IO_Sample);
var ActiveDPins : word;
    ActiveAPins : byte;
    Pins        : word;
    i           : integer;
    Bit         : integer;
    ID          : word;
    PinAValue   : word;
begin
  delete(IOSample.Sample,1,1); // Number Sampley raus da immer 1
  ActiveDPins := TwoByteToWord(ord(IOSample.Sample[2]),ord(IOSample.Sample[1]));
  ActiveAPins := ord(IOSample.Sample[3]);
  delete(IOSample.Sample,1,3);
  if ActiveDPins <> 0 then  //D Pins aktiv
  begin
    Pins := TwoByteToWord(ord(IOSample.Sample[2]),ord(IOSample.Sample[1]));
    delete(IOSample.Sample,1,2);
    for I:=high(DPin_Name) downto low(DPin_Name) do
    begin
      if DPin_Name[i] = emptyStr then continue;
      Bit:=trunc(Power(2,ord(i)));
      if ActiveDPins and Bit = Bit then
      begin
{$IFDEF DEBUG}        SendData('DPIN','I:'+floattostr(Power(2,ord(i))));{$ENDIF}
        ID:=GetOrCreateDPinVariable(DPin_Name[i]);
        if Pins and Bit = Bit then
        begin
{$IFDEF DEBUG}          SendData(DPin_Name[i],'true - Bit:'+inttostr(ord(i)));{$ENDIF}
            fKernel.VariableManager.WriteVariableBoolean(ID,true);
        end else begin
{$IFDEF DEBUG}          SendData(DPin_Name[i],'false - Bit:'+inttostr(ord(i)));{$ENDIF}
            fKernel.VariableManager.WriteVariableBoolean(ID,false);
        end;
      end;
    end;
  end;
  if ActiveAPins <> 0 then  //A Pins aktiv
  begin
    for I:=low(APin_Name) to high(APin_Name) do
    begin
      if APin_Name[i] = emptyStr then continue;
      Bit:=trunc(Power(2,ord(i)));
      if ActiveAPins and Bit = Bit then
      begin
{$IFDEF DEBUG}        SendData('APIN','I:'+floattostr(Power(2,ord(i))));{$ENDIF}
        PinAValue := TwoByteToWord(ord(IOSample.Sample[2]),ord(IOSample.Sample[1]));
        delete(IOSample.Sample,1,2);
        if APin_Name[i] = 'VSS' then
        begin
          ID:=GetOrCreateAPinVariable('VSS',vtFloat,'~Volt');
          PinAValue := trunc(PinAValue * 1.171875);
              fKernel.VariableManager.WriteVariableFloat(ID,PinAValue/1000);
        end else begin
          ID:=GetOrCreateAPinVariable(APin_Name[i]);
          PinAValue := trunc(PinAValue * 1.171875);
              fKernel.VariableManager.WriteVariableInteger(ID,PinAValue);
        end;
      end;
    end;
  end;

      */   
    }
private function     ReadPinConfig()
{
    /*

    procedure TIPSXBZBDevice.ReadPinConfig();
var ATData  : TXB_Command_Data;
    I     : integer;
begin
  for I:=high(DPin_Name) downto low(DPin_Name) do
  begin
    if DPin_Name[i] = EmptyStr then continue;
    ATData.ATCommand:= XB_StringToATCommand(DPin_Name[i]);
    SendCommand(ATData);
  end;
end;
     * 
     */
    
}
//------------------------------------------------------------------------------
private function RequestPinState()
{
    /*
procedure TIPSXBZBDevice.RequestPinState();
var ATData  : TXB_Command_Data;
begin
  ATData.ATCommand:= XB_AT_IS;
  SendCommand(ATData);
end;
*/
    
}
private function SendCommand(TXB_Command_Data $ATData)
{
    /*
    function TIPSXBZBDevice.SendCommand(ATData: TXB_Command_Data): TXB_Command_Data;
begin
  result.Status:= XB_Command_Error;
  try
    fFrameIDLock.Enter;
    if fFrameID = MAXBYTE then fFrameID:=1
    else inc(fFrameID);
  finally
      fFrameIDLock.Leave;
  end;
  ATData.FrameID:=fFrameID;
  ATData.Status:=XB_Command_OK;
  if SendToParent(ATdata) then //raise EIPSModuleObject.Create('Error on Send Command. Node unknown ?')
  begin
    if fDataReadyToReadReply.WaitFor(1000)=wrSignaled then   //warte auf Reply
    begin
      Result:=fReplyATData;
      fDataReadyToReadReply.ResetEvent;
      if Result.Status = XB_Command_OK then
      begin
{$IFDEF DEBUG}        Senddata('AT_Command_Status','OK');{$ENDIF}
      end else begin
        Senddata('AT_Command_Status','Error: '+ XB_Command_Status_To_String(fReplyATData.Status));
        raise EIPSModuleObject.Create(XB_Command_Status_To_String(fReplyATData.Status));
      end;
    end else begin
      Senddata('AT_Command_Status','Timeout');
      raise EIPSModuleObject.Create('Send Data Timeout')
    end;
  end;
end;
     */
}

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
            IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}", "Buffer" => utf8_encode($Data))));
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