<?
echo "<html>";
echo "<head><title>Waiting and Walkouts</title>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";
echo "</head>";

echo "<html><head><title>Waiting and Walkouts</title><head>";
echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";

include_once "util.php";
include "navbar.php";
connect("$database");

/* This shows patients that are triaged and waiting for a room
It is the same as the "Lobby" tab on the navigation bar.
The function "show_lobby" is located in util.php
*/

show_lobby("IND", "Lobby", $database);


echo "<p> &nbsp <p>";

//$now = get_mysql_now();

// Shows list of patients needing retriage

if(!$order){$order = "LAST_TRIAGE";}

$triage_interval = 30;

$triage_interval = get_triage_interval();

$sql = "SELECT lobby.MRN, 
              pt_status.PATIENT_LAST_NAME,
	      pt_status.PATIENT_FIRST_NAME,
	      UNIX_TIMESTAMP(now()) AS NOWW,
	      UNIX_TIMESTAMP(last_call_time) AS TIME_STAMP_LAST_CALL,
	      lobby.last_call_time AS LAST_CALL_TIME,
	      DATE_FORMAT(lobby.last_call_time, '%I:%i %p') AS LAST_CALL_TIME,
	      UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(last_call_time) AS TIME_SINCE_TRIAGED
        FROM lobby LEFT JOIN pt_status
	ON lobby.MRN = pt_status.MRN
       ";

//echo "SQL <P>$sql<p>";

$r = mysql_query($sql) or die("ERROR getting retriage list: $sql <p>");
$s = mysql_query($sql) or die("ERROR getting retriage list: $sql <p>");

// check if we need to do a retriage list at all
while($w = mysql_fetch_array($r)){

//   echo "$w[PATIENT_LAST_NAME]: $w[TIME_SINCE_TRIAGED] > $triage_interval <br>";
   if($w[TIME_SINCE_TRIAGED] > $triage_interval) {$retriage = "1"; break;}
}

if($retriage){
                                           
   echo "  <center>
           <table width='90%' border='5' cellpadding='3' cellspacing='3'>
           <caption><b> Patients who need to be Re-Triaged</caption>
        ";
   echo "<tr bgcolor='55FFFF'>
            <td><b>Name</td>
            <td><b>Last triage time</td>
            <td><b>Time since last triage</td>
	    <td><b>Re-triage</td>
         </tr>
        ";
   while($row = mysql_fetch_array($s, MYSQL_ASSOC)){
      //echo "$row[PATIENT_LAST_NAME]: TIME SINCE TRIAGED => $row[TIME_SINCE_TRIAGED] <br>";

      if($row[TIME_SINCE_TRIAGED] > $triage_interval){
     
         $time_since_last_triage = secs_to_stime($row[TIME_SINCE_TRIAGED]);
         echo "<tr bgcolor='FFFFFF'>";
         echo "<td>$row[PATIENT_LAST_NAME], $row[PATIENT_FIRST_NAME] </td>";
         echo "<td>$row[LAST_CALL_TIME] </td>"; 
         echo "<td> $time_since_last_triage </td>";  
     ?> 
    <td> <a href='#' onClick='window.open("/<?echo $doc_root;?>/edit_triage_info.php?mode=form&<?echo "MRN=$row[MRN]"; ?>","Triage_Info","width=500,height=800,location=0,menubar=0,scrollbars=1,status=0,resizable=1")'>
<center><img src='<?echo $image_dir;?>/edit.jpg'></a></center>
           </td>
     <?
        echo "</tr>";  
     }
  }
echo  "</table>";
}

?>
</body>
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</HEAD>
</html>


