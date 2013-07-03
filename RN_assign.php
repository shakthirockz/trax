<?
include 'util.php';
   
//Change colors
echo "<html><head><title>Trax - Nursing Assignments</title></head>";
echo "<body bgcolor='cyan' text='black' link='blue' vlink='green'>";
echo "<h2>Nursing Assignments "; echo date("m-j-Y ");
echo " </h2> <p> <hr> </hr> <p> ";

$mode = $_GET[mode];

if(!$mode) {$mode="form";}

if ($mode == "form")
{
   $RNS = $rooms = array(); 
   connect("trax");
   
   // Get list of nurse names
   $RN_name_sql = "SELECT * from RN_names"; 
   $RN_name_result = mysql_query($RN_name_sql);
   
   while($RN_name_row = mysql_fetch_array($RN_name_result))
   {
      $RN_smoosh_name = "$RN_name_row[FIRST_NAME]_$RN_name_row[LAST_NAME]";
      array_push($RNS, $RN_smoosh_name);
   }
   
   //Get list of rooms
   $room_result = mysql_query("SELECT NAME FROM ROOMS");
   
   while($room_row = mysql_fetch_array($room_result))
   {
      array_push($rooms, $room_row[NAME]);
   }
   
   //$dt = date("Y-m-d H:i:s");
   echo "<form action='$PHPSELF'>";
   echo "<input type='hidden' name='mode' value='save'>";
   //echo "<input type='hidden' name='dt' value='$dt'>";
   
   echo "<center><TABLE width='60%' border='3' cellspacing='3' cellpadding='3'>";
   
   sort($rooms);
   foreach($rooms as $rm)
   {
      echo "<tr><td>$rm</td>";
      
      echo "<td><SELECT name='$rm'>";
      echo "<option value=''></option>";
      
      sort($RNS);
      foreach($RNS as $rn)
      {
         $cln_rn = str_replace("_", " ", $rn); 
         echo "<option value='$rn' ";
         
         $cur = check_if_current($rn, $rm);
         if($cur == 'true') {echo " selected";}

         echo ">$cln_rn</option>";
      }
      
      echo"</select></td></tr>";
   }
   echo "</table>"; 
   echo "<p><center><input type='submit' value='Save Assignment'><p>";
   echo "</form>"; 
}

if ($mode == "save")
{
   connect("trax");
   
   $result = mysql_query("DELETE FROM RN_assign_current");
   
   foreach ($_GET as $room => $rn) 
   { 
      if($room == 'mode'){ continue; }
      $result = mysql_query("INSERT into RN_assign_current (ROOM, NAME) VALUES('$room', '$rn')"); 
   }
   
   show_current_assignment();
   
   ?> 
   <p><center> <input type='button' value='Change Assignments' 
                onClick="parent.frames[1].document.location='RN_assign.php';"> 
   <?
}

// #### END MAIN PROGRAM  ###

function check_if_current($rn, $rm)
{
   $result = mysql_query("SELECT NAME from RN_assign_current WHERE ROOM = '$rm' ");
   
   while($row = mysql_fetch_array($result))
   {
      if($row[NAME] == $rn) {return("true");}
      else {return("false");}
   }
}

function show_current_assignment()
{
   $result = mysql_query("SELECT * FROM RN_assign_current");
   
   echo "<center> <TABLE width='60%' border='3' cellspacing='3' cellpadding='3'>";
   
   while ($row = mysql_fetch_array($result))
   {
      $cln_rn = str_replace("_", " ", $row[NAME]); 
      echo "<tr> <td> $row[ROOM] </td> <td> $cln_rn </td> </tr> ";
   }
   echo "</table>";
}
   
   
?>







