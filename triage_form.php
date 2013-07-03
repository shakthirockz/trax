
<html>
<head>
<title>Triage</title>
<? 
include "util.php";
include "config.php";
?>

<script type="text/javascript">

function setFocus()
{
   <!-- parent.triage_list.location = "show_triage_list.php"; -->
   document.forms[0].first_name.focus()
}

  function c(){
     msg = "Enter triage data?";
     return confirm(msg);
  }

</SCRIPT>

</head>

<body bgcolor=cyan text=black link=blue vlink=#blue alink=#00FFFF onLoad="setFocus();">
<?
include "navbar.php";
echo "<h3>Triage Form </h3> ";

//foreach($_GET as $k => $v) {echo "$k => $v <br>";}
$mode = $_GET[mode]; 
$last_name = $_GET[last_name];
$first_name = $_GET[first_name];

if(!$mode){$mode = "form";}


if($mode = "form")
{

   $form_modules = get_form_modules();
   //echo "FORM_MODULES => <br>"; foreach($form_modules as $k=>$v){echo "$k => $v <br> ";}

   if($form_modules['NAME'] == 'yes'){
      echo "
         <form action='enter_triage_data.php' onSubmit='return c()'>

        <input type='hidden' name='orig_first_name' value='$_GET[first_name]'>
        <input type='hidden' name='orig_last_name' value='$_GET[last_name]'>
        <center>
        <Table border='1' width='60%'>
        <CAPTION align='left'> <b>Patient Name</b> </CAPTION>
        <tr><td>
        First     </td><td>  <input type='text' name='first_name' value='$_GET[first_name]' size='10' max  length='10' </td><td>
        Last      </td><td>   <input type='text' name='last_name' value='$_GET[last_name]' size='15' maxlength='25'> </td></tr>
        </table>
        <p>
        ";
   }

   if($form_modules['DEMOGRAPHICS'] == 'yes'){
      echo "

         <Table border='1' width='60%'>
         <tr><td><b>Age:&nbsp </b> </td><td><input name='age' size='4'> </td>
         <td><b>Sex:&nbsp </b> </td><td>Male <input type='radio' name='sex' value='male'>
			   Female <input type='radio' name='sex' value='female'>
		      </td>
         </tr>
         </table><p>
        ";
   }

   // MRN assignment and time stamping
   /* This obscure bit of code does the following:
   1) Assigns Unix time stamp as MRN
   2) If patient signed in their time stamp from sign_in table is retrieved and inserted into the 
      time track table
   3) START_TRIAGE time stamp is generated for signed in patients
   */

   $MRN = time();

   // Time stamp sign in time
   $tstamp = $_GET[tstamp];
   $stamp = $_GET[stamp];

   //echo "TSTAMP ==> $tstamp <p> STAMP ==> $stamp <p>";
    if($tstamp != ""){
       $tstamp = fix_timestamp($tstamp, $database);
       connect($database);

       $sign_in_time_stamp_result = 
          mysql_query("INSERT INTO time_track (MRN, EVENT, TM) VALUES('$MRN', 'PT_SIGN_IN', '$tstamp')" )
          or die ("Failed to timestamp sign in time: $tstamp");
    }

    if ($stamp == "true") {$stamp_result = STAMP($MRN, "START_TRIAGE", "yes");}


/*
OLD MANUAL MRN - now deprecated
<Table border='1' width='60%'> 
<tr><td>
Medical Record Number:    </td><td> <input type="text" name="MRN" size="7" maxlength="7"> 
<font size='-1'><i> (seven digits) </i></font> </td></tr>
</table><p>
*/

   if($form_modules['CC'] == 'yes'){
      echo "
            <Table border='1' width='60%'> <CAPTION align='left'> <b>CC: </b> </CAPTION><tr><td> Choose: 
          ";
      $complaints = get_complaint_list($database);
      sort($complaints);

      echo "<SELECT name='complaint_1'>";
      foreach($complaints as $comp){
         if ($comp == "empty"){ echo "<option value='$comp' selected> </option>";
         }
         else{
         echo "<option value='$comp'>$comp</option>";
         }
      }

      echo "
         </SELECT></td> <td>or write in:
         <input type='text' name='complaint_2'  size='25' maxlength='25'> </td></tr>
         </table>
         <p>
        ";
   }

   if($form_modules['VITALS'] == 'yes'){
      echo "
         <Table border='1' width='60%'> <CAPTION align='left'> <b>Vital Signs</b> </CAPTION><tr>
         <td> Temp:<input name='temp' size='6' maxlength='5'>      </td>
         <td> BP:<input name='SBP' size='3' maxlength='3'> /
	    <input name='DBP' size='3' maxlength='3'>         </td> </tr><tr>
         <td> Pulse:<input name='pulse' size='4' maxlength='3'>    </td>
         <td> Resp:<input name='resp' size='8' maxlength='2'>      </td> </tr><tr>
         <td> Sat:<input name='pox' size='4' maxlength='3'>%        </td>
         <td> Glucose:<input name='glu' size='4' maxlength='4'>   </td>
         </tr>
         <tr><td> Pain Scale:</td><td>
        ";
     
        for($i=1; $i<=10; $i++) { echo "$i<input type='radio' name='pain_scale' value='$i'>";} 
   
      echo "</td></tr> </table><p>";
   }

   if($form_modules['PMH'] == 'yes'){
      echo "
         <Table border='1' width='60%'> <CAPTION align='left'> <b>Past Medical History</b> </CAPTION>

         <tr><td> <textarea name='pmh' rows='3' cols='40'></textarea> </td> </tr>
         </table><p>   
        ";
   }

   if($form_modules['MEDS_ALLERGIES'] == 'yes'){
      echo "
         <Table border='1' width='60%'>
         <tr><td> <b> Medications </b> </td>
         <td> <b> Allergies   </b> </td></tr>
         <tr><td> <textarea name='meds' rows='4' cols='20'></textarea> </td>
         <td> <textarea name='allergies' rows='4' cols='20'></textarea> </td></tr>
         </table><p>
        ";
   }

   if($form_modules['HPI'] == 'yes'){
      echo "
         <Table border='1' width='60%'>  <CAPTION align='left'> <b>History of Present Illness</b> </CAPTION>
         <tr><td> <textarea name='hx' rows='5' cols='40'></textarea> </td> </tr>
         </table><p>
        ";
   }


   if($form_modules['ROOM_ASSIGNMENT'] == 'yes'){
      echo "
         <Table border='1' width='60%'>  <CAPTION align='left'> <b>Bed: </b> </CAPTION>
   	 <tr>
        ";
	
      $rooms = get_room_list($database);
      sort($rooms);
      $areas = get_area_list($database);

      echo "<td>AREA: <SELECT name='er_type'>";
      foreach($areas as $a){
         echo "<option value='$a'>$a</option>";
      }
      echo "</td>";

      echo "<td>ROOM: <SELECT name='room'>";
      foreach($rooms as $r){
         echo "<option value='$r'>$r</option>";
      }
      echo "</td>";
      echo "</tr> </table><p>";
   }

   

   if($form_modules['ACUITY'] == 'yes'){
      echo "
         <Table border='1' width='60%'>  <CAPTION align='left'> <b>Acuity</b> </CAPTION>
   	 <tr>
            <td>    5 <input type='radio' name='acuity'  value='5'> </td>
   	    <td>    4 <input type='radio' name='acuity'  value='4'>  </td>
   	    <td>    3 <input type='radio' name='acuity'  value='3'>  </td>
   	    <td>    2 <input type='radio' name='acuity'  value='2'>  </td>
   	    <td>    1 <input type='radio' name='acuity'  value='1'>  </td>
         </tr>
         </Table><p>
       ";
   }

   if($form_modules['ASSIGN_WAITING_AREAS'] == 'yes'){
      echo "
        <Table border='1' width='60%'>  <CAPTION align='left'> <b>Assigned Waiting Area</b> </CAPTION><tr>
        ";
      connect($database);
      $result = mysql_query("SELECT * FROM WAITING_AREAS");
      while ($row = mysql_fetch_array($result))
      {
         echo "<td> $row[NAME] <input type='radio' name='lobby_type'  value='$row[NAME]'> </td> ";
      }
      echo "</tr></Table><p>";
   }

   echo "<input type='hidden' name='MRN' value='$MRN'>"; 
   echo "<input type='hidden' name='signin_ind' value='$_GET[ind]'>";

   echo "<input type='submit' name='Submit' value='Submit' size='10'>
      <input type=reset value='Clear' size='10'>
      <p></form> <p>
      </body></html>
     ";
}

// END MAIN - BEGIN FUNCTIONS

function get_complaint_list($database)
{
   $complaints = array();
   connect($database); 
   $sql = "SELECT COMP FROM complaints" ;

   $result = mysql_query($sql); 
   while($row = mysql_fetch_array($result))
   {
      array_push($complaints, $row[COMP]);
   }  
   return $complaints;
}

function get_room_list($database)
{
   $rooms = array();
   connect($database); 
   $sql = "SELECT NAME FROM ROOMS 
           WHERE (STATUS is NULL || STATUS ='') " ;

   $result = mysql_query($sql); 
   while($row = mysql_fetch_array($result))
   {
      array_push($rooms, $row[NAME]);
   }  
   array_unshift($rooms, "");
   return $rooms;
}


function get_area_list($database)
{
   $areas = array();
   connect($database); 
   $sql = "SELECT NAME FROM AREAS" ;

   $result = mysql_query($sql); 
   while($row = mysql_fetch_array($result))
   {
      array_push($areas, $row[NAME]);
   } 
   sort($areas);
   array_unshift($areas, "LOBBY");
   return $areas;
}


// Fixes raw timestamp for re - insertion in time_track table
  
function fix_timestamp($tstamp, $database)
{
   if(preg_match("/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/", $tstamp, $regs))
   {
      $year  = $regs[1];
      $month = $regs[2];
      $day   = $regs[3];
      $hr    = $regs[4];
      $min   = $regs[5];
      $sec   = $regs[6];
      
      $fixed_time = "$year-$month-$day $hr:$min:$sec";
   }
   return $fixed_time;
}
      
// Determines which modules to use in form

function get_form_modules() {
  global $database;
  
  connect($database);
  $sql = "SELECT * FROM triage_form_modules WHERE USED = 'yes'";
  
  $r = mysql_query($sql);
 
  while( $row = mysql_fetch_array($r)){
      $form_modules[$row[NAME]] = 'yes';
  }
  return $form_modules;
}
      
      
      
      
