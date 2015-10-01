<?

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
        $this->NodeAddr16 =         utf8_encode($this->NodeAddr16);
        $this->NodeAddr64 =         utf8_encode($this->NodeAddr64);
        $this->NodeName =         utf8_encode($this->NodeName);
    }
    public function utf8_decode()
    {
        $this->NodeAddr16 =         utf8_decode($this->NodeAddr16);
        $this->NodeAddr64 =         utf8_decode($this->NodeAddr64);
        $this->NodeName =         utf8_decode($this->NodeName);
    }
    
}

?>