<?
class XBZBDevice extends IPSModule {

    public function Create()
    {
        parent::Create();
        $this->RequireParent("{B92E4FAA-1754-4FDC-8F7F-957C65A7ABB8}");
        $this->RegisterPropertyBoolean("EmulateStatus", false);
        $this->RegisterPropertyInteger("Interval", 0);

    }
    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();
        $this->RegisterTimer('RequestPinState', $this->ReadPropertyInteger('Interval'), 'XBee_RequestState($_IPS[\'TARGET\']);');

    }

################## PRIVATE     

################## ActionHandler

    public function ActionHandler($StatusVariableIdent, $Value) {
    }

################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    public function RequestState() {
    }

################## Datapoints
    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);
        if ($Data->DataID == '{A245A1A6-2618-47B2-AF49-0EDCAB93CCD0}')
        { // Daten dekodieren
            
        }
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

    protected function LogMessage($data, $cata) {
        
    }

    protected function SetSummary($data) {
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