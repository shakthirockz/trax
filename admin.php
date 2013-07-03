
<html><head>
<title>Admin</title>
  <Script language="JavaScript">
  function c(){
     msg = "Do you really want to erase all the tables?";
     return confirm(msg);
  }  
  function a(){
     msg = "Do you really want to delete this area? All rooms in this area will also be deleted.";
     return confirm(msg);
  } 
    function w(){
     msg = "Do you really want to delete this waiting area?";
     return confirm(msg);
  } 
 
function u(){
     msg = "Change the triage form modules now?";
     return confirm(msg);
  } 

   </script>
<?
//foreach($_GET as $k => $v) {echo "$k => $v <br>";}
include 'util.php';
include 'config.php';

echo "<h2>Adminstrator's Page </h2> <p> <hr> </hr> <p> ";

$mode = $_GET[mode];
$action = $_GET[action];

if(!$mode) {$mode="admin";}

if ($mode == "admin")
{
   echo "<h3>Purge all Databases </h3> <p>";
   
  echo" <form action='$PHPSELF' onSubmit='return c()'>
        <input type='hidden' name='mode' value='update'> 
        <input type='hidden' name='action' value='purge'> 
        <input type='Submit' value='Purge all Current Patients'>
        </form>
      ";
       
   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Add a Doctor </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_doctor'>";
   
   echo "<p> First name: <input name='mdfname'> <br>";
   echo "<p> Last name: <input name='mdlname'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Delete a Doctor </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_doctor'>";
   
   connect($database);
   
   $sql = "SELECT FIRST_NAME, LAST_NAME FROM MD_names ORDER BY LAST_NAME";
   $result = mysql_query($sql);
   
   echo "<SELECT name='fname_lname'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[FIRST_NAME]_$row[LAST_NAME]'>$row[FIRST_NAME] $row[LAST_NAME]</option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";

   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Add a Nurse </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_nurse'>";
   
   echo "<p> First name: <input name='rnfname'> <br>";
   echo "<p> Last name: <input name='rnlname'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Delete a Nurse </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_nurse'>";
   
   connect($database);
   
   $sql = "SELECT FIRST_NAME, LAST_NAME FROM RN_names ORDER BY LAST_NAME";
   $result = mysql_query($sql);
   
   echo "<SELECT name='fname_lname'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[FIRST_NAME]_$row[LAST_NAME]'>$row[FIRST_NAME] $row[LAST_NAME]</option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";  

   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Add a complaint </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_complaint'>";
   
   echo "<p> Complaint: <input name='complaint'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
      
   echo "<h3>Delete a complaint </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_complaint'>";
   
   connect($database);
   
   $sql = "SELECT COMP from complaints";
   $result = mysql_query($sql);
   
   echo "<SELECT name='complaint'>";
   $comps=array();
   while($row = mysql_fetch_array($result))
{array_push($comps, $row[COMP]);}
   sort($comps);
   foreach($comps as $c)
   {
      echo "<option value='$c'>$c</option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>"; 

   echo "<p> <hr> </hr> <p> ";
   
   echo "<h3>Add a status flag </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_status_flag'>";
   
   echo "<p> Status flag: <input name='status_flag'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
      
   echo "<h3>Delete a status flag </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_status_flag'>";
   
   connect($database);
   
   $sql = "SELECT NAME from status_flags";
   $result = mysql_query($sql);
   
   echo "<SELECT name='status_flags'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[NAME]'>$row[NAME] </option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";  
   
   
   
   echo "<p> <hr> </hr> <p> ";

   echo "<h3>Add a Room </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_room'>";
   
   echo "<p> Room: <input name='new_room'><p>";
   
   $result = mysql_query("SELECT NAME from AREAS");
   
   echo "Area: <SELECT name='area'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[NAME]'>$row[NAME] </option>";
   }
   echo "</select>";
   
   echo "&nbsp &nbsp <input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
      
   echo "<h3>Delete a Room </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_room'>";
   
   connect($database);
   
   $sql = "SELECT NAME from ROOMS";
   $result = mysql_query($sql);
   
   echo "<SELECT name='room'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[NAME]'>$row[NAME] </option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";
   
  echo "<p> <hr> </hr> <p> ";

   echo "<h3>Add an Area </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_area'>";

   echo "<p> Area: <input name='area'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";
   
   echo "<p> <hr> </hr> <p> ";
      
   echo "<h3>Delete an area </h3>  ";
   echo "<form action='$PHPSELF' onSubmit='return a()'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_area'>";

   connect($database);
   
   $sql = "SELECT NAME from AREAS";
   $result = mysql_query($sql);

   echo "<SELECT name='area'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[NAME]'>$row[NAME] </option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";



   echo "<p> <hr> </hr> <p> ";

   echo "<h3>Add a Waiting Area (Lobby)</h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='add_waiting_area'>";

   echo "<p> Waiting Area Name: <input name='waiting_area'>";
   echo "<input type='submit' value='Add'><p>";
   echo "</form>";

   echo "<p> <hr> </hr> <p> ";

   echo "<h3>Delete a Waiting Area (Lobby)</h3>  ";
   echo "<form action='$PHPSELF' onSubmit='return w()'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='remove_waiting_area'>";

   connect($database);

   $sql = "SELECT NAME from WAITING_AREAS";
   $result = mysql_query($sql);

   echo "<SELECT name='waiting_area'>";
   while($row = mysql_fetch_array($result))
   {
      echo "<option value='$row[NAME]'>$row[NAME] </option>";
   }
   echo "</select>";

   echo "<input type='submit' value='Delete'><p>";
   echo "</form>";


   echo "<p> <hr> </hr> <p> ";

   echo "<h3>Modify Triage Form</h3>  ";
   echo "<form action='$PHPSELF' onSubmit='return u()'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='update_triage_form_modules'>";

   connect($database);

   $sql = "SELECT * from triage_form_modules";
   $result = mysql_query($sql);
   echo" <table witdth='60%'>";
   while ($row = mysql_fetch_array($result)){
     echo "<tr><td> $row[NAME] </td><td> yes <input type='radio' name='$row[NAME]' value='yes' ";
                                          if($row[USED] == 'yes') {echo "checked>";}
					  else {echo ">";}
                                   echo " &nbsp no <input type='radio' name='$row[NAME]' value='no'";
      					  if($row[USED] == 'no') {echo "checked>";}
					  else {echo ">";}
     echo "</td></tr>";
  }
  echo"</table>";
  echo "<input type='submit' value='Update'><p>";
  echo "</form>";

  echo "<p> <hr> </hr> <p> ";


   echo "<h3>Change Re-triage Interval </h3>  ";
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='update'>";
   echo "<input type='hidden' name='action' value='change_retriage_interval'>";

   connect($database); 
   $sql = "SELECT TRIAGE_INTERVAL from config";
   $r = mysql_query($sql);
   while($row = mysql_fetch_array($r)){$triage_interval = $row[TRIAGE_INTERVAL]/60;}
   
   echo "<p> Set retriage interval to &nbsp <input name='triage_interval' value='$triage_interval' size='4'>";
   echo " Minutes. &nbsp <input type='submit' value='Change'><p>";
   echo "</form>";
         

}

// ********************* END FORM - START UPDATE ****************************

if ($mode == "update")
{
   $action = $_GET[action];

   switch ($action)
   {
      case purge :
      {
        //(purge fxn is in utils.php)
	$suc = purge();
        if($suc == '1') {echo "Tables Purged. All rooms and lobby are set to empty.
                                <p><a href='admin.php'>Back to Admin Page.</a> <p";}
        else {echo "ERROR: Tables not purged.";}
        break;
      }

      case add_doctor :
      {
        $mdfname = $_GET[mdfname];  $mdlname = $_GET[mdlname];
        $suc = add_doctor($mdfname, $mdlname);
        break;
      }

      case remove_doctor :
      {
        $fname_lname = $_GET[fname_lname];
        $suc = remove_doctor($fname_lname);
        break;
      }

      case add_nurse :
      {
        $rnfname = $_GET[rnfname];  $rnlname = $_GET[rnlname];
        $suc = add_nurse($rnfname, $rnlname);
        break;
      }

      case remove_nurse :
      {
        $fname_lname = $_GET[fname_lname];
        $suc = remove_nurse($fname_lname);
        break;
      }

      case add_complaint :
      {
        $complaint = $_GET[complaint];
        $suc = add_complaint($complaint);
        break;
      }

      case remove_complaint :
      {
        $complaint = $_GET[complaint];
        $suc = remove_complaint($complaint);
        break;
      }

      case add_status_flag :
      {
        $status_flag = $_GET[status_flag];
        $suc = add_status_flag($status_flag);
        break;
      }

      case remove_status_flag :
      {
        $status_flag = $_GET[status_flags];
        $suc = remove_status_flag($status_flag);
        break;
      }

      case add_room :
      {
        $new_room = $_GET[new_room];
        $area = $_GET[area];
        $suc = add_new_room($new_room, $area);
        break;
      }

      case remove_room :
      {
        $room = $_GET[room];
        $suc = remove_room($room);
        break;
      }

      case add_area :
      {
        $area = $_GET[area];
        $suc = add_area($area);
        break;
      }

      case remove_area :
      {
        $area = $_GET[area];
        $suc = remove_area($area);
        break;
      }
      case add_waiting_area :
      {
        $waiting_area = $_GET[waiting_area];
        $suc = add_waiting_area($waiting_area);
        break;
      }

      case remove_waiting_area :
      {
        $waiting_area = $_GET[waiting_area];
        $suc = remove_waiting_area($waiting_area);
        break;
      }

      case update_triage_form_modules :
      {
       $current_module_status = $_GET;
      $suc = update_triage_form_modules($_GET);
      }

      case change_retriage_interval :
      {
       $new_retriage_interval = $_GET[triage_interval];
       $suc = update_retriage_interval($new_retriage_interval);
      }
   }

echo "<script language='JavaScript'>
         this.location='/$doc_root/admin.php?mode=admin';
      </script>";

}


include "footer.php";


function add_doctor($mdfname, $mdlname)
{
   connect($database);
   $sql = "INSERT into MD_names (FIRST_NAME, LAST_NAME) VALUES('$mdfname', '$mdlname') ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);

    echo "Dr. $mdfname $mdlname has been added.<p>
	<p><a href='admin.php'>Back to Admin Page.</a> <p>";
}

function remove_doctor($mdfname_mdlname)
{
   connect($database);

   list($first, $last) = split("_", $mdfname_mdlname);

   $sql = "DELETE FROM MD_names WHERE FIRST_NAME = '$first' AND  LAST_NAME = '$last' ";
   echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Dr. $first $last has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p
          ";  
    exit();
}

function add_nurse($rnfname, $rnlname)
{
   connect($database);
   $sql = "INSERT into RN_names (FIRST_NAME, LAST_NAME) VALUES('$rnfname', '$rnlname') ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Nurse $rnfname $rnlname has been added.<p>
           <a href='admin.php'>Back to Admin Page.</a> <p";  
}

function remove_nurse($fname_lname)
{
   connect($database);
   
   list($first, $last) = split("_", $fname_lname);
   
   $sql = "DELETE FROM RN_names WHERE FIRST_NAME = '$first' AND  LAST_NAME = '$last' ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Nurse $first $last has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p
          ";  
}

function add_complaint($complaint)
{
   connect($database);
   $sql = "INSERT into complaints (COMP) VALUES('$_GET[complaint]') ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Complaint: $_GET[complaint] has been added.  <p>
	<p><a href='admin.php'>Back to Admin Page.</a> <p";  
}

function remove_complaint($complaint)
{
   connect($database);
   
   $sql = "DELETE FROM complaints WHERE COMP = '$complaint' ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Complaint: $complaint has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p
          ";  
}  
                 
function add_status_flag($status_flag)
{
   connect($database);
   $sql = "INSERT into status_flags (NAME) VALUES('$status_flag') ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Status_flag: $status_flag has been added.  <p>
	<a href='admin.php'>Back to Admin Page.</a> <p>";  
}

function remove_status_flag($status_flag)
{
   connect($database);
   
   $sql = "DELETE FROM status_flags WHERE NAME = '$status_flag' ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Status_flag: $status_flag has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p
          ";  
}

function add_new_room($new_room, $area)
{
   connect($database);
   
   if($area == ""){ echo "ERROR: Area can not be empty.  Please choose an area to associate with this room
                       or use the add area section to create some areas if you have not done so yet.<p>
                       <p><a href='admin.php'>Back to Admin Page.</a> <p>";
                    exit();
                   }
 
   $res = mysql_query("SELECT NAME, ER_TYPE from ROOMS WHERE NAME = '$room_name' "); 
   $name = mysql_fetch_array($res);
   if ($name[NAME] == $new_room && $name[ER_TYPE] == $area) 
    {echo "ERROR: There is already a room named $new_room in area: $area.<p>
                       Please choose a different name and / or area.
                       <p><a href='admin.php'>Back to Admin Page.</a> <p>";
                    exit();}  
                    
    $r = mysql_query("INSERT INTO ROOMS (NAME, ER_TYPE) VALUES('$new_room', '$area')");
    
    if($r == "TRUE"){ echo "$new_room in AREA: $area sucessfully added.  
                           <p><a href='admin.php'>Back to Admin Page.</a> <p>";
                    }
     else{echo "ERROR: unable to add $new_room in AREA: $area.
                        <p><a href='admin.php'>Back to Admin Page.</a> <p>";
         }
}

function remove_room($room)
{
   connect($database);
   
   $r = mysql_query("DELETE FROM ROOMS WHERE NAME = '$room' ");
   
   if($r == "TRUE"){ echo "ROOM: $room sucessfully deleted.  
                           <p><a href='admin.php'>Back to Admin Page.</a> <p>";
                    }
     else{echo "ERROR: unable to delete room: $room.
                        <p><a href='admin.php'>Back to Admin Page.</a> <p>";
         }
}

function add_area($area)
{
   connect($database);
   $sql = "INSERT into AREAS (NAME) VALUES('$area') ";
   //echo "SQL => $sql <p>";
   $result = mysql_query($sql);
  
    echo "Area: $area has been added.  <p>
	<a href='admin.php'>Back to Admin Page.</a> <p>";  
}

function remove_area($area)
{
   connect($database);

   $sql = "DELETE FROM AREAS WHERE NAME = '$area' ";
   $result = mysql_query($sql);
   $sql = "DELETE FROM ROOMS WHERE AREA = '$area' ";
   $result = mysql_query($sql);

  
    echo "Area: $area has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p
          ";
}

function add_waiting_area($waiting_area)
{
   connect($database);
   $sql = "INSERT into WAITING_AREAS (NAME) VALUES('$waiting_area') ";
   $result = mysql_query($sql);
  
    echo "Waiting Area: $waiting_area has been added.  <p>
	<a href='admin.php'>Back to Admin Page.</a> <p>";
}

function remove_waiting_area($waiting_area)
{
   connect($database);

   $sql = "DELETE FROM WAITING_AREAS WHERE NAME = '$waiting_area' ";
   $result = mysql_query($sql);

    echo "Waiting Area: $waiting_area has been deleted.<p>
          <p><a href='admin.php'>Back to Admin Page.</a> <p>
        ";
}


function update_triage_form_modules ($_GET){
   
   connect($database);
   foreach ($_GET as $k => $v) {
      if($k == 'mode' || $k == 'action') {continue;}
      
      echo "$k => $v <br>";

      $sql = "UPDATE triage_form_modules SET USED = '$v' WHERE NAME = '$k' ";
      echo "SQL => $sql <br>";
      $r = mysql_query($sql);

      echo "RESULT => $r<p>";
   }
}

function update_retriage_interval($new_retriage_interval) {

    connect($database);
    $new_retriage_interval = $new_retriage_interval * 60;   // convert mins to secs
    $sql = "UPDATE config SET TRIAGE_INTERVAL = '$new_retriage_interval' ";
    echo $sql ;
    $r = mysql_query($sql);
}
   
?>

