<?

if (@constant('IPS_BASE') == null) //Nur wenn Konstanten noch nicht bekannt sind.
{
// --- BASE MESSAGE
    define('IPS_BASE', 10000);                             //Base Message
    define('IPS_KERNELSHUTDOWN', IPS_BASE + 1);            //Pre Shutdown Message, Runlevel UNINIT Follows
    define('IPS_KERNELSTARTED', IPS_BASE + 2);             //Post Ready Message
// --- KERNEL
    define('IPS_KERNELMESSAGE', IPS_BASE + 100);           //Kernel Message
    define('KR_CREATE', IPS_KERNELMESSAGE + 1);            //Kernel is beeing created
    define('KR_INIT', IPS_KERNELMESSAGE + 2);              //Kernel Components are beeing initialised, Modules loaded, Settings read
    define('KR_READY', IPS_KERNELMESSAGE + 3);             //Kernel is ready and running
    define('KR_UNINIT', IPS_KERNELMESSAGE + 4);            //Got Shutdown Message, unloading all stuff
    define('KR_SHUTDOWN', IPS_KERNELMESSAGE + 5);          //Uninit Complete, Destroying Kernel Inteface
// --- KERNEL LOGMESSAGE
    define('IPS_LOGMESSAGE', IPS_BASE + 200);              //Logmessage Message
    define('KL_MESSAGE', IPS_LOGMESSAGE + 1);              //Normal Message                      | FG: Black | BG: White  | STLYE : NONE
    define('KL_SUCCESS', IPS_LOGMESSAGE + 2);              //Success Message                     | FG: Black | BG: Green  | STYLE : NONE
    define('KL_NOTIFY', IPS_LOGMESSAGE + 3);               //Notiy about Changes                 | FG: Black | BG: Blue   | STLYE : NONE
    define('KL_WARNING', IPS_LOGMESSAGE + 4);              //Warnings                            | FG: Black | BG: Yellow | STLYE : NONE
    define('KL_ERROR', IPS_LOGMESSAGE + 5);                //Error Message                       | FG: Black | BG: Red    | STLYE : BOLD
    define('KL_DEBUG', IPS_LOGMESSAGE + 6);                //Debug Informations + Script Results | FG: Grey  | BG: White  | STLYE : NONE
    define('KL_CUSTOM', IPS_LOGMESSAGE + 7);               //User Message                        | FG: Black | BG: White  | STLYE : NONE
// --- MODULE LOADER
    define('IPS_MODULEMESSAGE', IPS_BASE + 300);           //ModuleLoader Message
    define('ML_LOAD', IPS_MODULEMESSAGE + 1);              //Module loaded
    define('ML_UNLOAD', IPS_MODULEMESSAGE + 2);            //Module unloaded
// --- OBJECT MANAGER
    define('IPS_OBJECTMESSAGE', IPS_BASE + 400);
    define('OM_REGISTER', IPS_OBJECTMESSAGE + 1);          //Object was registered
    define('OM_UNREGISTER', IPS_OBJECTMESSAGE + 2);        //Object was unregistered
    define('OM_CHANGEPARENT', IPS_OBJECTMESSAGE + 3);      //Parent was Changed
    define('OM_CHANGENAME', IPS_OBJECTMESSAGE + 4);        //Name was Changed
    define('OM_CHANGEINFO', IPS_OBJECTMESSAGE + 5);        //Info was Changed
    define('OM_CHANGETYPE', IPS_OBJECTMESSAGE + 6);        //Type was Changed
    define('OM_CHANGESUMMARY', IPS_OBJECTMESSAGE + 7);     //Summary was Changed
    define('OM_CHANGEPOSITION', IPS_OBJECTMESSAGE + 8);    //Position was Changed
    define('OM_CHANGEREADONLY', IPS_OBJECTMESSAGE + 9);    //ReadOnly was Changed
    define('OM_CHANGEHIDDEN', IPS_OBJECTMESSAGE + 10);     //Hidden was Changed
    define('OM_CHANGEICON', IPS_OBJECTMESSAGE + 11);       //Icon was Changed
    define('OM_CHILDADDED', IPS_OBJECTMESSAGE + 12);       //Child for Object was added
    define('OM_CHILDREMOVED', IPS_OBJECTMESSAGE + 13);     //Child for Object was removed
    define('OM_CHANGEIDENT', IPS_OBJECTMESSAGE + 14);      //Ident was Changed
// --- INSTANCE MANAGER
    define('IPS_INSTANCEMESSAGE', IPS_BASE + 500);         //Instance Manager Message
    define('IM_CREATE', IPS_INSTANCEMESSAGE + 1);          //Instance created
    define('IM_DELETE', IPS_INSTANCEMESSAGE + 2);          //Instance deleted
    define('IM_CONNECT', IPS_INSTANCEMESSAGE + 3);         //Instance connectged
    define('IM_DISCONNECT', IPS_INSTANCEMESSAGE + 4);      //Instance disconncted
    define('IM_CHANGESTATUS', IPS_INSTANCEMESSAGE + 5);    //Status was Changed
    define('IM_CHANGESETTINGS', IPS_INSTANCEMESSAGE + 6);  //Settings were Changed
    define('IM_CHANGESEARCH', IPS_INSTANCEMESSAGE + 7);    //Searching was started/stopped
    define('IM_SEARCHUPDATE', IPS_INSTANCEMESSAGE + 8);    //Searching found new results
    define('IM_SEARCHPROGRESS', IPS_INSTANCEMESSAGE + 9);  //Searching progress in %
    define('IM_SEARCHCOMPLETE', IPS_INSTANCEMESSAGE + 10); //Searching is complete
// --- VARIABLE MANAGER
    define('IPS_VARIABLEMESSAGE', IPS_BASE + 600);              //Variable Manager Message
    define('VM_CREATE', IPS_VARIABLEMESSAGE + 1);               //Variable Created
    define('VM_DELETE', IPS_VARIABLEMESSAGE + 2);               //Variable Deleted
    define('VM_UPDATE', IPS_VARIABLEMESSAGE + 3);               //On Variable Update
    define('VM_CHANGEPROFILENAME', IPS_VARIABLEMESSAGE + 4);    //On Profile Name Change
    define('VM_CHANGEPROFILEACTION', IPS_VARIABLEMESSAGE + 5);  //On Profile Action Change
// --- SCRIPT MANAGER
    define('IPS_SCRIPTMESSAGE', IPS_BASE + 700);           //Script Manager Message
    define('SM_CREATE', IPS_SCRIPTMESSAGE + 1);            //On Script Create
    define('SM_DELETE', IPS_SCRIPTMESSAGE + 2);            //On Script Delete
    define('SM_CHANGEFILE', IPS_SCRIPTMESSAGE + 3);        //On Script File changed
    define('SM_BROKEN', IPS_SCRIPTMESSAGE + 4);            //Script Broken Status changed
// --- EVENT MANAGER
    define('IPS_EVENTMESSAGE', IPS_BASE + 800);             //Event Scripter Message
    define('EM_CREATE', IPS_EVENTMESSAGE + 1);             //On Event Create
    define('EM_DELETE', IPS_EVENTMESSAGE + 2);             //On Event Delete
    define('EM_UPDATE', IPS_EVENTMESSAGE + 3);
    define('EM_CHANGEACTIVE', IPS_EVENTMESSAGE + 4);
    define('EM_CHANGELIMIT', IPS_EVENTMESSAGE + 5);
    define('EM_CHANGESCRIPT', IPS_EVENTMESSAGE + 6);
    define('EM_CHANGETRIGGER', IPS_EVENTMESSAGE + 7);
    define('EM_CHANGETRIGGERVALUE', IPS_EVENTMESSAGE + 8);
    define('EM_CHANGETRIGGEREXECUTION', IPS_EVENTMESSAGE + 9);
    define('EM_CHANGECYCLIC', IPS_EVENTMESSAGE + 10);
    define('EM_CHANGECYCLICDATEFROM', IPS_EVENTMESSAGE + 11);
    define('EM_CHANGECYCLICDATETO', IPS_EVENTMESSAGE + 12);
    define('EM_CHANGECYCLICTIMEFROM', IPS_EVENTMESSAGE + 13);
    define('EM_CHANGECYCLICTIMETO', IPS_EVENTMESSAGE + 14);
// --- MEDIA MANAGER
    define('IPS_MEDIAMESSAGE', IPS_BASE + 900);           //Media Manager Message
    define('MM_CREATE', IPS_MEDIAMESSAGE + 1);             //On Media Create
    define('MM_DELETE', IPS_MEDIAMESSAGE + 2);             //On Media Delete
    define('MM_CHANGEFILE', IPS_MEDIAMESSAGE + 3);         //On Media File changed
    define('MM_AVAILABLE', IPS_MEDIAMESSAGE + 4);          //Media Available Status changed
    define('MM_UPDATE', IPS_MEDIAMESSAGE + 5);
// --- LINK MANAGER
    define('IPS_LINKMESSAGE', IPS_BASE + 1000);           //Link Manager Message
    define('LM_CREATE', IPS_LINKMESSAGE + 1);             //On Link Create
    define('LM_DELETE', IPS_LINKMESSAGE + 2);             //On Link Delete
    define('LM_CHANGETARGET', IPS_LINKMESSAGE + 3);       //On Link TargetID change
// --- DATA HANDLER
    define('IPS_DATAMESSAGE', IPS_BASE + 1100);             //Data Handler Message
    define('DM_CONNECT', IPS_DATAMESSAGE + 1);             //On Instance Connect
    define('DM_DISCONNECT', IPS_DATAMESSAGE + 2);          //On Instance Disconnect
// --- SCRIPT ENGINE
    define('IPS_ENGINEMESSAGE', IPS_BASE + 1200);           //Script Engine Message
    define('SE_UPDATE', IPS_ENGINEMESSAGE + 1);             //On Library Refresh
    define('SE_EXECUTE', IPS_ENGINEMESSAGE + 2);            //On Script Finished execution
    define('SE_RUNNING', IPS_ENGINEMESSAGE + 3);            //On Script Started execution
// --- PROFILE POOL
    define('IPS_PROFILEMESSAGE', IPS_BASE + 1300);
    define('PM_CREATE', IPS_PROFILEMESSAGE + 1);
    define('PM_DELETE', IPS_PROFILEMESSAGE + 2);
    define('PM_CHANGETEXT', IPS_PROFILEMESSAGE + 3);
    define('PM_CHANGEVALUES', IPS_PROFILEMESSAGE + 4);
    define('PM_CHANGEDIGITS', IPS_PROFILEMESSAGE + 5);
    define('PM_CHANGEICON', IPS_PROFILEMESSAGE + 6);
    define('PM_ASSOCIATIONADDED', IPS_PROFILEMESSAGE + 7);
    define('PM_ASSOCIATIONREMOVED', IPS_PROFILEMESSAGE + 8);
    define('PM_ASSOCIATIONCHANGED', IPS_PROFILEMESSAGE + 9);
// --- TIMER POOL
    define('IPS_TIMERMESSAGE', IPS_BASE + 1400);            //Timer Pool Message
    define('TM_REGISTER', IPS_TIMERMESSAGE + 1);
    define('TM_UNREGISTER', IPS_TIMERMESSAGE + 2);
    define('TM_SETINTERVAL', IPS_TIMERMESSAGE + 3);
    define('TM_UPDATE', IPS_TIMERMESSAGE + 4);
    define('TM_RUNNING', IPS_TIMERMESSAGE + 5);
// --- STATUS CODES
    define('IS_SBASE', 100);
    define('IS_CREATING', IS_SBASE + 1); //module is being created
    define('IS_ACTIVE', IS_SBASE + 2); //module created and running
    define('IS_DELETING', IS_SBASE + 3); //module us being deleted
    define('IS_INACTIVE', IS_SBASE + 4); //module is not beeing used
// --- ERROR CODES
    define('IS_EBASE', 200);          //default errorcode
    define('IS_NOTCREATED', IS_EBASE + 1); //instance could not be created
// --- Search Handling
    define('FOUND_UNKNOWN', 0);     //Undefined value
    define('FOUND_NEW', 1);         //Device is new and not configured yet
    define('FOUND_OLD', 2);         //Device is already configues (InstanceID should be set)
    define('FOUND_CURRENT', 3);     //Device is already configues (InstanceID is from the current/searching Instance)
    define('FOUND_UNSUPPORTED', 4); //Device is not supported by Module

    define('vtBoolean', 0);
    define('vtInteger', 1);
    define('vtFloat', 2);
    define('vtString', 3);
}

//  API Datentypen
class TXB_API_Command extends stdClass
{

    const XB_API_AT_Command = 0x08;
    const XB_API_Transmit_Request = 0x10;
    const XB_API_Remote_AT_Command = 0x17;
    const XB_API_AT_Command_Responde = 0x88;
    const XB_API_Modem_Status = 0x8a;
    const XB_API_Transmit_Status = 0x8b;
    const XB_API_Receive_Paket = 0x90;
    const XB_API_IO_Data_Sample_Rx = 0x92;
    const XB_API_Node_Identification_Indicator = 0x95;
    const XB_API_Remote_AT_Command_Responde = 0x97;

}

// AT Commandos
class TXB_AT_Command extends stdClass
{

//    const XB_AT_ND = 0x4E44; // 'ND';
//    const XB_AT_D0 = 0x4430; //'DO';
//    const XB_AT_D1 = 0x4431; //'D1';
//    const XB_AT_D2 = 0x4432; //'D2';
//    const XB_AT_D3 = 0x4433; //'D3';
//    const XB_AT_D4 = 0x4434; //'D4';
//    const XB_AT_D5 = 0x4435; //'D5';
//    const XB_AT_D6 = 0x4436; //'D6';
//    const XB_AT_D7 = 0x4437; //'D7';
//    const XB_AT_P0 = 0x5030; //'P0';
//    const XB_AT_P1 = 0x5031; //'P1';
//    const XB_AT_P2 = 0x5032; //'P2'
//    const XB_AT_IS = 0x4953; // 'IS'
//    const XB_AT_DN = 0x444E;
//    const XB_AT_ID = 0x4944;
//    const XB_AT_SC = 0x5343;
//    const XB_AT_SD = 0x5344;
//    const XB_AT_ZS = 0x5A53;
//    const XB_AT_NJ = 0x4E4A;
//    const XB_AT_JN = 0x4A4E;
//    const XB_AT_OP = 0x4F50;
//    const XB_AT_OI = 0x4F49;
//    const XB_AT_CH = 0x4348;
//    const XB_AT_NC = 0x4E43;
//    const XB_AT_SH = 0x5348;
//    const XB_AT_SL = 0x534C;
//    const XB_AT_MY = 0x4D59;
//    const XB_AT_MP = 0x4D50;
//    const XB_AT_DH = 0x4448;
//    const XB_AT_DL = 0x444C;
//    const XB_AT_NI = 0x4E49;
//    const XB_AT_NH = 0x4E48;
//    const XB_AT_BH = 0x4248;
//    const XB_AT_AR = 0x4152;
//    const XB_AT_DD = 0x4444;
//    const XB_AT_NT = 0x4E54;
//    const XB_AT_NO = 0x4E4F;
//    const XB_AT_NP = 0x4E50;
//    const XB_AT_CR = 0x4352;
//    const XB_AT_SE = 0x5345;
//    const XB_AT_DE = 0x4445;
//    const XB_AT_CI = 0x4349;
//    const XB_AT_PL = 0x504C;
//    const XB_AT_PM = 0x504D;
//    const XB_AT_PP = 0x5050;
//    const XB_AT_EE = 0x4545;
//    const XB_AT_EO = 0x454F;
//    const XB_AT_KY = 0x4B59;
//    const XB_AT_NK = 0x4E4B;
//    const XB_AT_BD = 0x4244;
//    const XB_AT_NB = 0x4E42;
//    const XB_AT_SB = 0x5342;
//    const XB_AT_RO = 0x524F;
//    const XB_AT_AP = 0x4150;
//    const XB_AT_AO = 0x414F;
//    const XB_AT_CT = 0x4354;
//    const XB_AT_GT = 0x4754;
//    const XB_AT_CC = 0x4343;
//    const XB_AT_SM = 0x534D;
//    const XB_AT_ST = 0x5354;
//    const XB_AT_SP = 0x5350;
//    const XB_AT_SN = 0x534E;
//    const XB_AT_SO = 0x534F;
//    const XB_AT_PO = 0x504F;
//    const XB_AT_PR = 0x5052;
//    const XB_AT_LT = 0x4C54;
//    const XB_AT_RP = 0x5250;
//    const XB_AT_DO = 0x444F;
//    const XB_AT_IR = 0x4952;
//    const XB_AT_IC = 0x4943;
//    const XB_AT_VV = 0x562B;
//    const XB_AT_VR = 0x5652;
//    const XB_AT_HV = 0x4856;
//    const XB_AT_AI = 0x4149;
//    const XB_AT_DB = 0x4442; //'DB'
//    const XB_AT_VSS = 0x2556; //"%V"

    /*
      private $Command;

      public function __construct($XB_AT_Command)
      {
      $this->Command = $XB_AT_Command;
      }

      public function GetString()
      {
      return pack("n", $this->Command);
      }
     */
    const XB_AT_ND = 'ND';
    const XB_AT_D0 = 'D0';
    const XB_AT_D1 = 'D1';
    const XB_AT_D2 = 'D2';
    const XB_AT_D3 = 'D3';
    const XB_AT_D4 = 'D4';
    const XB_AT_D5 = 'D5';
    const XB_AT_D6 = 'D6';
    const XB_AT_D7 = 'D7';
    const XB_AT_P0 = 'P0';
    const XB_AT_P1 = 'P1';
    const XB_AT_P2 = 'P2';
    const XB_AT_IS = 'IS';
    const XB_AT_DN = 'DN';
    const XB_AT_ID = 'ID';
    const XB_AT_SC = 'SC';
    const XB_AT_SD = 'SD';
    const XB_AT_ZS = 'ZS';
    const XB_AT_NJ = 'NJ';
    const XB_AT_JN = 'JN';
    const XB_AT_OP = 'OP';
    const XB_AT_OI = 'OI';
    const XB_AT_CH = 'CH';
    const XB_AT_NC = 'NC';
    const XB_AT_SH = 'SH';
    const XB_AT_SL = 'SL';
    const XB_AT_MY = 'MY';
    const XB_AT_MP = 'MP';
    const XB_AT_DH = 'DH';
    const XB_AT_DL = 'DL';
    const XB_AT_NI = 'NI';
    const XB_AT_NH = 'NH';
    const XB_AT_BH = 'BH';
    const XB_AT_AR = 'AR';
    const XB_AT_DD = 'DD';
    const XB_AT_NT = 'NT';
    const XB_AT_NO = 'NO';
    const XB_AT_NP = 'NP';
    const XB_AT_CR = 'CR';
    const XB_AT_SE = 'SE';
    const XB_AT_DE = 'DE';
    const XB_AT_CI = 'CI';
    const XB_AT_PL = 'PL';
    const XB_AT_PM = 'PM';
    const XB_AT_PP = 'PP';
    const XB_AT_EE = 'EE';
    const XB_AT_EO = 'EO';
    const XB_AT_KY = 'KY';
    const XB_AT_NK = 'NK';
    const XB_AT_BD = 'BD';
    const XB_AT_NB = 'NB';
    const XB_AT_SB = 'SB';
    const XB_AT_RO = 'RO';
    const XB_AT_AP = 'AP';
    const XB_AT_AO = 'AO';
    const XB_AT_CT = 'CT';
    const XB_AT_GT = 'GT';
    const XB_AT_CC = 'CC';
    const XB_AT_SM = 'SM';
    const XB_AT_ST = 'ST';
    const XB_AT_SP = 'SP';
    const XB_AT_SN = 'SN';
    const XB_AT_SO = 'SO';
    const XB_AT_PO = 'PO';
    const XB_AT_PR = 'PR';
    const XB_AT_LT = 'LT';
    const XB_AT_RP = 'RP';
    const XB_AT_DO = 'DO';
    const XB_AT_IR = 'IR';
    const XB_AT_IC = 'IC';
    const XB_AT_VV = 'V+';
    const XB_AT_VR = 'VR';
    const XB_AT_HV = 'HV';
    const XB_AT_AI = 'AI';
    const XB_AT_DB = 'DB';
    const XB_AT_VSS = '%V';

}

// AT Command Status Response
class TXB_Command_Status extends stdClass
{

    const XB_Command_OK = 0;
    const XB_Command_Error = 1;
    const XB_Command_Invalid_Command = 2;
    const XB_Command_Invalid_Parameter = 3;
    const XB_Command_Tx_Failure = 4;

}

// AT Command Record
class TXB_Command_Data extends stdClass
{

    public $ATCommand;
    public $Status;
    public $Data;
    public $FrameID;

    public function GetDataFromJSONObject($Data)
    {
        $this->ATCommand = utf8_decode($Data->ATCommand);
        $this->Status = utf8_decode($Data->Status);
        $this->Data = utf8_decode($Data->Data);
        $this->FrameID = utf8_decode($Data->FrameID);
    }

    public function ToJSONString($GUID)
    {
        $SendData = new stdClass;
        $SendData->DataID = $GUID;
        $SendData->ATCommand = utf8_encode($this->ATCommand);
        $SendData->Status = utf8_encode($this->Status);
        $SendData->Data = utf8_encode($this->Data);
        $SendData->FrameID = utf8_encode($this->FrameID);
        return json_encode($SendData);
    }

}

// Trasmit Status Response
class TXB_Transmit_Status extends stdClass
{

    const XB_Transmit_OK = 0x00;
    const XB_Transmit_ACK_Fail = 0x01;
    const XB_Transmit_CCA_Fail = 0x02;
    const XB_Transmit_Invalid_Endpoint = 0x15;
    const XB_Transmit_Network_ACK_Fail = 0x21;
    const XB_Transmit_Not_Joined_to_Network = 0x22;
    const XB_Transmit_Self_addressed = 0x23;
    const XB_Transmit_Address_Not_Found = 0x24;
    const XB_Transmit_Route_Not_Found = 0x25;
    const XB_Transmit_Broadcast_Fail = 0x26;
    const XB_Transmit_Invalid_binding_table_index = 0x2B;
    const XB_Transmit_Resource_error = 0x2C;
    const XB_Transmit_broadcast_with_APS = 0x2D;
    const XB_Transmit_unicast_with_APS = 0x2E;
    const XB_Transmit_Resource_error_2 = 0x32;
    const XB_Transmit_Data_payload_too_large = 0x74;
    const XB_Transmit_Indirect_message_unrequested = 0x75;

    static function ToString(int $Code)
    {
        switch ($Code)
        {
            case static::XB_Transmit_ACK_Fail:
                return 'Transmit_ACK_Fail';
            case static::XB_Transmit_CCA_Fail:
                return 'Transmit_CCA_Fail';
            case static:: XB_Transmit_Invalid_Endpoint:
                return 'Transmit_Invalid_Endpoint';
            case static:: XB_Transmit_Network_ACK_Fail:
                return 'Transmit_Network_ACK_Fail';
            case static:: XB_Transmit_Not_Joined_to_Network:
                return 'Transmit_Not_Joined_to_Network';
            case static:: XB_Transmit_Self_addressed:
                return 'Transmit_Self_addressed';
            case static:: XB_Transmit_Address_Not_Found:
                return 'Transmit_Address_Not_Found';
            case static:: XB_Transmit_Route_Not_Found:
                return 'Transmit_Route_Not_Found';
            case static:: XB_Transmit_Broadcast_Fail:
                return 'Transmit_Broadcast_Fail';
            case static:: XB_Transmit_Invalid_binding_table_index:
                return 'Transmit_Invalid_binding_table_index';
            case static:: XB_Transmit_Resource_error:
                return 'Transmit_Resource_error';
            case static:: XB_Transmit_broadcast_with_APS:
                return 'Transmit_broadcast_with_APS';
            case static:: XB_Transmit_unicast_with_APS:
                return 'Transmit_unicast_with_APS';
            case static:: XB_Transmit_Resource_error_2:
                return 'Transmit_Resource_error_2';
            case static:: XB_Transmit_Data_payload_too_large:
                return 'Transmit_Data_payload_too_large';
            case static:: XB_Transmit_Indirect_message_unrequested:
                return 'Transmit_Indirect_message_unrequested';
        }
    }

}

// Receive Status Response
class TXB_Receive_Status extends stdClass
{

    const XB_Receive_Packet_Acknowledged = 0x01;
    const XB_Receive_Packet_was_a_broadcast_packet = 0x02;
    const XB_Receive_Packet_encrypted_with_APS_encryption = 0x20;
    const XB_Receive_Packet_was_sent_from_an_end_device = 0x40;

}

// API Frame Record
/**
 * @property mixed $APICommand
 * @property string $NodeName
 * @property string $Data
 * @property Byte $FrameID
 */
class TXB_API_Data extends stdClass
{

    public $APICommand;
    public $NodeName;
    public $Data;
    public $FrameID;

//  TxStatus   : TXB_Transmit_Status;
//  RxStatus   : TXB_Receive_Status;
    public function GetDataFromJSONObject($Data)
    {
        $this->APICommand = utf8_decode($Data->APICommand);
        $this->NodeName = utf8_decode($Data->NodeName);
        $this->Data = utf8_decode($Data->Data);
        $this->FrameID = utf8_decode($Data->FrameID);
    }

    public function ToJSONString($GUID)
    {
        $SendData = new stdClass;
        $SendData->DataID = $GUID;
        $SendData->APICommand = utf8_encode($this->APICommand);
        $SendData->NodeName = utf8_encode($this->NodeName);
        $SendData->Data = utf8_encode($this->Data);
        $SendData->FrameID = utf8_encode($this->FrameID);
        return json_encode($SendData);
    }

}

class TXB_API_IO_Sample extends stdClass
{

    public $Status;
    public $Sample;

    public function GetDataFromJSONObject($Data)
    {
        $this->Status = utf8_decode($Data->Status);
        $this->Sample = utf8_decode($Data->Sample);
    }

    public function ToJSONString($GUID)
    {
        $SendData = new stdClass;
        $SendData->DataID = $GUID;
        $SendData->Status = utf8_encode($this->Status);
        $SendData->Sample = utf8_encode($this->Sample);
        return json_encode($SendData);
    }

}

// I/O Pin BitMask
class TXB_Pin_Mask extends stdClass
{

    const XB_PIN_D00 = 0;
    const XB_PIN_D01 = 1;
    const XB_PIN_D02 = 2;
    const XB_PIN_D03 = 3;
    const XB_PIN_D04 = 4;
    const XB_PIN_D05 = 5;
    const XB_PIN_D06 = 6;
    const XB_PIN_D07 = 7;
    const XB_PIN_D10 = 10;
    const XB_PIN_D11 = 11;
    const XB_PIN_D12 = 12;

}

//NA | NA | NA | CD/DIO 12 |
//PWM/DI O11 | RSSI/DI O10 | NA | NA |
//CTS/DI O7 | RTS/DI O6 | ASSOC DIO5 | DIO4 |
//AD3/DI O3 | AD2/DI O2 | AD1/DI O1 | AD0/DI O0
class TXB_Modem_Status extends stdClass
{

    const XB_Modem_Hardware_reset = 0;
    const XB_Modem_Watchdog_timer_reset = 1;
    const XB_Modem_Joined_network = 2;
    const XB_Modem_Disassociated = 3;
    const XB_Modem_Coordinator_started = 6;
    const XB_Modem_Network_security_key_was_updated = 7;

}

class TXB_Node extends stdClass
{

    public $NodeAddr64;
    public $NodeAddr16;
    public $NodeName;

    public function utf8_encode()
    {
        $this->NodeAddr16 = utf8_encode($this->NodeAddr16);
        $this->NodeAddr64 = utf8_encode($this->NodeAddr64);
        $this->NodeName = utf8_encode($this->NodeName);
    }

    public function utf8_decode()
    {
        $this->NodeAddr16 = utf8_decode($this->NodeAddr16);
        $this->NodeAddr64 = utf8_decode($this->NodeAddr64);
        $this->NodeName = utf8_decode($this->NodeName);
    }

}

class TXB_NodeFromGeneric extends TXB_Node
{

    public function __construct($object)
    {
        $this->NodeAddr16 = $object->NodeAddr16;
        $this->NodeAddr64 = $object->NodeAddr64;
        $this->NodeName = $object->NodeName;
    }

}

?>