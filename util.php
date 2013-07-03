<?
/*Utility Functions*/
include "config.php";
$timestamp = date("Y-m-d H:i:s");

 // A useful link to the lab order form
      $lref = "<a href='/$doc_root/lab_order_form.php?MRN=$row[MRN]&er_select=$er_select'>";

 // A useful link to the patient form
      $pref = "<a href='/$doc_root/patient.php?MRN=$row[MRN]&er_select=$er_select'>";

/* open a connection. */
function connect ($database)
{
  global $database;
  $sucess=mysql_connect("localhost","root","")
	or die("Could not connect to database");
	
	//echo "DATABASE ==> $database <p>";

   mysql_select_db("$database", $sucess)
	or die ("Could not find database $database");
}

//New and prefered way to get time intervwls. 
function get_time_from_now($start_time, $database) 
{
   connect($database);

   $sql = "SELECT SEC_TO_TIME(UNIX_TIMESTAMP() - UNIX_TIMESTAMP('$start_time'))";
   $result = mysql_query($sql);
   $time_from_now = mysql_fetch_array($result);
   $time_from_now = $time_from_now[0];

//remove seconds

   $no_secs = ereg_replace(":[0-9][0-9]$", "", $time_from_now);
   $time_from_now = $no_secs;
   
   return $time_from_now;
}


function check_needs_retriage($total_time)
{
   if (preg_match("/\d(\d):\d{2}/", $total_time, $regs))
   {
      if($regs[1] > 2) {return "true";}
      else{return "false";}
    }
    else {return "error"; }
 }


function get_time_interval($start_time, $end_time, $database) {

   /* open a connection. */
   $sucess=mysql_connect("localhost","root","")
	or die("Could not connect to database");

   mysql_select_db("$database", $sucess)
	or die ("Could not find database $database");

   $sql = "SELECT SEC_TO_TIME(UNIX_TIMESTAMP('$end_time') - UNIX_TIMESTAMP('$start_time'))";
   $result = mysql_query($sql);
   $time_interval = mysql_fetch_array($result);
   $time_interval = $time_interval[0];

//remove seconds

   $no_secs = ereg_replace(":[0-9][0-9]$", "", $time_interval);
   $time_interval = $no_secs;

   return $time_interval;
}

//gets list of empty rooms 
function get_avail_rooms()
{
   $database = "trax";
   connect($database);
   $avail_rooms = array();
   
   $sql = "select NAME from ROOMS where status = '' ORDER BY NAME ";
   $result = mysql_query($sql);
   
   while ($row = mysql_fetch_array($result))
   {
      array_push($avail_rooms, $row[NAME]);
   }

   return $avail_rooms;
}

//gets list of all rooms - empty or full
function get_all_rooms()
{
   $database = "trax";
   connect($database);
   $all_rooms = array();
   
   $sql = "select NAME from ROOMS order by NAME";
   $result = mysql_query($sql);
   
   while ($row = mysql_fetch_array($result))
   {
      array_push($all_rooms, $row[NAME]);
   }
   
   return $all_rooms;
}

function make_full_name($fname, $lname)
{
   $fname = ucwords(strtolower($fname));
   $lname = ucwords(strtolower($lname)); 
   $full_name =  $lname . ", " . $fname ;
   return $full_name;
}

//empties all current patients and rooms 
function purge()
{
   global $database; 
   connect($database);
   
   $sql = "DELETE FROM pt_status";
   $result = mysql_query($sql);
   
   $sql = "DELETE FROM lobby";
   $result = mysql_query($sql);
   
   $sql = "DELETE FROM triage_data";
   $result = mysql_query($sql);

   $sql = "update ROOMS SET STATUS ='', MRN=''";
   $result = mysql_query($sql);

   return(1);
}

//changes lobby type on lobby view
function toggle_lobby($mrn)
{
   //while(1) {       // loop allows for gaps in IND sequence
       connect($database);

        $r= mysql_query("SELECT LOBBY_TYPE from pt_status where MRN = '$mrn' ");

        while ($row = mysql_fetch_array($r)) {
            $lobby_type = $row[LOBBY_TYPE];
         }

        $r = mysql_query("SELECT IND from WAITING_AREAS WHERE NAME = '$lobby_type' ");
        while ($row = mysql_fetch_array($r)) {
            $ind = $row[IND];
         }

        $r = mysql_query("SELECT MAX(IND) AS MAX_IND from WAITING_AREAS  ");
        while ($row = mysql_fetch_array($r)) {
          $max_ind =  $row[MAX_IND];
        }

        $new_ind = $ind + 1;
        if ($new_ind > $max_ind) {$new_ind= 1;}

       $r = mysql_query("SELECT NAME from WAITING_AREAS WHERE IND = '$new_ind' ");
       while ($row = mysql_fetch_array($r)) {
           $new_lobby_type  = $row[NAME];
        }
      // if($row[NAME] != "") {break;}
   // }
     $r= mysql_query("UPDATE pt_status SET LOBBY_TYPE = '$new_lobby_type' where MRN = '$mrn' ");
 }

//converts seconds to hrs, mins, secs string
function secs_to_stime($secs)
{
    $hours = intval($secs/3600);
    $rem = $secs % 3600;
    
    if ($hours == '0')
    {
       $mins = intval($secs/60);
       $s = $secs % 60;   
    
       $t = "$mins";
       if ($mins == '1') {$t .= " minute";}
          else {$t .= " minutes";}
    
       $t .=  ", $s seconds.";
    }
    else
    {
       $mins = intval($rem/60);
       $s = $rem % 60;
       
       $t =  "$hours hours, $mins minutes, $s seconds";
    }
return ($t);
}

//converts secs to time formated string xx:xx:xx
function secs_to_ftime($seconds)
{
    $hours = intval($seconds/3600);
    $rem = $seconds % 3600;
       
    if ($hours == '0')
    {
       $hours = "00";
       $mins = intval($secs/60);      
       $secs = $seconds % 60;  
       $t = "$hours:$mins:$secs";
    }
    else
    {
       $mins = intval($rem/60);
       $secs = $rem % 60;
    }  
     
   if ($mins < 9) {$mins = "0" . $mins;}
   if ($secs < 9) {$s = "0" . $secs;} 
   $t =  "$hours:$mins:$secs";
   
   return ($t);
}

//Identifies ER section from MRN by querying ROOMs table
function locate_me($MRN)
{
  $table = "ROOMS";
  $sql = "SELECT NAME from ROOMS WHERE MRN= '$MRN'";
  $result = mysql_query($sql) or die ("Invalid sql query getting room name."); 
    
   while ($room = mysql_fetch_array($result)) 
   {
    if (ereg('FT[0-9]', $room[NAME])) {$er_select = "FT";}
    elseif (ereg('UC[0-9]', $room[NAME])) {$er_select = "UC";}
    elseif (ereg('ANNEX[0-9]', $room[NAME])) {$er_select = "ANNEX";}
    else   {$er_select = "Main";}   
   }
   return $er_select;
}

function STAMP ($mrn, $event, $override)
{
   connect($database); 
  echo "<p> FROM STAMP => <br> MRN -> $mrn <br> EVENT -> $event <br> OVERRIDE -> $override <p> ";    
   
   $result = mysql_query("SELECT IND, EVENT FROM time_track WHERE MRN='$mrn' AND EVENT='$event' ");
   
   $num_rows = mysql_num_rows($result);
   if($num_rows == "0") {$new_stamp = "true";}
   else {$new_stamp = "false";}
   
   if($new_stamp == "true") 
   {
      $result = mysql_query("INSERT INTO time_track (MRN, EVENT) VALUES('$mrn','$event') "); 
   }
   elseif($new_stamp == "false" && $override == 'yes')  // old time stamp - over ride with new one
   {
      $result = mysql_query("UPDATE time_track SET TSTAMP = 'now()' WHERE MRN='$mrn' AND EVENT='$event' ");
   }
   else {return;}  //no over ride so do nothing - leave original time stamp
   
}

// Gets  er area based on room number
function get_er_type($room_name)
{
   $result = mysql_query("SELECT ER_TYPE from ROOMS where NAME = '$room_name'");
   while ($row = mysql_fetch_array($result)){$er_type = $row[ER_TYPE];}
   return $er_type;
}

 
function triage_delete($IND)
{
   connect($database);
   $res = mysql_query("DELETE FROM sign_in WHERE IND='$IND' ");
}


function get_er_type_from_MRN($MRN)
{
   $result = mysql_query("SELECT ER_TYPE from ROOMS where MRN = '$MRN' ");
   
   echo "SQL in function get_er_type_from_MRN($MRN) => SELECT ER_TYPE from ROOMS where MRN = '$MRN' <p>";

   while ($row = mysql_fetch_array($result)){$area = $row[ER_TYPE];}
   return $area;
}


function get_mysql_now ()
{
   $r = mysql_query("SELECT UNIX_TIMESTAMP(now() ) ");

   while($row = mysql_fetch_array($r)){
     $now = $row[0];
   }
   return $now;
}

function get_name_from_MRN($MRN){

   $sql = "SELECT PATIENT_LAST_NAME, PATIENT_FIRST_NAME FROM pt_status WHERE MRN = '$MRN' ";
   $r = mysql_query($sql);
   while($row = mysql_fetch_array($r)){
      $lname = $row[PATIENT_LAST_NAME];
      $fname = $row[PATIENT_FIRST_NAME];
   }
   $name = "$fname $lname";
   return $name;
}

function make_lobby_header($order)
{
      $ds = "lobby=true&er_select=All";
      $PHPSELF = $_SERVER['PHP_SELF'];
      
      echo "<center><TABLE BORDER=5 CELLSPACING=2 CELLPADDING=2 WIDTH='90%'>";
      echo "<p>";
      echo "<tr bgcolor=88FFFF>";
      echo "<td> <b> <a href='$_GET[PHP_SELF]?$ds&order=last_name&er_select=All'>
         Name </b> </a> </td> ";
      echo "<td> <b> Age </b> </a> </td>";
      echo "<td> <b> <a href='$PHP_SELF?$ds&order=complaint&er_select=All'>
         Complaint </b> </a> </td> ";
      echo "<td> <b> Acuity </b> </a> </td> ";
      echo "<td> <b> Time </b> </a> </td> ";
      echo "<td> <b> Temp </b>  </td> ";
      echo "<td> <b> Pulse </b>  </td> ";
      echo "<td> <b> BP </b>  </td> ";
      echo "<td> <b> Resp </b>  </td> ";
      echo "<td> <b> Pulse Ox </b>  </td> ";
      echo "<td> <b> <a href='$PHPSELF?$ds&order=LOBBY_TYPE'>Area</a> </b>  </td> ";
      echo "<td> <b> <font='-1'> View<br>Note</font></b>  </td> ";
     // echo "<td> <b> <font='-1'>Edit<br>Note</font></b>  </td> ";
      //echo "</tr></table>";
}   


// used in both triage view and Lobby tab
function show_lobby($order, $er_select, $database)
{
   global $database, $doc_root, $image_dir;
   
   connect($database);

   if(!$order) {$order = "IND";}
      $sql ="SELECT * FROM pt_status LEFT JOIN triage_data 
             ON pt_status.MRN = triage_data.MRN 
             WHERE ER_ROOM_NUMBER='LOBBY'  
             ORDER BY $order ";
             
      $result = mysql_query($sql);
      $num_in_lobbies = mysql_num_rows($result);
     
      echo " <h3> Lobby View </h3>  ";
      echo "<center><table width='90%' border='0'>";
      echo "<tr><td><h5> $num_in_lobbies Patient"; $s = ($num_in_lobbies == "1") ? " " :  "s "; echo "$s";
      echo " traiged and waiting in lobby</h5></td>";
      echo "</tr></table><p>";
     
      make_lobby_header($order); 
           
      while($row = mysql_fetch_array($result))
      {
         $total_time = get_time_from_now($row[REG_TIME], $database);
         $pref = "<a href='/$doc_root/patient.php?MRN=$row[MRN]&er_select=$er_select'>";
         $tref = "<a href='/$doc_root/show_triage_info.php?MRN=$row[MRN]'>";

	 if($row[CURRENT_STATUS] == "walkout") {$strike = "<s>";}
         
         echo "<tr bgcolor=FFFFFF>
               <td> $pref$strike$row[last_name], $row[first_name]</a> </td>
               <td> $strike$row[age]    </td>
               <td> $strike$row[complaint] </td>
               <td> $strike$row[acuity] </td>
               <td> $strike$total_time  </td>
              ";
         $color = flag_vitals("temp", $row[temp]);
         echo "<td> $color $strike$row[temp] </td> ";
         
         $color = flag_vitals("pulse", $row[pulse]);
         echo "<td> $color $strike$row[pulse] </td> ";
         
         $color = flag_vitals("SBP", $row[SBP]);
         echo "<td> $color $strike$row[SBP]/";
         
         $color = flag_vitals("DBP", $row[DBP]);
         echo "$color $strike$row[DBP]</td>";
               
         $color = flag_vitals("resp", $row[resp]);     
         echo "<td> $color $strike$row[resp] </td>";

         $color = flag_vitals("pox", $row[pox]);     
         echo "<td> $color $strike$row[pox] </td>";

         echo "<td><a href='toggle_lobby.php?MRN=$row[MRN]'> $strike$row[LOBBY_TYPE] </td>";

         ?> <td> <a href='#' onClick='window.open("/<?echo $doc_root?>/show_triage_info.php?<?echo "MRN=$row[MRN]"; ?>","Triage_Info","width=400,height=600,location=0,menubar=0,scrollbars=1,status=0,resizable=1")'>
<img src='<?echo $image_dir;?>/doc.gif'></a> 
           </td></tr>
         <?
      }
      echo "</table>";
}

function flag_vitals($vital, $val)
{
   switch ($vital){
     case temp:
        if($val > 100.4 || $val < 95.5) { $color = "<b><font color='FF0000'>" ;}
        return $color;
        break;
    case SBP:
       if($val > 180 || $val < 85) { $color = "<b><font color='FF0000'>" ;}
       return $color;
       break;
    case DBP:
       if($val > 110 || $val < 50) { $color = "<b><font color='FF0000'>" ;}
       return $color;
       break;
   case pulse:
       if($val > 120 || $val < 50) { $color = "<b><font color='FF0000'>" ;}
       return $color;
       break;
   case resp:
       if($val > 25 || $val < 12) { $color = "<b><font color='FF0000'>" ;}
       return $color;
       break;
   case pox:
       if( $val < 92) { $color = "<b><font color='FF0000'>" ;}
       return $color;
       break;
   }
}

function get_triage_interval() {
   $sql = "SELECT TRIAGE_INTERVAL FROM config";
   $r = mysql_query($sql);
   while($row = mysql_fetch_array($r)){
      $triage_interval = $row[TRIAGE_INTERVAL];
   }
return $triage_interval;
}


  
