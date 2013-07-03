<?

//foreach($_GET as $k => $v) {echo "$k => $v <br>";}
$MRN = $_GET[MRN];
$er_select = $_GET[er_select];
include "util.php";
include "config.php";

echo "<html><head><title>Update Patient Status</title></head>";
echo "<body bgcolor='cyan' text='black' link='blue' vlink='green'>";

include "navbar.php";

connect($database);

$sql = "SELECT * FROM pt_status LEFT JOIN triage_data 
             ON pt_status.MRN = triage_data.MRN 
             WHERE pt_status.MRN = '$MRN'  ";
       
//echo "SQL => $sql <p>";

$result= mysql_query($sql);
       
while ($row = mysql_fetch_array ($result)) {

$MRN= $row[MRN];
$ER_MD = $row[ER_MD];
$complaint = $row[complaint];
$ER_room_number = $current_room = $row[ER_ROOM_NUMBER];

$doc_link = "https://mdportal.wjmc.org";

$full_name = make_full_name($row[first_name], $row[last_name]);

echo "<p><center>";
echo "<TABLE BORDER=1 CELLSPACING=2 CELLPADDING=2 WIDTH='70%'>"; 

echo "<CAPTION> <h3> Update status for patient <a href='$doc_link'>$full_name</a>.</h3> </CAPTION>";

echo "       <td> <b>Complaint</b>        </td>";
echo "       <td> ";   
echo "          <form action='update_pt_status.php?'> ";
echo "                 <input type='hidden' name='update_to' value='change_complaint'> ";
echo "                 <input type='text' name='new_complaint' value='$complaint'> ";
echo "                 <input type='hidden' name='MRN' value='$MRN'> ";
echo "    <input type='hidden' name='er_select' value='$er_select'>";
echo "                 <input type='submit' value='change'> ";
echo "	      </td> ";
echo "</tr>";
echo "                    </form> ";

$er_type = get_er_type($row[ER_ROOM_NUMBER]);

echo     "<td> <b>ER Assignment</b>       </td> <td> $er_type                           </td> </tr>";
echo     "<td> <b>Room Number</b>         </td> <td> $row[ER_ROOM_NUMBER]               </td> </tr>";

echo "</TABLE><p>";

echo "<TABLE border='1' width='70%'>";
echo "<tr>";
echo "<td> <b>Assign Room:  </b></td><td>"; 

$current_room = $row[ER_ROOM_NUMBER];
$avail_rooms = get_avail_rooms();
$all_rooms = get_all_rooms();

echo " <form action='/$doc_root/update_pt_status.php'>";
echo "    <input type='hidden' name='MRN' value='$row[MRN]'>";
echo "	  <input type='hidden' name='update_to' value='assign_ER_room'>";
echo "    <input type='hidden' name='er_select' value=$er_select>";

echo "<SELECT name='ER_room_num'>";

foreach ($avail_rooms as $room)
{
       if($room == $current_room) {
          echo "<option value='$room'selected> $room </option>";
          }
          else{
               echo "<option value='$room'> $room </option>";
               }
}
echo "</SELECT>";
echo "</td><td>";
echo     "<input type='submit' value='Assign'>";
echo "</form>";
echo "</td>";
echo "</tr>";
}

$sql = "SELECT LAST_NAME FROM MD_names order by LAST_NAME";
$result = mysql_query($sql);

echo "<tr>";
echo "<td>";
echo "<B>  ER Doctor:</b>"; 
echo "</td>";

echo "<td>";
echo "<form action='/$doc_root/update_pt_status.php'>";
echo     "<input type='hidden' name='update_to' value='assign_ER_MD'>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo "    <input type='hidden' name='er_select' value='$er_select'>";

echo "<SELECT name='er_md'>";

if ($ER_MD ==""){
     echo "<OPTION value='none' selected> NOT ASSIGNED </OPTION>";
     }
     else{    
          echo "<option value='$ER_MD' selected> $ER_MD </option>";
          }
     
while ($row= mysql_fetch_array($result)) {
          echo "  <option value='$row[LAST_NAME]'> $row[LAST_NAME] </option>";
   }
echo "</SELECT>";

echo "</td>";
echo "<td>";
echo     "<input type='submit' value='Assign'>";
echo "</td>";
echo "</tr>";
echo "</form>";
//echo "</table>";
echo "<p>";

echo "<tr> ";
echo "<td> ";
echo "<p><B>Patient Status / Location</b>";
//echo "<table border='1' width= 70%>";
echo "</td> ";

echo "<td> ";
echo "<form action='/$doc_root/update_pt_status.php'> ";
echo "<SELECT name='update_to'> ";

$new_status = array("READY", "DISPO", "ADMIT", "DISCHARGE", "Private MD", "SEND_TO_ROOM", "ERASE", "WALKOUT", "Waiting for room");
$result = mysql_query("SELECT * FROM status_flags");
while($row=mysql_fetch_array($result)){array_push($new_status, $row[NAME]);}

sort($new_status);
foreach($new_status as $ns)
{
   //$fs = str_replace("_", " ", $ns);                  // Displays SEND_TO_ROOM with spaces
   if($ns == "DISPO") {echo "<option name='$ns' selected>$ns</option>";}
   else {echo "<option name='$ns'>$ns</option>";}
}

echo " </SELECT> ";
echo "</td> ";
echo     "<input type='hidden' name='ER_room_number' value='$caller'>";
echo     "<input type='hidden' name='caller' value='$caller'>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo "    <input type='hidden' name='er_select' value='$er_select'>";
echo "<td> ";
echo     "<input type='submit' value='Update'> ";
echo "</form> ";
echo "</td> ";
echo "</table>";

echo "<p>";

echo "<table border='1' width= 70%>";
echo "<tr> ";
echo "<td> ";
echo "<p><B> Set admit room number:</b>";
echo "</td> ";
echo "<td> ";

$r = mysql_query("SELECT ADMIT_ROOM_NUM FROM pt_status WHERE MRN = '$MRN' ");
while($row = mysql_fetch_array($r)){$admit_room_num = $row[ADMIT_ROOM_NUM];}

echo "<form action='/$doc_root/update_pt_status.php?update_to=set_room_number'>";
echo     "Room: <input type='text' name='admit_room_num' value='$admit_room_num' size='6'>";
echo     "<input type='hidden' name='update_to' value='set_room_number'>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo "    <input type='hidden' name='er_select' value='$er_select'>";
echo     "</td<td><input type='submit' value='Enter'>";
echo "</form>";
echo "</td> ";
echo "</tr> ";
echo "</table>";

echo "<table border='1' width= 70%>";
echo "<tr> ";
echo "<td> ";
echo "<p><B> Set private doctor name:</b>";
echo "</td> ";
echo "<td> ";
echo "<form action='/$doc_root/update_pt_status.php?update_to=set_pmd_name'>";
echo     "Name: <input type='text' name='pmd_name' size='20'>";
echo     "<input type='hidden' name='update_to' value='set_pmd_name'>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo     "<input type='submit' value='Enter'>";
echo "</form>";
echo "</td> ";
echo "</tr> </table><p>";

echo "<table border='1' width= 70%><tr><td>";
echo "<form action='/$doc_root/lab_order_form.php'>";
echo     "<b>Lab Orders:</b></td><td>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo     "<input type='hidden' name='er_select' value='$er_select'>";
echo     "<center><input type='submit' value='Orders'><center>";
echo "</form></td></tr>";
echo "</table>";


echo "ER_SELECT => $_GET[er_select] <p>";

echo "<p><table border='1' width='70%'><tr><td>";
echo "<form action='/$doc_root/message.php'>";
echo     "<b>Message:</b></td><td>";
echo     "<input type='hidden' name='MRN' value='$MRN'>";
echo     "<input type='hidden' name='er_select' value='$_GET[er_select]'>";
echo     "<input type='hidden' name='mode' value='insert_new_message'>";
echo	 "<table width='70%'><tr><td>";
echo     "<textarea name='message' cols='30' rows='4' WRAP='SOFT'></textarea>";
echo     "</td></tr><tr>
          <td>MD <input type='radio' name='target' value='MD'>
              RN <input type='radio' name='target' value='RN'>
              Secretary <input type='radio' name='target' value='SECY'>
          </td></tr>
        </table>
       ";
echo     "<input type='submit' value='Post Message'>";
echo "</form></td></tr>";
echo "</table></center>";

?>


