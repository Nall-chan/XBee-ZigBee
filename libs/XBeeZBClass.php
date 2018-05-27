<?php

/**
 * @addtogroup xbeezigbee
 * @{
 *
 * @package       XBeeZigBee
 * @file          module.php
 * @author        Michael Tröger <micha@nall-chan.net>
 * @copyright     2018 Michael Tröger
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       2.3
 *
 */
if (!defined("IPS_BASE")) {
    // --- BASE MESSAGE
    define('IPS_BASE', 10000);                             //Base Message
    define('IPS_KERNELSTARTED', IPS_BASE + 1);             //Post Ready Message
    define('IPS_KERNELSHUTDOWN', IPS_BASE + 2);            //Pre Shutdown Message, Runlevel UNINIT Follows
}
if (!defined("IPS_KERNELMESSAGE")) {
    // --- KERNEL
    define('IPS_KERNELMESSAGE', IPS_BASE + 100);           //Kernel Message
    define('KR_CREATE', IPS_KERNELMESSAGE + 1);            //Kernel is beeing created
    define('KR_INIT', IPS_KERNELMESSAGE + 2);              //Kernel Components are beeing initialised, Modules loaded, Settings read
    define('KR_READY', IPS_KERNELMESSAGE + 3);             //Kernel is ready and running
    define('KR_UNINIT', IPS_KERNELMESSAGE + 4);            //Got Shutdown Message, unloading all stuff
    define('KR_SHUTDOWN', IPS_KERNELMESSAGE + 5);          //Uninit Complete, Destroying Kernel Inteface
}
if (!defined("IPS_LOGMESSAGE")) {
    // --- KERNEL LOGMESSAGE
    define('IPS_LOGMESSAGE', IPS_BASE + 200);              //Logmessage Message
    define('KL_MESSAGE', IPS_LOGMESSAGE + 1);              //Normal Message                      | FG: Black | BG: White  | STLYE : NONE
    define('KL_SUCCESS', IPS_LOGMESSAGE + 2);              //Success Message                     | FG: Black | BG: Green  | STYLE : NONE
    define('KL_NOTIFY', IPS_LOGMESSAGE + 3);               //Notiy about Changes                 | FG: Black | BG: Blue   | STLYE : NONE
    define('KL_WARNING', IPS_LOGMESSAGE + 4);              //Warnings                            | FG: Black | BG: Yellow | STLYE : NONE
    define('KL_ERROR', IPS_LOGMESSAGE + 5);                //Error Message                       | FG: Black | BG: Red    | STLYE : BOLD
    define('KL_DEBUG', IPS_LOGMESSAGE + 6);                //Debug Informations + Script Results | FG: Grey  | BG: White  | STLYE : NONE
    define('KL_CUSTOM', IPS_LOGMESSAGE + 7);               //User Message                        | FG: Black | BG: White  | STLYE : NONE
}
if (!defined("IPS_MODULEMESSAGE")) {
    // --- MODULE LOADER
    define('IPS_MODULEMESSAGE', IPS_BASE + 300);           //ModuleLoader Message
    define('ML_LOAD', IPS_MODULEMESSAGE + 1);              //Module loaded
    define('ML_UNLOAD', IPS_MODULEMESSAGE + 2);            //Module unloaded
}
if (!defined("IPS_OBJECTMESSAGE")) {
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
}
if (!defined("IPS_INSTANCEMESSAGE")) {
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
}
if (!defined("IPS_VARIABLEMESSAGE")) {
    // --- VARIABLE MANAGER
    define('IPS_VARIABLEMESSAGE', IPS_BASE + 600);              //Variable Manager Message
    define('VM_CREATE', IPS_VARIABLEMESSAGE + 1);               //Variable Created
    define('VM_DELETE', IPS_VARIABLEMESSAGE + 2);               //Variable Deleted
    define('VM_UPDATE', IPS_VARIABLEMESSAGE + 3);               //On Variable Update
    define('VM_CHANGEPROFILENAME', IPS_VARIABLEMESSAGE + 4);    //On Profile Name Change
    define('VM_CHANGEPROFILEACTION', IPS_VARIABLEMESSAGE + 5);  //On Profile Action Change
}
if (!defined("IPS_SCRIPTMESSAGE")) {
    // --- SCRIPT MANAGER
    define('IPS_SCRIPTMESSAGE', IPS_BASE + 700);           //Script Manager Message
    define('SM_CREATE', IPS_SCRIPTMESSAGE + 1);            //On Script Create
    define('SM_DELETE', IPS_SCRIPTMESSAGE + 2);            //On Script Delete
    define('SM_CHANGEFILE', IPS_SCRIPTMESSAGE + 3);        //On Script File changed
    define('SM_BROKEN', IPS_SCRIPTMESSAGE + 4);            //Script Broken Status changed
}
if (!defined("IPS_EVENTMESSAGE")) {
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
}
if (!defined("IPS_MEDIAMESSAGE")) {
    // --- MEDIA MANAGER
    define('IPS_MEDIAMESSAGE', IPS_BASE + 900);           //Media Manager Message
    define('MM_CREATE', IPS_MEDIAMESSAGE + 1);             //On Media Create
    define('MM_DELETE', IPS_MEDIAMESSAGE + 2);             //On Media Delete
    define('MM_CHANGEFILE', IPS_MEDIAMESSAGE + 3);         //On Media File changed
    define('MM_AVAILABLE', IPS_MEDIAMESSAGE + 4);          //Media Available Status changed
    define('MM_UPDATE', IPS_MEDIAMESSAGE + 5);
}
if (!defined("IPS_LINKMESSAGE")) {
    // --- LINK MANAGER
    define('IPS_LINKMESSAGE', IPS_BASE + 1000);           //Link Manager Message
    define('LM_CREATE', IPS_LINKMESSAGE + 1);             //On Link Create
    define('LM_DELETE', IPS_LINKMESSAGE + 2);             //On Link Delete
    define('LM_CHANGETARGET', IPS_LINKMESSAGE + 3);       //On Link TargetID change
}
if (!defined("IPS_FLOWMESSAGE")) {
    // --- DATA HANDLER
    define('IPS_FLOWMESSAGE', IPS_BASE + 1100);             //Data Handler Message
    define('FM_CONNECT', IPS_FLOWMESSAGE + 1);             //On Instance Connect
    define('FM_DISCONNECT', IPS_FLOWMESSAGE + 2);          //On Instance Disconnect
}
if (!defined("IPS_ENGINEMESSAGE")) {
    // --- SCRIPT ENGINE
    define('IPS_ENGINEMESSAGE', IPS_BASE + 1200);           //Script Engine Message
    define('SE_UPDATE', IPS_ENGINEMESSAGE + 1);             //On Library Refresh
    define('SE_EXECUTE', IPS_ENGINEMESSAGE + 2);            //On Script Finished execution
    define('SE_RUNNING', IPS_ENGINEMESSAGE + 3);            //On Script Started execution
}
if (!defined("IPS_PROFILEMESSAGE")) {
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
}
if (!defined("IPS_TIMERMESSAGE")) {
    // --- TIMER POOL
    define('IPS_TIMERMESSAGE', IPS_BASE + 1400);            //Timer Pool Message
    define('TM_REGISTER', IPS_TIMERMESSAGE + 1);
    define('TM_UNREGISTER', IPS_TIMERMESSAGE + 2);
    define('TM_SETINTERVAL', IPS_TIMERMESSAGE + 3);
    define('TM_UPDATE', IPS_TIMERMESSAGE + 4);
    define('TM_RUNNING', IPS_TIMERMESSAGE + 5);
}

if (!defined("IS_ACTIVE")) { //Nur wenn Konstanten noch nicht bekannt sind.
// --- STATUS CODES
    define('IS_SBASE', 100);
    define('IS_CREATING', IS_SBASE + 1); //module is being created
    define('IS_ACTIVE', IS_SBASE + 2); //module created and running
    define('IS_DELETING', IS_SBASE + 3); //module us being deleted
    define('IS_INACTIVE', IS_SBASE + 4); //module is not beeing used
// --- ERROR CODES
    define('IS_EBASE', 200);          //default errorcode
    define('IS_NOTCREATED', IS_EBASE + 1); //instance could not be created
}

if (!defined("vtBoolean")) { //Nur wenn Konstanten noch nicht bekannt sind.
    define('vtBoolean', 0);
    define('vtInteger', 1);
    define('vtFloat', 2);
    define('vtString', 3);
}

/**
 *  Alle unterstützen API Kommandos
 */
class TXB_API_Commands
{
    const AT_Command = 0x08;
    const Transmit_Request = 0x10;
    const Remote_AT_Command = 0x17;
    const AT_Command_Responde = 0x88;
    const Modem_Status = 0x8a;
    const Transmit_Status = 0x8b;
    const Receive_Paket = 0x90;
    const IO_Data_Sample_Rx = 0x92;
    const Node_Identification_Indicator = 0x95;
    const Remote_AT_Command_Responde = 0x97;

    /**
     *  Liefert den Klartext zu einem Kommando
     *
     * @param int $Code
     * @return string
     */
    public static function ToString(int $Code)
    {
        switch ($Code) {
            case self::AT_Command:
                return 'AT_Command';
            case self::Transmit_Request:
                return 'Transmit_Request';
            case self::Remote_AT_Command:
                return 'Remote_AT_Command';
            case self::AT_Command_Responde:
                return 'AT_Command_Responde';
            case self::Modem_Status:
                return 'Modem_Status';
            case self::Transmit_Status:
                return 'Transmit_Status';
            case self::Receive_Paket:
                return 'Receive_Paket';
            case self::IO_Data_Sample_Rx:
                return 'IO_Data_Sample_Rx';
            case self::Node_Identification_Indicator:
                return 'Node_Identification_Indicator';
            case self::Remote_AT_Command_Responde:
                return 'Remote_AT_Command_Responde';
            default:
                return bin2hex(chr($Code));
        }
    }
}

/**
 *  Alle bekannten Modem Stati
 */
class TXB_Modem_Status
{
    const Hardware_reset = 0;
    const Watchdog_timer_reset = 1;
    const Joined_network = 2;
    const Disassociated = 3;
    const Config_error = 4;
    const Coordinator_realignment = 5;
    const Coordinator_started = 6;
    const Network_security_key_was_updated = 7;
    const Network_woke_up = 0x0B;
    const Network_went_to_sleep = 0x0C;
    const Voltage_supply_limit_exceed = 0x0D;
    const Modem_config_changed_while_join = 0x11;
    const Stack_error = 0x80;
    const Command_without_connecting_from_AP = 0x82;
    const AP_not_found = 0x83;
    const PSK_not_configured = 0x84;
    const SSID_not_found = 0x87;
    const Failed_to_join_with_security_enabled = 0x88;
    const Invalid_channel = 0x8A;
    const Failed_to_join_AP = 0x8E;

    /**
     *  Liefert den Klartext zu einem Modem Status.
     *
     * @param int $Code
     * @return string
     */
    public static function ToString(int $Code)
    {
        switch ($Code) {
            case self::Hardware_reset:
                return 'Hardware_reset';
            case self::Watchdog_timer_reset:
                return 'Watchdog_timer_reset';
            case self::Joined_network:
                return 'Joined_network';
            case self::Disassociated:
                return 'Disassociated';
            case self::Config_error:
                return 'Config error / sync lost';
            case self::Coordinator_realignment:
                return 'Coordinator realignment';
            case self::Coordinator_started:
                return 'Coordinator_started';
            case self::Network_security_key_was_updated:
                return 'Network_security_key_was_updated';
            case self::Network_woke_up:
                return 'Network woke up';
            case self::Network_went_to_sleep:
                return 'Network went to sleep';
            case self::Voltage_supply_limit_exceed:
                return 'Voltage supply limit exceed';
            case self::Modem_config_changed_while_join:
                return 'Modem config changed while join';
            case self::Stack_error:
                return 'Stack error';
            case self::Command_without_connecting_from_AP:
                return 'Send/Join command without connecting from AP';
            case self::AP_not_found:
                return 'AP not found';
            case self::PSK_not_configured:
                return 'PSK not configured';
            case self::SSID_not_found:
                return 'SSID not found';
            case self::Failed_to_join_with_security_enabled:
                return 'Failed to join with security enabled';
            case self::Invalid_channel:
                return 'Invalid channel';
            case self::Failed_to_join_AP:
                return 'Failed to join AP';
            default:
                return bin2hex(chr($Code));
        }
    }
}

/**
 *  Alle möglichen Quittungen zu einer Datenübertragung.
 */
class TXB_Transmit_Status
{
    const OK = 0x00;
    const ACK_Fail = 0x01;
    const CCA_Fail = 0x02;
    const Invalid_Endpoint = 0x15;
    const Network_ACK_Fail = 0x21;
    const Not_Joined_to_Network = 0x22;
    const Self_addressed = 0x23;
    const Address_Not_Found = 0x24;
    const Route_Not_Found = 0x25;
    const Broadcast_Fail = 0x26;
    const Invalid_binding_table_index = 0x2B;
    const Resource_error = 0x2C;
    const broadcast_with_APS = 0x2D;
    const unicast_with_APS = 0x2E;
    const Resource_error_2 = 0x32;
    const Data_payload_too_large = 0x74;
    const Indirect_message_unrequested = 0x75;

    /**
     *  Liefert den Klartext zu einem Status.
     *
     * @param int $Code
     * @return string
     */
    public static function ToString(int $Code)
    {
        switch ($Code) {
            case self::OK:
                return 'OK';
            case self::ACK_Fail:
                return 'ACK_Fail';
            case self::CCA_Fail:
                return 'CCA_Fail';
            case self:: Invalid_Endpoint:
                return 'Invalid_Endpoint';
            case self:: Network_ACK_Fail:
                return 'Network_ACK_Fail';
            case self:: Not_Joined_to_Network:
                return 'Not_Joined_to_Network';
            case self:: Self_addressed:
                return 'Self_addressed';
            case self:: Address_Not_Found:
                return 'Address_Not_Found';
            case self:: Route_Not_Found:
                return 'Route_Not_Found';
            case self:: Broadcast_Fail:
                return 'Broadcast_Fail';
            case self:: Invalid_binding_table_index:
                return 'Invalid_binding_table_index';
            case self:: Resource_error:
                return 'Resource_error';
            case self:: broadcast_with_APS:
                return 'broadcast_with_APS';
            case self:: unicast_with_APS:
                return 'unicast_with_APS';
            case self:: Resource_error_2:
                return 'Resource_error_2';
            case self:: Data_payload_too_large:
                return 'Data_payload_too_large';
            case self:: Indirect_message_unrequested:
                return 'Indirect_message_unrequested';
            default:
                return bin2hex(chr($Code));
        }
    }
}

/**
 *  Status eines empfangenen Datenpaketes.
 */
class TXB_Receive_Status
{
    const Packet_Acknowledged = 0x01;
    const Packet_was_a_broadcast_packet = 0x02;
    const Packet_encrypted_with_APS_encryption = 0x20;
    const Packet_was_sent_from_an_end_device = 0x40;

    /**
     *  Liefert den Klartext zu einem Status.
     *
     * @param int $Code
     * @return string
     */
    public static function ToString(int $Code)
    {
        $ret = array();
        if (($Code & self::Packet_Acknowledged) == self::Packet_Acknowledged) {
            $ret[] = 'Packet_Acknowledged';
        }
        if (($Code & self::Packet_was_a_broadcast_packet) == self::Packet_was_a_broadcast_packet) {
            $ret[] = 'Packet_was_a_broadcast_packet';
        }
        if (($Code & self::Packet_encrypted_with_APS_encryption) == self::Packet_encrypted_with_APS_encryption) {
            $ret[] = 'Packet_encrypted_with_APS_encryption';
        }
        if (($Code & self::Packet_was_sent_from_an_end_device) == self::Packet_was_sent_from_an_end_device) {
            $ret[] = 'Packet_was_sent_from_an_end_device';
        }

        return implode(' + ', $ret);
    }
}

/**
 *  Alle unterstützen AT Kommandos
 */
class TXB_AT_Commands
{
    const AT_ND = 'ND';
    const AT_D0 = 'D0';
    const AT_D1 = 'D1';
    const AT_D2 = 'D2';
    const AT_D3 = 'D3';
    const AT_D4 = 'D4';
    const AT_D5 = 'D5';
    const AT_D6 = 'D6';
    const AT_D7 = 'D7';
    const AT_P0 = 'P0';
    const AT_P1 = 'P1';
    const AT_P2 = 'P2';
    const AT_IS = 'IS';
    const AT_DN = 'DN';
    const AT_ID = 'ID';
    const AT_SC = 'SC';
    const AT_SD = 'SD';
    const AT_ZS = 'ZS';
    const AT_NJ = 'NJ';
    const AT_JN = 'JN';
    const AT_OP = 'OP';
    const AT_OI = 'OI';
    const AT_CH = 'CH';
    const AT_NC = 'NC';
    const AT_SH = 'SH';
    const AT_SL = 'SL';
    const AT_MY = 'MY';
    const AT_MP = 'MP';
    const AT_DH = 'DH';
    const AT_DL = 'DL';
    const AT_NI = 'NI';
    const AT_NR = 'NR';
    const AT_NH = 'NH';
    const AT_BH = 'BH';
    const AT_AR = 'AR';
    const AT_DD = 'DD';
    const AT_NT = 'NT';
    const AT_NO = 'NO';
    const AT_NP = 'NP';
    const AT_CR = 'CR';
    const AT_SE = 'SE';
    const AT_DE = 'DE';
    const AT_CI = 'CI';
    const AT_PL = 'PL';
    const AT_PM = 'PM';
    const AT_PP = 'PP';
    const AT_EE = 'EE';
    const AT_EO = 'EO';
    const AT_KY = 'KY';
    const AT_NK = 'NK';
    const AT_BD = 'BD';
    const AT_NB = 'NB';
    const AT_SB = 'SB';
    const AT_RO = 'RO';
    const AT_AP = 'AP';
    const AT_AO = 'AO';
    const AT_CT = 'CT';
    const AT_GT = 'GT';
    const AT_CC = 'CC';
    const AT_SM = 'SM';
    const AT_ST = 'ST';
    const AT_SP = 'SP';
    const AT_SN = 'SN';
    const AT_SO = 'SO';
    const AT_PO = 'PO';
    const AT_PR = 'PR';
    const AT_LT = 'LT';
    const AT_RP = 'RP';
    const AT_DO = 'DO';
    const AT_IR = 'IR';
    const AT_IC = 'IC';
    const AT_VV = 'V+';
    const AT_VR = 'VR';
    const AT_HV = 'HV';
    const AT_AI = 'AI';
    const AT_DB = 'DB';
    const AT_VSS = '%V';
}

/**
 *  Status einer Antwort von einem AT Kommando.
 */
class TXB_AT_Command_Status
{
    const OK = 0;
    const Error = 1;
    const Invalid_Command = 2;
    const Invalid_Parameter = 3;
    const Tx_Failure = 4;

    /**
     *  Liefert den Klartext zu einem Status.
     *
     * @param int $Code
     * @return string
     */
    public static function ToString(int $Code)
    {
        switch ($Code) {
            case self::OK:
                return 'OK';
            case self::Error:
                return 'Error';
            case self::Invalid_Command:
                return 'Invalid_Command';
            case self::Invalid_Parameter:
                return 'Invalid_Parameter';
            case self::Tx_Failure:
                return 'Tx_Failure';
            default:
                return bin2hex(chr($Code));
        }
    }
}

/**
 * Enthält alle Daten eines API Paketes.
 */
class TXB_API_Data
{
    use NodeExtracter;

    /**
     * API Command des Paketes.
     * @var TXB_API_Commands
     * @access public
     */
    public $APICommand;

    /**
     * Name des Node.
     * @var string
     * @access public
     */
    public $NodeName;

    /**
     * Nutzdaten des Paketes.
     * @var string
     * @access public
     */
    public $Data;

    /**
     * FrameID des Paketes.
     * @var Byte
     * @access public
     */
    public $FrameID = null;

    /**
     * Liefert die Daten welche behalten werden müssen.
     * @access public
     */
    public function __sleep()
    {
        return array('APICommand', 'NodeName', 'Data', 'FrameID');
    }

    /**
     * Erzeugt ein API Paket aus den übergeben Daten.
     * Es können wahlweise Rohdaten vom Coordinator,
     * ein API-Kommando mit Nutzdaten,
     * ein utf8-encodiertes Objekt vom IPS Datenaustausch oder
     * zu versendendes AT Kommando (TXB_CMD_DATA) übergeben werden.
     *
     * @param object|TXB_API_Data|string $Frame
     * @param null|string $Payload
     * @return TXB_API_Data
     */
    public function __construct($Frame = null, $Payload = null)
    {
        if (is_null($Frame)) {
            return;
        }
        if (is_object($Frame)) {
            if (property_exists($Frame, 'APICommand')) {
                $this->APICommand = utf8_decode($Frame->APICommand);
                if (!is_null($Frame->NodeName)) {
                    $this->NodeName = utf8_decode($Frame->NodeName);
                }
                $this->Data = utf8_decode($Frame->Data);
                return;
            }
            if (property_exists($Frame, 'ATCommand')) {
                $this->APICommand = TXB_API_Commands::AT_Command;
                $this->Data = $Frame->ATCommand . $Frame->Data;
                return;
            }
        }
        if (!is_null($Payload)) {
            $this->APICommand = $Frame;
            $this->Data = $Payload;
            return;
        }

        $this->APICommand = ord($Frame[0]);
        $this->FrameID = 0;
        $Frame = substr($Frame, 1, -1);
        switch ($this->APICommand) {
            case TXB_API_Commands::AT_Command_Responde:
            case TXB_API_Commands::Transmit_Status:
            case TXB_API_Commands::Remote_AT_Command_Responde:
                $this->FrameID = ord($Frame[0]);
                $Frame = substr($Frame, 1);
                break;
        }
        $this->Data = $Frame;
    }

    /**
     * Liefert den Byte-String für den Versand an den Coordinator
     *
     * @param bool $Escape True wenn API2 mit maskierten Zeichen.
     * @param TXB_Node $Node Ziel Node oder NULL bei Versand an den Coordinator.
     * @return string Byte-String für den Coordinator.
     */
    public function ToFrame(bool $Escape, TXB_Node $Node = null)
    {
        $Data = chr($this->APICommand) . chr($this->FrameID);
        if (!is_null($Node)) {
            $Data .= $Node->NodeAddr64;
            $Data .= $Node->NodeAddr16;
        }
        $Data .= $this->Data;
        $len = strlen($Data);
        $frame = chr(floor($len / 256)) . chr($len % 256) . $Data;
        $check = 0;
        for ($x = 0; $x < $len; $x++) {
            $check = $check + ord($Data[$x]);
        }
        $check = $check & 0xff;
        $check = 0xff - $check;
        $frame .= chr($check);
        $escaped = array("\x7d\x5d", "\x7d\x31", "\x7d\x33", "\x7d\x5e");
        $unescaped = array("\x7d", "\x11", "\x13", "\x7e");
        if ($Escape) {
            $packet = chr(0x7e) . str_replace($unescaped, $escaped, $frame);
        } else {
            $packet = chr(0x7e) . $frame;
        }

        return $packet;
    }

    /**
     *  Erzeugt einen String für den Datenaustausch innerhalb von IPS.
     *
     * @param string $GUID Die GUID des Ziel-Interfaces innerhalb von IPS.
     * @return string Datenstring für den Datenaustausch innerhalb von IPS.
     */
    public function ToJSONString($GUID)
    {
        $SendData = new stdClass;
        $SendData->DataID = $GUID;
        $SendData->APICommand = utf8_encode($this->APICommand);
        $SendData->NodeName = utf8_encode($this->NodeName);
        $SendData->Data = utf8_encode($this->Data);
        return json_encode($SendData);
    }
}

/**
 * TXB_API_DataList ist eine Klasse welche ein Array von TXB_API_Data enthält.
 */
class TXB_API_DataList
{

    /**
     * Array mit allen Items.
     * @var array
     * @access public
     */
    public $Items = array();

    /**
     * Aktueller Frame.
     * @var array
     * @access public
     */
    public $FrameID = 1;

    /**
     * Liefert die Daten welche behalten werden müssen.
     * @access public
     */
    public function __sleep()
    {
        return array('Items', 'FrameID');
    }

    /**
     * Fügt einen Eintrag in $Items hinzu.
     * @access public
     * @return int FrameID Die FrameID in der Warteschlange.
     */
    public function Add()
    {
        $FrameID = $this->FrameID;
        $this->Items[$FrameID] = null;
        if ($this->FrameID == 255) {
            $this->FrameID = 1;
        } else {
            $this->FrameID++;
        }
        return $FrameID;
    }

    /**
     * Update für einen Eintrag in $Items.
     * @access public
     * @param TXB_API_Data $APIData Das neue Objekt.
     * @return bool True bei erfolg, False wenn FrameID nicht vorhanden.
     */
    public function Update(TXB_API_Data $APIData)
    {
        if (!array_key_exists($APIData->FrameID, $this->Items)) {
            return false;
        }
        $this->Items[$APIData->FrameID] = $APIData;
        return true;
    }

    /**
     * Löscht einen Eintrag aus $Items.
     * @access public
     * @param int $Index Der Index des zu löschenden Items.
     */
    public function Remove(int $Index)
    {
        if (array_key_exists($Index, $this->Items)) {
            unset($this->Items[$Index]);
        }
    }

    /**
     * Liefert einen bestimmten Eintrag aus den Items.
     * @access public
     * @param int $Index
     * @return TXB_API_Data APIData der Antwort.
     */
    public function Get(int $Index)
    {
        if (array_key_exists($Index, $this->Items)) {
            return $this->Items[$Index];
        }
        return false;
    }
}

/**
 * Enthält alle Daten eines AT Paketes.
 *
 */
class TXB_CMD_Data
{
    use NodeExtracter;

    /**
     * AT Command des Paketes.
     * @var TXB_AT_Commands
     * @access public
     */
    public $ATCommand;

    /**
     * Status der Antwort.
     * @var TXB_AT_Command_Status
     * @access public
     */
    public $Status;

    /**
     * Nutzdaten des Paketes.
     * @var string
     * @access public
     */
    public $Data;

    /**
     * Liefert die Daten welche behalten werden müssen.
     * @access public
     */
    public function __sleep()
    {
        return array('ATCommand', 'Status', 'Data');
    }

    /**
     * Erzeugt ein AT Paket aus den übergeben Daten.
     * Es können wahlweise die Nutzdaten eines API Paketes vom Typ TXB_API_Commands::AT_Command_Responde
     * oder ein AT Kommando mit Nutzdaten übergeben werden.
     *
     * @param string $Data
     * @param null|string $Payload
     * @return TXB_CMD_Data
     */
    public function __construct(string $Data, string $Payload = null)
    {
        if (is_null($Payload)) {
            if (is_string($Data)) {
                $this->ATCommand = substr($Data, 0, 2);
                $this->Status = ord(substr($Data, 2, 1));
                $this->Data = substr($Data, 3);
            }
        } else {
            $this->ATCommand = $Data;
            $this->Data = $Payload;
        }
    }
}

/**
 * Biete Funktionen um Adressen und Namen von Nodes aus den Nutzdaten zu extrahieren.
 */
trait NodeExtracter
{

    /**
     *  Extrahiert die MAC-Adresse des Node aus den ersten 8 Bytes von $Data.
     *
     * @access public
     * @return string 64-Bit Adresse (MAC) des Node
     */
    public function ExtractNodeAddr64()
    {
        $Addr64 = substr($this->Data, 0, 8);
        $this->Data = substr($this->Data, 8);
        return $Addr64;
    }

    /**
     *  Extrahiert die dynamisch 16-Bit Adresse des Node aus den ersten 2 Bytes von $Data.
     *
     * @access public
     * @return string 16-Bit Adresse des Node
     */
    public function ExtractNodeAddr16()
    {
        $Addr16 = substr($this->Data, 0, 2);
        $this->Data = substr($this->Data, 2);
        return $Addr16;
    }

    /**
     *  Extrahiert eine Zeichenkette bis zum ersten 0-Byte aus $Data.
     *
     * @access public
     * @return string Der extrahierte String.
     */
    public function ExtractString()
    {
        $end = strpos($this->Data, chr(0));
        if ($end === false) {
            $Value = $this->Data;
            $this->Data = '';
        } else {
            $Value = substr($this->Data, 0, $end);
            $this->Data = substr($this->Data, $end + 1);
        }
        return $Value;
    }
}

/**
 * Enthält die Daten eines Node.
 */
class TXB_Node
{

    /**
     * 64Bit Adresse des Node.
     * @var string
     * @access public
     */
    public $NodeAddr64;

    /**
     * 16Bit Adresse des Node.
     * @var string
     * @access public
     */
    public $NodeAddr16;

    /**
     * Name des Node.
     * @var string
     * @access public
     */
    public $NodeName;

    /**
     * Liefert die Daten welche behalten werden müssen.
     * @access public
     */
    public function __sleep()
    {
        return array('NodeName', 'NodeAddr16', 'NodeAddr64');
    }
}

/**
 * TXB_NodeList ist eine Klasse welche ein Array von TXB_Node enthält.
 *
 */
class TXB_NodeList
{

    /**
     * Array mit allen Items.
     * @var array
     * @access public
     */
    public $Items = array();

    /**
     * Liefert die Daten welche behalten werden müssen.
     * @access public
     */
    public function __sleep()
    {
        return array('Items');
    }

    /**
     * Update für einen Eintrag in $Items.
     * @access public
     * @param TXB_Node $Node Das neue Objekt.
     */
    public function Update(TXB_Node $Node)
    {
        $this->Items[$Node->NodeName] = $Node;
    }

    /**
     * Löscht einen Eintrag aus $Items.
     * @access public
     * @param string $NodeName Der Index des zu löschenden Items.
     */
    public function Remove(string $NodeName)
    {
        if (isset($this->Items[$NodeName])) {
            unset($this->Items[$NodeName]);
        }
    }

    /**
     * Liefert einen bestimmten Eintrag aus den Items.
     * @access public
     * @param string $NodeName
     * @return TXB_Node Node
     */
    public function GetByNodeName(string $NodeName)
    {
        if (!isset($this->Items[$NodeName])) {
            return false;
        }
        return $this->Items[$NodeName];
    }

    /** Liefert einen bestimmten Eintrag aus den Items.
     * @access public
     * @param string $NodeAddr16
     * @return TXB_Node Node
     */
    public function GetByNodeAddr16(string $NodeAddr16)
    {
        foreach ($this->Items as $Name => $Node) {
            if ($Node->NodeAddr16 == $NodeAddr16) {
                return $this->Items[$Name];
            }
        }
        return false;
    }

    /** Liefert einen bestimmten Eintrag aus den Items.
     * @access public
     * @param string $NodeAddr64
     * @return TXB_Node Node
     */
    public function GetByNodeAddr64(string $NodeAddr64)
    {
        foreach ($this->Items as $Name => $Node) {
            if ($Node->NodeAddr64 == $NodeAddr64) {
                return $this->Items[$Name];
            }
        }
        return false;
    }
}

/**
 * DebugHelper ergänzt SendDebug um die Möglichkeit Array und Objekte auszugeben.
 *
 */
trait DebugHelper
{

    /**
     * Ergänzt SendDebug um Möglichkeit Objekte und Array auszugeben.
     *
     * @access protected
     * @param string $Message Nachricht für Data.
     * @param TXB_API_Data|mixed $Data Daten für die Ausgabe.
     * @return int $Format Ausgabeformat für Strings.
     */
    protected function SendDebug($Message, $Data, $Format)
    {
        if (is_a($Data, 'TXB_API_Data')) {
            $this->SendDebug($Message . ' APICmd', TXB_API_Commands::ToString($Data->APICommand), 0);
            $this->SendDebug($Message . ' Data', $Data->Data, 1);
            if (!is_null($Data->FrameID)) {
                $this->SendDebug($Message . ' FrameID', (string) $Data->FrameID, 0);
            }
        } elseif (is_a($Data, 'TXB_CMD_Data')) {
            $this->SendDebug($Message . ' ATCmd', $Data->ATCommand, 0);
            $this->SendDebug($Message . ' Status', TXB_AT_Command_Status::ToString($Data->Status), 0);
            $this->SendDebug($Message . ' Data', $Data->Data, 1);
        } elseif (is_object($Data)) {
            foreach ($Data as $Key => $DebugData) {
                $this->SendDebug($Message . ":" . $Key, $DebugData, 1);
            }
        } elseif (is_array($Data)) {
            foreach ($Data as $Key => $DebugData) {
                $this->SendDebug($Message . ":" . $Key, $DebugData, 0);
            }
        } else {
            parent::SendDebug($Message, $Data, $Format);
        }
    }
}

/**
 * Biete Funktionen um Thread-Safe auf Objekte zuzugrifen.
 */
trait Semaphore
{

    /**
     * Versucht eine Semaphore zu setzen und wiederholt dies bei Misserfolg bis zu 100 mal.
     * @param string $ident Ein String der den Lock bezeichnet.
     * @return boolean TRUE bei Erfolg, FALSE bei Misserfolg.
     */
    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++) {
            if (IPS_SemaphoreEnter("XBZB_" . (string) $this->InstanceID . (string) $ident, 1)) {
                return true;
            } else {
                IPS_Sleep(mt_rand(1, 5));
            }
        }
        return false;
    }

    /**
     * Löscht eine Semaphore.
     * @param string $ident Ein String der den Lock bezeichnet.
     */
    private function unlock($ident)
    {
        IPS_SemaphoreLeave("XBZB_" . (string) $this->InstanceID . (string) $ident);
    }
}

/**
 * Trait mit Hilfsfunktionen für den Datenaustausch.
 */
trait InstanceStatus
{

    /**
     * Ermittelt den Parent und verwaltet die Einträge des Parent im MessageSink
     * Ermöglicht es das Statusänderungen des Parent empfangen werden können.
     *
     * @access private
     */
    protected function GetParentData()
    {
        $OldParentId = $this->Parent;
        $ParentId = @IPS_GetInstance($this->InstanceID)['ConnectionID'];
        if ($OldParentId > 0) {
            $this->UnregisterMessage($OldParentId, IM_CHANGESTATUS);
        }
        if ($ParentId > 0) {
            $this->RegisterMessage($ParentId, IM_CHANGESTATUS);
            $this->Parent = $ParentId;
        } else {
            $this->Parent = 0;
        }
    }

    /**
     * Setzt den Status dieser Instanz auf den übergebenen Status.
     * Prüft vorher noch ob sich dieser vom aktuellen Status unterscheidet.
     *
     * @access protected
     * @param int $InstanceStatus
     */
    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus']) {
            parent::SetStatus($InstanceStatus);
        }
    }

    /**
     * Prüft den Parent auf vorhandensein und Status.
     *
     * @access protected
     * @return bool True wenn Parent vorhanden und in Status 102, sonst false.
     */
    protected function HasActiveParent()
    {
        $instance = IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] > 0) {
            $parent = IPS_GetInstance($instance['ConnectionID']);
            if ($parent['InstanceStatus'] == 102) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Trait mit Hilfsfunktionen für Variablenprofile.
 */
trait Profile
{

    /**
     * Erstell und konfiguriert ein VariablenProfil für den Typ integer mit Assoziationen
     *
     * @access protected
     * @param string $Name Name des Profils.
     * @param string $Icon Name des Icon.
     * @param string $Prefix Prefix für die Darstellung.
     * @param string $Suffix Suffix für die Darstellung.
     * @param array $Associations Assoziationen der Werte als Array.
     */
    protected function RegisterProfileIntegerEx($Name, $Icon, $Prefix, $Suffix, $Associations)
    {
        if (sizeof($Associations) === 0) {
            $MinValue = 0;
            $MaxValue = 0;
        } else {
            $MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations) - 1][0];
        }

        $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, 0);

        foreach ($Associations as $Association) {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }
    }

    /**
     * Erstell und konfiguriert ein VariablenProfil für den Typ integer
     *
     * @access protected
     * @param string $Name Name des Profils.
     * @param string $Icon Name des Icon.
     * @param string $Prefix Prefix für die Darstellung.
     * @param string $Suffix Suffix für die Darstellung.
     * @param int $MinValue Minimaler Wert.
     * @param int $MaxValue Maximaler wert.
     * @param int $StepSize Schrittweite
     */
    protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize)
    {
        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, 1);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != 1) {
                throw new Exception("Variable profile type does not match for profile " . $Name, E_USER_NOTICE);
            }
        }

        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
    }

    /**
     * Löscht ein Variablenprofile, sofern es nicht außerhalb dieser Instanz noch verwendet wird.
     * @param string $Profil Name des zu löschenden Profils.
     */
    protected function UnregisterProfil(string $Profil)
    {
        if (!IPS_VariableProfileExists($Profil)) {
            return;
        }
        foreach (IPS_GetVariableList() as $VarID) {
            if (IPS_GetParent($VarID) == $this->InstanceID) {
                continue;
            }
            if (IPS_GetVariable($VarID)['VariableCustomProfile'] == $Profil) {
                return;
            }
        }
        IPS_DeleteVariableProfile($Profil);
    }
}

/** @} */
