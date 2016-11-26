<?

require_once(__DIR__ . "/../XBeeZBClass.php");  // diverse Klassen

class XBZBDevice extends IPSModule
{

    use DebugHelper,
        InstanceStatus;

    private $DPin_Name = array('D0', 'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', '', '', 'P0', 'P1', 'P2');
    private $APin_Name = array('AD0', 'AD1', 'AD2', 'AD3', '', '', '', 'VSS');
    private $AT_WriteCommand = array(
        TXB_AT_Commands::AT_D0,
        TXB_AT_Commands::AT_D1,
        TXB_AT_Commands::AT_D2,
        TXB_AT_Commands::AT_D3,
        TXB_AT_Commands::AT_D4,
        TXB_AT_Commands::AT_D5,
        TXB_AT_Commands::AT_D6,
        TXB_AT_Commands::AT_D7,
        TXB_AT_Commands::AT_P0,
        TXB_AT_Commands::AT_P1,
        TXB_AT_Commands::AT_P2,
        TXB_AT_Commands::AT_ID,
        TXB_AT_Commands::AT_SC,
        TXB_AT_Commands::AT_SD,
        TXB_AT_Commands::AT_ZS,
        TXB_AT_Commands::AT_NJ,
        TXB_AT_Commands::AT_DH,
        TXB_AT_Commands::AT_DL,
        TXB_AT_Commands::AT_NI,
        TXB_AT_Commands::AT_NH,
        TXB_AT_Commands::AT_BH,
        TXB_AT_Commands::AT_AR,
        TXB_AT_Commands::AT_DD,
        TXB_AT_Commands::AT_NT,
        TXB_AT_Commands::AT_NO,
        TXB_AT_Commands::AT_CR,
        TXB_AT_Commands::AT_SE,
        TXB_AT_Commands::AT_DE,
        TXB_AT_Commands::AT_CI,
        TXB_AT_Commands::AT_PL,
        TXB_AT_Commands::AT_PM,
        TXB_AT_Commands::AT_EE,
        TXB_AT_Commands::AT_EO,
        TXB_AT_Commands::AT_KY,
        TXB_AT_Commands::AT_NK,
        TXB_AT_Commands::AT_BD,
        TXB_AT_Commands::AT_NB,
        TXB_AT_Commands::AT_SB,
        TXB_AT_Commands::AT_RO,
        TXB_AT_Commands::AT_AP,
        TXB_AT_Commands::AT_AO,
        TXB_AT_Commands::AT_CT,
        TXB_AT_Commands::AT_GT,
        TXB_AT_Commands::AT_CC,
        TXB_AT_Commands::AT_SM,
        TXB_AT_Commands::AT_ST,
        TXB_AT_Commands::AT_SP,
        TXB_AT_Commands::AT_SN,
        TXB_AT_Commands::AT_SO,
        TXB_AT_Commands::AT_PO,
        TXB_AT_Commands::AT_PR,
        TXB_AT_Commands::AT_LT,
        TXB_AT_Commands::AT_RP,
        TXB_AT_Commands::AT_DO,
        TXB_AT_Commands::AT_IR,
        TXB_AT_Commands::AT_IC,
        TXB_AT_Commands::AT_VV);
    private $AT_ReadCommand = array(
        TXB_AT_Commands::AT_DN,
        TXB_AT_Commands::AT_ND,
        TXB_AT_Commands::AT_D0,
        TXB_AT_Commands::AT_D1,
        TXB_AT_Commands::AT_D2,
        TXB_AT_Commands::AT_D3,
        TXB_AT_Commands::AT_D4,
        TXB_AT_Commands::AT_D5,
        TXB_AT_Commands::AT_D6,
        TXB_AT_Commands::AT_D7,
        TXB_AT_Commands::AT_P0,
        TXB_AT_Commands::AT_P1,
        TXB_AT_Commands::AT_P2,
        TXB_AT_Commands::AT_IS,
        TXB_AT_Commands::AT_ID,
        TXB_AT_Commands::AT_SC,
        TXB_AT_Commands::AT_SD,
        TXB_AT_Commands::AT_ZS,
        TXB_AT_Commands::AT_NJ,
        TXB_AT_Commands::AT_JN,
        TXB_AT_Commands::AT_OP,
        TXB_AT_Commands::AT_OI,
        TXB_AT_Commands::AT_CH,
        TXB_AT_Commands::AT_NC,
        TXB_AT_Commands::AT_SH,
        TXB_AT_Commands::AT_SL,
        TXB_AT_Commands::AT_MY,
        TXB_AT_Commands::AT_MP,
        TXB_AT_Commands::AT_DH,
        TXB_AT_Commands::AT_DL,
        TXB_AT_Commands::AT_NI,
        TXB_AT_Commands::AT_NH,
        TXB_AT_Commands::AT_BH,
        TXB_AT_Commands::AT_AR,
        TXB_AT_Commands::AT_DD,
        TXB_AT_Commands::AT_NT,
        TXB_AT_Commands::AT_NO,
        TXB_AT_Commands::AT_NP,
        TXB_AT_Commands::AT_CR,
        TXB_AT_Commands::AT_SE,
        TXB_AT_Commands::AT_DE,
        TXB_AT_Commands::AT_CI,
        TXB_AT_Commands::AT_PL,
        TXB_AT_Commands::AT_PM,
        TXB_AT_Commands::AT_PP,
        TXB_AT_Commands::AT_EE,
        TXB_AT_Commands::AT_EO,
        TXB_AT_Commands::AT_KY,
        TXB_AT_Commands::AT_NK,
        TXB_AT_Commands::AT_BD,
        TXB_AT_Commands::AT_NB,
        TXB_AT_Commands::AT_SB,
        TXB_AT_Commands::AT_RO,
        TXB_AT_Commands::AT_AP,
        TXB_AT_Commands::AT_AO,
        TXB_AT_Commands::AT_CT,
        TXB_AT_Commands::AT_GT,
        TXB_AT_Commands::AT_CC,
        TXB_AT_Commands::AT_SM,
        TXB_AT_Commands::AT_ST,
        TXB_AT_Commands::AT_SP,
        TXB_AT_Commands::AT_SN,
        TXB_AT_Commands::AT_SO,
        TXB_AT_Commands::AT_PO,
        TXB_AT_Commands::AT_PR,
        TXB_AT_Commands::AT_LT,
        TXB_AT_Commands::AT_RP,
        TXB_AT_Commands::AT_DO,
        TXB_AT_Commands::AT_IR,
        TXB_AT_Commands::AT_IC,
        TXB_AT_Commands::AT_VV,
        TXB_AT_Commands::AT_VR,
        TXB_AT_Commands::AT_HV,
        TXB_AT_Commands::AT_AI,
        TXB_AT_Commands::AT_DB,
        TXB_AT_Commands::AT_VSS);

    public function Create()
    {
        parent::Create();
        $this->ConnectParent("{B92E4FAA-1754-4FDC-8F7F-957C65A7ABB8}");
        $this->RegisterPropertyBoolean("EmulateStatus", false);
        $this->RegisterPropertyInteger("Interval", 0);
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        if (IPS_GetKernelRunlevel() <> KR_READY)
            return;
        $this->UnregisterVariable("ReplyATData");
        $this->UnregisterVariable("FrameID");
        $this->RegisterTimer('RequestPinState', $this->ReadPropertyInteger('Interval') * 1000, 'XBee_RequestState($_IPS[\'TARGET\']);');
        $this->ReadPinConfig();
        $this->RequestPinState();
    }

################## PRIVATE     
################## ActionHandler

    public function RequestAction($Ident, $Value)
    {
        if (is_bool($Value) === false)
        {
            trigger_error('Wrong Datatype for ' . $Ident, E_USER_NOTICE);
            return false;
        }
        return $this->WriteBoolean($Ident, (bool) $Value);
    }

################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    public function RequestState()
    {
        if (!$this->HasActiveParent())
        {
            trigger_error('Instance has no active Parent Instance!', E_USER_NOTICE);
            return false;
        }
        return $this->RequestPinState();
    }

    public function ReadConfig()
    {
        if (!$this->HasActiveParent())
        {
            trigger_error('Instance has no active Parent Instance!', E_USER_NOTICE);
            return false;
        }
        return $this->ReadPinConfig();
    }

    public function WriteBoolean(string $Pin, bool $Value)
    {
        try
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
            $CMDData = new TXB_CMD_Data($Pin, chr($ValueStr));
            $ResultCMDData = $this->Send($CMDData);
            if (is_null($ResultCMDData))
                return false;
            if ($ResultCMDData->ATCommand <> $CMDData->ATCommand)
                throw new Exception('Wrong Command received.');
            if ($this->ReadPropertyBoolean('EmulateStatus'))
                SetValueBoolean($VarID, $Value);
//            if ($ResultCMDData->Data <> $CMDData->Data)
//                throw new Exception('Error on write Data.');
            return true;
        }
        catch (Exception $ex)
        {
            trigger_error($ex, E_USER_NOTICE);
            return false;
        }
    }

    public function WriteParameter(string $Parameter, string $Value)
    {
        try
        {
            if ($Value == "")
                throw new Exception('Value is empty!');
            if (!in_array($Parameter, $this->AT_WriteCommand))
                throw new Exception('Unknown Parameter: ' . $Parameter);
            $CMDData = new TXB_CMD_Data($Parameter, $Value);
            $ResultCMDData = $this->Send($CMDData);
            if (is_null($ResultCMDData))
                return false;
            if ($ResultCMDData->ATCommand <> $CMDData->ATCommand)
                throw new Exception('Wrong Command received.');
            if ($ResultCMDData->Data <> $CMDData->Data)
                throw new Exception('Error on write Data.');
            return true;
        }
        catch (Exception $ex)
        {
            trigger_error($ex, E_USER_NOTICE);
            return false;
        }
    }

    public function ReadParameter(string $Parameter)
    {
        try
        {
            if (!in_array($Parameter, $this->AT_ReadCommand))
            {
                throw new Exception('Unknown Parameter: ' . $Parameter);
            }
            $CMDData = new TXB_CMD_Data($Parameter, '');
            $ResultCMDData = $this->Send($CMDData);
            if (is_null($ResultCMDData))
                return false;
            if ($ResultCMDData->ATCommand <> $CMDData->ATCommand)
                throw new Exception('Wrong Command received.');
            return $ResultCMDData->Data;
        }
        catch (Exception $ex)
        {
            trigger_error($ex, E_USER_NOTICE);
            return false;
        }
    }

################## Datapoints

    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);

        $APIData = new TXB_API_Data($Data);
        $this->SendDebug('Receive', $APIData, 1);
        switch ($APIData->APICommand)
        {
            case TXB_API_Commands::AT_Command_Responde:
            case TXB_API_Commands::Remote_AT_Command_Responde:
                // bail out... answer to late
                break;
            case TXB_API_Commands::Transmit_Status:
                // hey data why are you here ???  Not for me... bail out
                break;
            case TXB_API_Commands::Node_Identification_Indicator:
                // additional data ?!
                break;
            case TXB_API_Commands::IO_Data_Sample_Rx:
                // gotcha
                return $this->DecodeIOSample($APIData->Data);
        }
    }

    private function DecodePinConfig(TXB_CMD_Data $CMDData)
    {
        if ($CMDData->Status <> TXB_AT_Command_Status::OK)
        {
            $this->SendDebug('Command Status Error', TXB_AT_Command_Status::ToString($CMDData->Status), 0);
            trigger_error(TXB_AT_Command_Status::ToString($CMDData->Status), E_USER_NOTICE);
            return false;
        }


        switch ($CMDData->ATCommand)
        {
            case TXB_AT_Commands::AT_D0:
            case TXB_AT_Commands::AT_D1:
            case TXB_AT_Commands::AT_D2:
            case TXB_AT_Commands::AT_D3:
            case TXB_AT_Commands::AT_D4:
            case TXB_AT_Commands::AT_D5:
            case TXB_AT_Commands::AT_D6:
            case TXB_AT_Commands::AT_D7:
            case TXB_AT_Commands::AT_P0:
            case TXB_AT_Commands::AT_P1:
            case TXB_AT_Commands::AT_P2:
                // Neuen Wert darstellen und Variable anlegen und Schaltbar machen wenn Value 4 oder 5 sonst nicht schaltbar
                if (strlen($CMDData->Data) <> 1)
                {
                    $this->SendDebug('Wrong size for data:', $CMDData, 0);
                    trigger_error('Wrong size for data.', E_USER_NOTICE);
                    return false;
                }
                switch (ord($CMDData->Data))
                {
                    case 0:
                    case 1:
                        $VarID = @$this->GetIDForIdent($CMDData->ATCommand);
                        if ($VarID > 0)
                        {
                            $this->DisableAction($CMDData->ATCommand);
                            IPS_SetVariableCustomProfile($VarID, '');
                        }
                        break;
                    case 2:

                        $VarID = $this->RegisterVariableInteger('A' . $CMDData->ATCommand, 'A' . $CMDData->ATCommand);
//                        if ($VarID > 0)
//                        {
                        $this->DisableAction('A' . $CMDData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '');
//                        }
                        break;
                    case 3:
                        $VarID = $this->RegisterVariableBoolean($CMDData->ATCommand, $CMDData->ATCommand);
                        $this->DisableAction($CMDData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '');
                        break;
                    case 4:
                        $VarID = $this->RegisterVariableBoolean($CMDData->ATCommand, $CMDData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '~Switch');
                        $this->EnableAction($CMDData->ATCommand);
                        SetValueBoolean($VarID, false);
                        break;
                    case 5:
                        $VarID = $this->RegisterVariableBoolean($CMDData->ATCommand, $CMDData->ATCommand);
                        IPS_SetVariableCustomProfile($VarID, '~Switch');
                        $this->EnableAction($CMDData->ATCommand);
                        SetValueBoolean($VarID, true);
                        break;
                }
                break;
            case TXB_AT_Commands::AT_IS:
                return $this->DecodeIOSample( chr(01) . $CMDData->Data);
        }
        return true;
    }

    private function DecodeIOSample($Data)
    {
        $ActiveDPins = unpack("n", substr($Data, 2, 2))[1];
        $ActiveAPins = ord($Data[4]);
        if ($ActiveDPins <> 0)
        {
            $PinValue = unpack("n", substr($Data, 5, 2))[1];
            foreach ($this->DPin_Name as $Index => $Pin_Name)
            {
                if ($Pin_Name == '')
                    continue;
                $Bit = pow(2, $Index);
                if (($ActiveDPins & $Bit) == $Bit)
                {
                    $VarID = @$this->GetIDForIdent($Pin_Name);
                    if ($VarID === false)
                        $VarID = $this->RegisterVariableBoolean($Pin_Name, $Pin_Name);
                    SetValueBoolean($VarID, (($PinValue & $Bit) == $Bit));
                }
            }
        }
        if ($ActiveAPins <> 0)
        {
            $i = 0;
            foreach ($this->APin_Name as $Index => $Pin_Name)
            {
                if ($Pin_Name == "")
                    continue;;
                $Bit = pow(2, $Index);
                if (($ActiveAPins & $Bit) == $Bit)
                {
                    $PinAValue = 0;
                    $PinAValue = unpack("n", substr($Data, 7 + ($i * 2), 2))[1];
                    $PinAValue = $PinAValue * 1.171875;

                    if ($Pin_Name == 'VSS')
                    {
                        $VarID = @$this->GetIDForIdent($Pin_Name);
                        if ($VarID === false)
                            $VarID = $this->RegisterVariableFloat('VSS', 'VSS', '~Volt');
                        SetValueFloat($VarID, $PinAValue / 1000);
                    }
                    else
                    {
                        $VarID = @$this->GetIDForIdent($Pin_Name);
                        if ($VarID === false)
                            $VarID = $this->RegisterVariableInteger($Pin_Name, $Pin_Name);
                        SetValueInteger($VarID, $PinAValue);
                    }
                    $i++;
                }
            }
        }
        return true;
    }

    private function ReadPinConfig()
    {
        foreach ($this->DPin_Name as $Pin)
        {
            if ($Pin == '')
                continue;
            $CMDData = new TXB_CMD_Data($Pin, '');
            $ResultCMDData = $this->Send($CMDData);
            if (is_null($ResultCMDData))
                return false;
            if ($this->DecodePinConfig($ResultCMDData) === false)
                return false;
        }
    }

//------------------------------------------------------------------------------
    private function RequestPinState()
    {
        $CMDData = new TXB_CMD_Data(TXB_AT_Commands::AT_IS, '');
        $ResultCMDData = $this->Send($CMDData);
        if (is_null($ResultCMDData))
            return false;
        return $this->DecodePinConfig($ResultCMDData);
    }

    /**
     * 
     * @param TXB_CMD_Data $CMDData
     * @return TXB_CMD_Data
     * @throws Exception
     */
    private function Send(TXB_CMD_Data $CMDData)
    {
        try
        {

            if (!$this->HasActiveParent())
                throw new Exception("Instance has no active Parent.");
            $this->SendDebug('Send', $CMDData, 0);
            $APIData = new TXB_API_Data($CMDData);
            $this->SendDebug('Send', $APIData, 0);
            $JSONString = $APIData->ToJSONString('{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}');
            $anwser = $this->SendDataToParent($JSONString);
            if ($anwser === false)
            {
                $this->SendDebug('Response', 'No valid answer', 0);
                return NULL;
            }
            $result = unserialize($anwser);
            $this->SendDebug('Response', $result, 0);
            if (($result->APICommand != TXB_API_Commands::AT_Command_Responde) and ( $result->APICommand != TXB_API_Commands::Remote_AT_Command_Responde))
                throw new Exception('Wrong APIFrame in Result');
            $ResultCMDData = new TXB_CMD_Data($result->Data);
            $this->SendDebug('Response', $ResultCMDData, 0);
            if ($ResultCMDData->Status == TXB_AT_Command_Status::OK)
                return $ResultCMDData;
            throw new Exception('Error on Transmit:' . TXB_AT_Command_Status::ToString($ResultCMDData->Status));
        }
        catch (Exception $exc)
        {
            trigger_error($exc->getMessage(), E_USER_NOTICE);
            return NULL;
        }
    }

################## DUMMYS / WOARKAROUNDS - protected
}

?>