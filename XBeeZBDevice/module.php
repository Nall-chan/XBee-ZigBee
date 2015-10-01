<?

require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

class XBZBDevice extends IPSModule
{

    private $DPin_Name = array('D0', 'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', '', '', 'P0', 'P1', 'P2');
    private $APin_Name = array('AD0', 'AD1', 'AD2', 'AD3', '', '', '', 'VSS');
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

    public function RequestAction($Ident, $Value)
    {
        if (is_bool($Value) === false)
            throw new Exception('Wrong Datatype for ' . $Ident);
        $this->WriteBoolean($Ident, (bool) $Value);
    }

################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    public function RequestState()
    {
        if (!$this->HasActiveParent())
            throw new Exception('Instance has no active Parent Instance!');

        $this->RequestPinState();
    }

    public function ReadConfig()
    {
        if (!$this->HasActiveParent())
            throw new Exception('Instance has no active Parent Instance!');

        $this->ReadPinConfig();
    }

    public function WriteBoolean(string $Pin, boolean $Value)
    {
        if ($Pin == '')
            throw new Exception('Pin is not Set!');
        if (!in_array($Pin, $this->DPin_Name))
            throw new Exception('Pin not exists!');
        $VarID = @$this->GetIDForIdent($Pin);
        if ($VarID === false)
            throw new Exception('Pin not exists! Try WriteParameter.');
        if (IPS_GetVariable($VarID)['VariableType'] !== 0)
            throw new Exception('Wrong Datatype for ' . $VarID);
        if ($Value === true)
            $ValueStr = 0x05;
        else
            $ValueStr = 0x04;
        $ATData = new TXB_Command_Data();
        $ATData->ATCommand = $Pin;
        $ATData->Data = chr($ValueStr);
        /*        $ResponseATData = $this->SendCommand($ATData);
          if ($ResponseATData->Status <> TXB_Command_Status::XB_Command_OK)
          throw new Exception('Error on Send Command ' . $VarID); */
        $this->SendCommand($ATData);
        if ($this->ReadPropertyBoolean('EmulateStatus'))
            SetValue($VarID, $Value);
        return true;
    }

    public function WriteParameter(string $Parameter, string $Value)
    {
        if ($Value == "")
            throw new Exception('Value is empty!');
        if (!in_array($Parameter, $this->AT_WriteCommand))
            throw new Exception('Unknown Parameter: ' . $Parameter);
        $ATData = new TXB_Command_Data();
        $ATData->ATCommand = $Parameter;
        $ATData->Data = $Value;
        /*        $ResponseATData = $this->SendCommand($ATData);
          if ($ResponseATData->Status <> TXB_Command_Status::XB_Command_OK)
          throw new Exception('Error on Send Command ' . $Parameter); */
        $this->SendCommand($ATData);
        return true;
    }

    public function ReadParameter(string $Parameter)
    {
        if (!in_array($Parameter, $this->AT_ReadCommand))
            throw new Exception('Unknown Parameter: ' . $Parameter);
        $ATData = new TXB_Command_Data();
        $ATData->ATCommand = $Parameter;
        $ATData->Data = '';
        $ResponseATData = $this->SendCommand($ATData);
        return $ResponseATData->Data;
    }

################## Datapoints

    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        
        if ($Data->DataID <> '{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}')
            return false;
        if (property_exists($Data, 'ATCommand'))
        {
           IPS_LogMessage('ReceiveCMDData:'.$this->InstanceID,  print_r($Data,1));

            $ATData = new TXB_Command_Data();
            $ATData->GetDataFromJSONObject($Data);
            $this->ReceiveCMDData($ATData);
            return true;
        }
        if (property_exists($Data, 'Sample'))
        {
            IPS_LogMessage('ReceiveIOSample:'.$this->InstanceID,  print_r($Data,1));
            $IOSample = new TXB_API_IO_Sample();
            $IOSample->GetDataFromJSONObject($Data);
            $this->DecodeIOSample($IOSample);
            return true;
        }
        return false;
    }

    private function ReceiveCMDData(TXB_Command_Data $ATData)
    {
        $ReplyATDataID = $this->GetIDForIdent('ReplyATData');
        $ReplyATData = $ATData->ToJSONString('');

        if (!$this->lock('ReplyATData'))
            throw new Exception('ReplyATData is locked');
//         SendData('AT_Command_Responde('+XB_ATCommandToString(ATData.ATCommand)+')',ATData.data);        
        SetValueString($ReplyATDataID, $ReplyATData);
        $this->unlock('ReplyATData');
        if ($ATData->Status <> TXB_Command_Status::XB_Command_OK)
            return;

        switch ($ATData->ATCommand)
        {
            case TXB_AT_Command::XB_AT_D0:
            case TXB_AT_Command::XB_AT_D1:
            case TXB_AT_Command::XB_AT_D2:
            case TXB_AT_Command::XB_AT_D3:
            case TXB_AT_Command::XB_AT_D4:
            case TXB_AT_Command::XB_AT_D5:
            case TXB_AT_Command::XB_AT_D6:
            case TXB_AT_Command::XB_AT_D7:
            case TXB_AT_Command::XB_AT_P0:
            case TXB_AT_Command::XB_AT_P1:
            case TXB_AT_Command::XB_AT_P2:
                // Neuen Wert darstellen und Variable anlegen und Schaltbar machen wenn Value 4 oder 5 sonst nicht schaltbar
                if (strlen($ATData->Data) <> 1)
                    return;
                switch (ord($ATData->Data))
                {
                    case 0:
                    case 1:
                        $VarID = @$this->GetIDForIdent($ATData->ATCommand);
                        if ($VarID <> 0)
                        {
                            $this->DisableAction($ATData->ATCommand);
                            IPS_SetVariableCustomProfile($VarID, '');
                        }
                        break;
                    case 2:

                        $VarID = $this->RegisterVariableInteger('A' . $ATData->ATCommand, 'A' . $ATData->ATCommand);
                        if ($VarID <> 0)
                        {
                            $this->DisableAction($ATData->ATCommand);
                            IPS_SetVariableCustomProfile($VarID, '');
                        }
                        break;
                    case 3:
                        $VarID = $this->RegisterVariableBoolean($ATData->ATCommand, $ATData->ATCommand);
                        $this->DisableAction($ATData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '');
                        break;
                    case 4:
                        $VarID = $this->RegisterVariableBoolean($ATData->ATCommand, $ATData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '~Switch');
                        $this->EnableAction($ATData->ATCommand);
                        SetValueBoolean($VarID, false);
                        break;
                    case 5:
                        $VarID = $this->RegisterVariableBoolean($ATData->ATCommand, $ATData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '~Switch');
                        $this->EnableAction($ATData->ATCommand);
                        SetValueBoolean($VarID, true);
                        break;
                }
                break;
            case TXB_AT_Command::XB_AT_IS:
//                if not fDelayTimerActive then
                $IOSample = new TXB_API_IO_Sample();
                $IOSample->Status = TXB_Receive_Status::XB_Receive_Packet_Acknowledged;
                $IOSample->Sample = $ATData->Data;
                $this->DecodeIOSample($IOSample);
                break;
        }
    }

    private function DecodeIOSample(TXB_API_IO_Sample $IOSample)
    {
        $ActiveDPins = unpack("n", substr($IOSample->Sample, 1, 2))[1];
        $ActiveAPins = ord($IOSample->Sample[3]);
        if ($ActiveDPins <> 0)
        {
            $PinValue = unpack("n", substr($IOSample->Sample, 4, 2))[1];
            foreach ($this->DPin_Name as $Index => $Pin_Name)
            {
                if ($Pin_Name == '')
                    continue;
                $Bit = pow(2, $Index);
                if (($ActiveDPins & $Bit) == $Bit)
                {
//                        {$IFDEF DEBUG}        SendData('DPIN','I:'+floattostr(Power(2,ord(i))));{$ENDIF}
                    $VarID = $this->RegisterVariableBoolean($Pin_Name, $Pin_Name);

                    if (($PinValue & $Bit) == $Bit)
                    {
//                            {$IFDEF DEBUG}          SendData(DPin_Name[i],'true - Bit:'+inttostr(ord(i)));{$ENDIF}
                        SetValueBoolean($VarID, true);
                    }
                    else
                    {
//                            {$IFDEF DEBUG}          SendData(DPin_Name[i],'false - Bit:'+inttostr(ord(i)));{$ENDIF}
                        SetValueBoolean($VarID, false);
                    }
                }
            }
        }
        if ($ActiveAPins <> 0)
        {
            foreach ($this->APin_Name as $Index => $Pin_Name)
            {
                if ($Pin_Name == "")
                    continue;
                $Bit = pow(2, $Index);
                if (($ActiveAPins & $Bit) == $Bit)
                {
//                    {$IFDEF DEBUG}        SendData('APIN','I:'+floattostr(Power(2,ord(i))));{$ENDIF}
                    $PinAValue = unpack("N", substr($IOSample->Sample, 4 + $Index, 2))[1];
                    $PinAValue = $PinAValue * 1.171875;
                    if ($Pin_Name == 'VSS')
                    {
                        $VarID = $this->RegisterVariableFloat('VSS', 'VSS', '~Volt');
                        SetValueFloat($VarID, $PinAValue / 1000);
                    }
                    else
                    {
                        $VarID = $this->RegisterVariableInteger($Pin_Name, $Pin_Name);
                        SetValueFloat($VarID, $PinAValue);
                    }
                }
            }
        }
    }

    private function ReadPinConfig()
    {
        $ATData = new TXB_Command_Data();
        $ATData->Data = '';
        foreach ($this->DPin_Name as $Pin)
        {
            if ($Pin== '') continue;
            
            $ATData->ATCommand = $Pin;
            $this->SendCommand($ATData);
        }
    }

//------------------------------------------------------------------------------
    private function RequestPinState()
    {
        $ATData = new TXB_Command_Data();
        $ATData->ATCommand = TXB_AT_Command::XB_AT_IS;
        $this->SendCommand($ATData);
    }

    private function SendCommand(TXB_Command_Data $ATData)
    {
        if (!$this->HasActiveParent())
            throw new Exception("Instance has no active Parent.");

//        $ReplayATData = new TXB_Command_Data();
        $FrameID = $this->GetIDForIdent('FrameID');
        $ReplyATDataID = $this->GetIDForIdent('ReplyATData');
        if (!$this->lock('RequestSendData'))
            throw new Exception('RequestSendData is locked');
        $Frame = GetValueInteger($FrameID);
        if ($Frame == 255)
            $Frame = 1;
        else
            $Frame++;
        SetValueInteger($FrameID, $Frame);
        if (!$this->lock('ReplyATData'))
        {
            $this->unlock('RequestSendData');
            throw new Exception('ReplyATData is locked');
        }
        SetValueString($ReplyATDataID, '');
        $this->unlock('ReplyATData');
        $ATData->FrameID = $Frame;
        try
        {
            $this->SendDataToParent($ATData);
        }
        catch (Exception $exc)
        {
            $this->unlock('RequestSendData');
            throw new Exception($exc);
        }
        $ReplayATData = $this->WaitForResponse();

//        IPS_LogMessage('ReplayATData:'.$this->InstanceID,print_r($ReplayATData,1));

        if ($ReplayATData === false)
        {
//          Senddata('TX_Status','Timeout');
            $this->unlock('RequestSendData');
            throw new Exception('Send Data Timeout');
        }
        if ($ReplayATData->Status == TXB_Command_Status::XB_Command_OK)
        {
//            Senddata('TX_Status','OK')
            $this->unlock('RequestSendData');
            return $ReplayATData;
        }
//        Senddata('TX_Status','Error: '+ XB_Transmit_Status_to_String(fTransmitStatus));
        $this->unlock('RequestSendData');

        throw new Exception('Error on Transmit:' . ord($ReplayATData->Status));
    }

    protected function SendDataToParent($Data)
    {
        // API-Daten verpacken und dann versenden.
        $JSONString = $Data->ToJSONString('{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}');
        IPS_LogMessage('SendDataToSplitter:'.$this->InstanceID,$JSONString);
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        return true;
    }

################## DUMMYS / WOARKAROUNDS - protected

    private function WaitForResponse()
    {
        $ReplyATDataID = $this->GetIDForIdent('ReplyATData');
        for ($i = 0; $i < 500; $i++)
        {
            if (GetValueString($ReplyATDataID) === '')
                IPS_Sleep(10);
            else
            {
                if ($this->lock('ReplyATData'))
                {
                    $ret = GetValueString($ReplyATDataID);
                    SetValueString($ReplyATDataID, '');
                    $this->unlock('ReplyATData');
                    $JSON = json_decode($ret);
                    $ATData = new TXB_Command_Data();
                    $ATData->GetDataFromJSONObject($JSON);
                    return $ATData;
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