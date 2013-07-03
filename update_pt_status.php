
<?php
include_once "util.php";
include "config.php";

print "<html><head><title>Patient Triaged</title>";

foreach($_GET as $k => $v){echo "$k => $v <br> ";}

$update_to = $_GET[update_to];
$MRN =$_GET[MRN];
$er_select = $_GET[er_select];

connect($database);

//see how we are called

switch ($update_to) {

     case assign_ER_room :
           assign_ER_room($MRN, $_GET[ER_room_num]);
           break;
           
     case assign_ER_MD :
           assign_ER_MD($MRN, $_GET[er_md]);
           break;
           
     case change_complaint :
           change_complaint($MRN, $_GET[new_complaint]);
           break;  
           
     case set_room_number :
           assign_admit_room_number ($MRN, $_GET[admit_room_num]);
           break;
           
     case set_pmd_name :
           set_pmd_name ($MRN, $_GET[pmd_name]);
           break;

     default :
           change_status($MRN, $_GET[update_to]);
           break;
                                                             
} 

function assign_ER_room($MRN, $ER_room_num) 
{   
  //Check if coming from lobby or if just changing rooms
  $r = mysql_query("SELECT ER_ROOM_NUMBER, CURRENT_STATUS FROM pt_status WHERE MRN = '$MRN' ");
  while($row = mysql_fetch_array($r)){
     $current_er_room_number = $row[ER_ROOM_NUMBER];
     $current_status = $row[CURRENT_STATUS];
  }
 /* 
  echo "CUR RM NUM: $current_er_room_number <p>";
  echo "CUR STATUS: $current_status <p>";
  exit();
*/
  // do this if virgin pt from lobby
  if(($current_er_room_number == "LOBBY"|| $current_er_room_number == "Lobby") && $current_status == "Waiting for room")
  {

     echo "VIGIN PT <P>";

    $s = STAMP( $MRN, "TO_ER_FROM_LOBBY", "no");
    $er_type = get_er_type($ER_room_num);

    $sql = "UPDATE pt_status SET ER_ROOM_NUMBER = '$ER_room_num',
                               ER_TYPE = '$er_type',
                               LOBBY_TYPE = 'NULL',
                               CURRENT_STATUS = 'READY'
                           WHERE MRN = $MRN";
   
    $result = mysql_query($sql) 
       or die ("Invalid sql query setting ER ROOM number, type and updating pt_status for new patient."); 
       
  $sql = "UPDATE ROOMS SET STATUS = 'OCCUPIED', MRN = $MRN WHERE NAME = '$ER_room_num'";
  $result = mysql_query($sql) 
       or die ("Invalid sql query setting room status for ROOM_NAME: $room_name.");
  
      // remove them from the lobby table
   $sql = "DELETE FROM lobby WHERE MRN = '$MRN' ";
   $result = mysql_query($sql) 
       or die ("Error deleting pt from lobby table: $sql <p>");
  
  }
  else{ // do this for existing patient who is just changing rooms. 
    $er_type = get_er_type($ER_room_num);
  
    $sql = "UPDATE pt_status SET ER_ROOM_NUMBER = '$ER_room_num',
                               ER_TYPE = '$er_type'
                           WHERE MRN = $MRN";
     
    $result = mysql_query($sql) 
       or die ("Invalid sql query setting ER ROOM number while moving old patient. <p> $sql"); 
   
   // Clear old room
   $r = mysql_query ("UPDATE ROOMS SET STATUS = '', MRN = '' WHERE MRN = '$MRN'");
   
   // Enter info in new room
   $sql = "UPDATE ROOMS SET STATUS = 'OCCUPIED', MRN = $MRN WHERE NAME = '$ER_room_num'";
   $result = mysql_query($sql) 
       or die ("Invalid sql query setting room status for ROOM_NAME: $room_name."); 
   } 
}

function assign_ER_MD($MRN, $ER_MD) 
{
  $s = STAMP( $MRN, "MD_ASSIGNED_$ER_MD", "no");
  mysql_query("UPDATE pt_status SET ER_MD = '$ER_MD',
                           CURRENT_STATUS ='Eval in progress'
                                WHERE MRN = '$MRN'" )
     or die ("Error setting ER_MD : $ER_MD");
}

function change_complaint($MRN, $new_complaint) 
{   
   mysql_query("UPDATE triage_data SET complaint = '$new_complaint' WHERE MRN='$MRN' ")
     or die ("Error changing complaint: $new_complaint : $MRN ");
}

function change_status($MRN, $new_status)
{
   echo "MRN => $MRN <br> NEW_STATUS => $new_status<p>";
   $new_status = str_replace(" ", "_", $new_status); 
   echo "MRN => $MRN <br> Fixed NEW_STATUS => $new_status<p>";
   
   switch ($new_status) {
   
      case DISPO:
         STAMP ( $MRN, "DISPO_READY", "yes" );
         mysql_query("UPDATE pt_status SET CURRENT_STATUS = 'DISPO' WHERE MRN = '$MRN' ");
         break;
         
     case ADMIT:
         STAMP ( $MRN, "ADMIT_ORDERS_COMPLETE", "no" );
         mysql_query("UPDATE pt_status SET CURRENT_STATUS = 'ADMIT' WHERE MRN = '$MRN' ");
         break;
         
     case DISCHARGE:
         STAMP ( $MRN, "DISCHARGED", "yes" );
         mysql_query("UPDATE pt_status SET CURRENT_STATUS = 'Discharged',
                                           ER_TYPE = '',
                                           ER_ROOM_NUMBER = '',
                                           LAB_STATUS = '',
                                           CT_STATUS = '',
                                           SONO_STATUS = '',
                                           xray_STATUS = '',
                                           EKG_STATUS = '',
                                           ER_MD = ''
                     WHERE MRN = '$MRN' ");
                     
         mysql_query("UPDATE ROOMS SET STATUS ='', MRN = '' WHERE MRN = '$MRN' ");
         break;

    case ERASE :
         mysql_query("DELETE FROM pt_status WHERE MRN = '$MRN' ");
	 mysql_query("UPDATE ROOMS SET STATUS ='', MRN='' WHERE MRN = '$MRN' ");
	 break;

     case SEND_TO_ROOM:
         $r = mysql_query("SELECT ADMIT_ROOM_NUM FROM pt_status WHERE MRN = '$MRN' ");
         while($row = mysql_fetch_array($r)) {$admit_room = $row[ADMIT_ROOM_NUM];}
         
         STAMP ( $MRN, "UP_TO_ROOM_$admit_room", "yes" );
         mysql_query("UPDATE pt_status SET CURRENT_STATUS = 'SENT TO ROOM',
                                           ER_TYPE = '',
                                           ER_ROOM_NUMBER = '',
                                           LAB_STATUS = '',
                                           CT_STATUS = '',
                                           SONO_STATUS = '',
                                           xray_STATUS = '',
                                           EKG_STATUS = '',
                                           ER_MD = ''
                         WHERE MRN = '$MRN' ");
                     
         mysql_query("UPDATE ROOMS SET STATUS ='', MRN = '' WHERE MRN = '$MRN' ");
         break;
         
     default:
         mysql_query("UPDATE pt_status SET CURRENT_STATUS = '$new_status' WHERE MRN = '$MRN' ");
         break;
     }
}

function assign_admit_room_number ($MRN, $room_num)
{
   STAMP ( $MRN, "ROOM_ASSIGNED_$room_num", "yes" );
   mysql_query("UPDATE pt_status SET ADMIT_ROOM_NUM = '$room_num',
                                     CURRENT_STATUS = 'ADMIT $room_num'
                         WHERE MRN = '$MRN' ");     
}

function set_pmd_name ($MRN, $pmd_name)
{
   mysql_query("UPDATE pt_status SET PMD = '$pmd_name' WHERE MRN = '$MRN' ");     
}

echo "ER_SELECT AT BOTTOM => $er_select<p>";

$er_select = get_er_type_from_MRN($_GET[MRN]);

echo "FIXED ER_SELECT AT BOTTOM => $er_select <p>";

echo "DOC_ROOT at bottom = > $doc_root<p>";

echo "<script language='JavaScript'>
         this.location='/$doc_root/show_ER_patients.php?er_select=$er_select';
      </script>";
  
?>

