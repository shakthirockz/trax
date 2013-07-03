<?
include 'util.php';
include "config.php";

$full_name = make_full_name($row[PATIENT_FIRST_NAME], $row[PATIENT_LAST_NAME]);

//foreach($_GET as $k => $v){echo "$k => $v <br> ";}

$er_select = $_GET[er_select];
$order = $_GET[order];

echo "<html>";
echo "<head><title>Patients in $er_select.</title>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";
echo "</head>";

echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'
        onLoad='parent.bedStatusFrame.location = \"show_available_rooms.php?er_select=$er_select\"'>";

include "navbar.php";

connect($database);

if ($order == "") {$order = "ER_ROOM_NUMBER";}
if ($er_select == "") {$er_select= 'All';}

if ($_GET[lobby] == "true") {show_lobby($order, $er_select, $database);}
else {show_er_area($order, $er_select, $image_dir, $database);}

// #####  END MAIN   ####

function show_er_area ($order, $er_select, $image_dir, $database)
{
   global $doc_root, $image_dir;

   if($er_select == "All") {
     $sql = "SELECT * from pt_status LEFT JOIN triage_data
                      ON pt_status.MRN = triage_data.MRN
                      WHERE CURRENT_STATUS != 'Discharged' AND
                            CURRENT_STATUS != 'SENT TO ROOM'
                      ORDER BY $order" ;
   }
   else {
      $sql = "SELECT * from pt_status LEFT JOIN triage_data
                      ON pt_status.MRN = triage_data.MRN
                      WHERE ER_TYPE = '$er_select'
                      ORDER BY $order" ;
   }
 // echo "$sql <p>";
   $result = mysql_query( $sql );
   $num_pts = mysql_num_rows($result);

   make_er_area_header($num_pts, $er_select);

   while ($row = mysql_fetch_array ($result))
   {
      // A useful link to the lab order form
       $lref = "<a href='/$doc_root/lab_order_form.php?MRN=$row[MRN]&er_select=$er_select'>";

      // A useful link to the patient form
      $pref = "<a href='/$doc_root/patient.php?MRN=$row[MRN]&er_select=$er_select'>";

      $full_name = make_full_name($row[PATIENT_FIRST_NAME], $row[PATIENT_LAST_NAME]);

      $comment = $row[COMMENT];
      echo "<tr bgcolor=FFFFFF>";

      echo "<td>$pref$full_name</a></td> ";
      echo "<td> <a href='/$doc_root/patient.php?MRN=$row[MRN]&er_select=$er_select'>$row[ER_ROOM_NUMBER]     </td> ";
      echo "<td> $row[complaint]                 </td> ";

      $total_time = get_time_from_now($row[REG_TIME], $database);
      echo  "<td> $total_time </td> ";

      if (ereg("DISPO", $row[CURRENT_STATUS])) {
         echo  "<td> <b>$pref<font color='red'>$row[CURRENT_STATUS]</b>";
      }
      else if (ereg("ADMIT", $row[CURRENT_STATUS]))
      {
         echo  "<td> $pref<font color='FF66AA'> <b>$row[CURRENT_STATUS]</b></font> ";
      }
      else if (ereg("READY", $row[CURRENT_STATUS]))
      {
         echo  "<td> $pref<font color='green'> <b>READY</b> </font>";
      }
      else if (ereg("SEEN", $row[CURRENT_STATUS])) 
      {
         echo  "<td> $pref<font color='00EE00'> <b>SEEN</b> </font>";
      }
      else{echo  "<td> $pref$row[CURRENT_STATUS]";
      }

      if ($row[PMD]) {echo "<br><b><font color='black'> $row[PMD] </font></b>";}
      echo "</td>";

      switch ($row[LAB_STATUS]) 
      {
       case ordered:
         echo "<td> $lref<img src='$image_dir/green.jpg'></a> </td>";
         break;
   
       case pending:
         echo "<td>$lref<img src='$image_dir/yellow.jpg'></a> </td>";  
         break;
   
      case complete:
         echo "<td>$lref<img src='$image_dir/red.jpg'></a> </td>";
         break;
   
      default:
         echo "<td>$lref<img src='$image_dir/white.jpg'></a> </td>";
         break;
     }
     switch ($row[xray_STATUS]) 
     {
     case ordered:
        echo "<td>$lref<img src='$image_dir/green.jpg'></a> </td>";
        break;
   
     case pending:
        echo "<td>$lref<img src='$image_dir/yellow.jpg'></a> </td>";
        break;
   
      case complete:
        echo "<td>$lref<img src='$image_dir/red.jpg'></a> </td>";
        break;
   
      default:
         echo "<td>$lref<img src='$image_dir/white.jpg'></a>  </td>";
         break;
     }

      switch ($row[EKG_STATUS]) {
      case ordered:
         echo "<td>$lref<img src='$image_dir/green.jpg'></a> </td>";
         break;
   
      case pending:
         echo "<td>$lref<img src='$image_dir/yellow.jpg'> </a> </td>";
         break;
   
      case complete:
         echo "<td>$lref<img src='$image_dir/red.jpg'> </a> </td>";
         break;
   
       default:
         echo "<td>$lref<img src='$image_dir/white.jpg'></a>  </td>";
         break;
      }

      switch ($row[CT_STATUS]) 
      {
       case ordered:
         echo "<td>$lref<img src='$image_dir/green.jpg'></a> </td>";
         break;
   
       case pending:
         echo "<td>$lref<img src='$image_dir/yellow.jpg'></a> </td>";
         break;
   
       case complete:
         echo "<td>$lref<img src='$image_dir/red.jpg'></a> </td>";
         break;
   
       default:
         echo "<td>$lref<img src='$image_dir/white.jpg'></a>  </td>";
         break;
      }

      switch ($row[SONO_STATUS]) 
      {
       case ordered:
         echo "<td>$lref<img src='$image_dir/green.jpg'></a> </td>";
         break;
   
       case pending:
         echo "<td>$lref<img src='$image_dir/yellow.jpg'></a> </td>";
         break;
   
       case complete:
          echo "<td>$lref<img src='$image_dir/red.jpg'></a> </td>";
          break;
   
        default:
          echo "<td>$lref<img src='$image_dir/white.jpg'></a> </td>";
          break;
      }
      echo "<td>$pref $row[ER_MD]</td>"; 
      
      /*
      $RN = get_RN($row[ER_ROOM_NUMBER]);
      echo "<td>$pref $RN</td>"; 
      echo "</tr>" ;
      */

      $new_msg = check_if_new_msg($row[MRN]);  
 
      $msg_str = "MRN=$row[MRN]&er_select=$er_select";
  
        
      $winOpenString = "onClick='window.open(\"message.php?$msg_str\ \;\", \"Message\", \"width=200,height=200,toolbar=no,directories=no,menubar=no,status=no,left=100,top=100\")'";

      if(is_array($new_msg)) { 
         if ($new_msg[target] == 'MD') {echo   "<td><img src='$image_dir/red_msg_sm.gif' $winOpenString>   </td>";}
	 if ($new_msg[target] == 'RN') {echo   "<td><img src='$image_dir/green_msg_sm.gif' height='35' width='37' $winOpenString> </td>";}
	 if ($new_msg[target] == 'SECY') {echo "<td><img src='$image_dir/yellow_msg_sm.gif' height='35' width='37' $winOpenString> </td>";}
      }
      else { echo "<td> &nbsp </td>";}
         echo "</tr>";
      }
}

function make_er_area_header ($num_pts, $er_select)
{
   $PHP_SELF = $_SERVER['PHP_SELF'];
   echo "<center><TABLE BORDER=5 CELLSPACING=2 CELLPADDING=2 WIDTH='98%'>"; 
   
   $er_name = $er_select;
   if ($er_name == 'All'){$er_name = "Entire ER";}
   $er_name = str_replace("_", " ", $er_name);

   echo "<CAPTION> <h3> Total of $num_pts Patient";  $s = ($num_pts == "1") ? " " :  "s "; echo "$s";
   echo "in $er_name.</h3> </CAPTION>";
   echo "<p>";
   echo "<tr bgcolor=88FFFF>";
   echo     "<td> <b> <a href='$PHP_SELF?order=PATIENT_LAST_NAME&er_select=$er_select'>Name </b></a></td> ";
   echo     "<td> <b> <a href='$PHP_SELF?order=ER_ROOM_NUMBER&er_select=$er_select'>Rm#    </b> </a> </td> ";
   echo     "<td> <b> <a href='$PHP_SELF?order=complaint&er_select=$er_select'>Complaint           </b> </a> </td> ";
   echo     "<td> <b> <a href='$PHP_SELF?order=REC_NUM&er_select=$er_select'>Time           </b> </a> </td> "; 
   echo     "<td nowrap> <b> <a href='$PHP_SELF?order=CURRENT_STATUS&er_select=$er_select'>Status  </b> </a>    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </td> ";
   echo     "<td> <b> LAB    </b></td>"; 
   echo     "<td nowrap> <b> X-ray  </b></td>"; 
   echo     "<td> <b> EKG    </b></td>"; 
   echo     "<td> <b> CT     </b></td>"; 
   echo     "<td> <b> US   </b></td>"; 
   echo     "<td> <b> <a href='$PHP_SELF?order=ER_MD&er_select=$er_select'> MD </b> </a>  </td>"; 
   //echo     "<td> <b> <a href='$PHP_SELF?order=ER_MD&er_select=$er_select'> RN </b> </a>  </td>";
   echo     "<td> <b>MSG</b></td>";
   echo "</tr>";
}  
  
function get_RN($er_room_num)
{
   $r = mysql_query("SELECT NAME FROM RN_assign_current WHERE ROOM='$er_room_num' ");
   while($row = mysql_fetch_array($r)){$RN = $row[NAME];}
   if(preg_match("/(.*)_/", $RN, $regs)) {$RN = $regs[1];}
   return $RN;
}


function check_if_new_msg($MRN) {

  $msg_array = array();
  connect($database);
  $sql = "SELECT * FROM message WHERE MRN = '$MRN' ";  
  
  $r = mysql_query($sql);
  while($row = mysql_fetch_array($r)){

     if($row[STATUS] == "new"){
        $msg_array[status]  = $row[STATUS];
        $msg_array[message] = $row[MESSAGE];
	$msg_array[target]  = $row[TARGET];
      }
  }
  if($msg_array[status] == 'new') {return $msg_array;}
  else {return "no_new_messages";}
  
}

// These are currently for reference only - should be removed from final version
// A useful link to the lab order form
$lref = "<a href='/$doc_root/lab_order_form.php?MRN=$row[MRN]&er_select=$er_select'>";
// A useful link to the patient form
$pref = "<a href='/$doc_root/patient.php?MRN=$row[MRN]&er_select=$er_select'>";
// link to triage note
$tref = "<a href='/$doc_root/show_triage_info.php?MRN=$row[MRN]'>";     
?>

</body>
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</HEAD>
</html>
