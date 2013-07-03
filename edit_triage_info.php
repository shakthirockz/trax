<?
echo "<html><head><title>Triage Info</title>
      <script>
         function rc(){
	    window.opener.location.reload();
	 }   
     </script>
     <head>
    ";
    
echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";
//foreach($_GET as $k=>$v){echo "$k=>$v <br>";}

include_once "util.php";
connect("trax");

$mode = $_GET[mode];
$mrn = $_GET[MRN];

if(!$mode){$mode = "form";}

if($mode == "walkout_ck"){  echo "WALKOUT CHECK DISABLED<P>"; exit(); $walkout = walkout_ck();}

if($mode =="flag_walkout"){ 
   echo" FLAG WALKOUT DISABLED<P>"; 
   exit(); 
   //echo "CALLING FLAG WALKOUT MRN => $mrn<P>"; $flag = flag_walkout($mrn);
}

if($mode == "form")
{
   $result = mysql_query("SELECT * FROM triage_data WHERE MRN = '$_GET[MRN]' ");

   while($row = mysql_fetch_array($result, MYSQL_ASSOC))
   {
   echo "
        <table width='90%' border='1' cellpadding='3' cellspacing='3'>
        <caption><b> Triage info for $row[first_name] $row[last_name]  </caption>
       ";

    echo "<form action='$_SERVER[PHP_SELF]?mode=update' onSubmit='rc();'>";  
    foreach ($row as $k => $v)
   {
         if($k == "IND" ) {continue;}
        // if($k == "MRN" ) {echo "<input type='hidden' name='MRN' value='$k'>";}
         echo "<tr><td><b>$k </td> <td><input name='$k' value=' $v' size='40'> </b></td></tr> ";
   }

   echo "<input type='hidden' name='mode' value='update'>";
   echo "<tr><td>&nbsp</td><td><input type='submit' value='Enter Changes'>";
   echo "<input type='button' value='Cancel'></td></tr>";
   echo "</form></table>";
   }
}

if($mode == "update")
{
   $sql = "UPDATE triage_data SET ";
   
   foreach($_GET as $k => $v){
      $v = ltrim($v);
      if($k =="mode") {continue;}
      if($k == "LAST_TRIAGE") {break;}
      $sql .= " $k = '$v', ";
   }
  
  // $sql = rtrim($sql);
  // $sql = rtrim($sql, ","); $sql .= " ";
  $mrn = ltrim($_GET[MRN]);

  $sql .= "LAST_TRIAGE = '$timestamp' ";
   $sql .= " WHERE MRN = '$mrn' ";
   
  // echo $sql;
   
   mysql_query($sql);

// update the lobby table
   $sql = "UPDATE lobby SET call_count = '0', last_call_time = now() 
           WHERE MRN='$mrn' ";

	   echo "SQL => $sql <p>";

   $r = mysql_query($sql);

echo "<script>
   setTimeout('window.close()', 900);
</script> ";

}

// The following 2 functions (walkout_ck and flag_walkout) are adopted from similar functions in triage_form.php
// They are not the same.  These 2 operate on triaged patients.  The other 2 operate on patients who are merely signed
// in but not yet triaged.

/*
function walkout_ck()
{
connect($database);
$sql = "SELECT PATIENT_FIRST_NAME, PATIENT_LAST_NAME, REG_TIME,
      DATE_FORMAT(REG_TIME, '%H:%i') AS SIGN_IN_TIME
      FROM pt_status
      WHERE MRN = '$_GET[MRN]'  " ;
   
$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
{
echo "<b>Patient Name:</b> $row[PATIENT_LAST_NAME], $row[PATIENT_FIRST_NAME]  <p>
 <b>Sign in Time:</b>
";
echo "$row[SIGN_IN_TIME] <p>";

$waiting_time = get_time_from_now($row[REG_TIME], $database);
}

echo "<b>Call to triage time: </b>"; echo date('H:i'); echo "<p>";

echo "<b>Waiting time: </b> $waiting_time <p>";

echo "<table width='50%'>
 <tr><td>
 <input type='button' value='No Response' ";
 
 ?> onClick='this.document.location="<?echo "$_SERVER[PHP_SELF]?mode=flag_walkout&MRN=$_GET[MRN]"?>"'>  <?
echo "</td><td>
 <input type='button' value='Re-Triage'";
 ?>
onClick='this.document.location="<?echo "$_SERVER[PHP_SELF]?mode=form&MRN=$_GET[MRN]"?>" '>
</td> <td> <input type='button' value='Cancel' onClick='window.close()'>

       </td>
    </tr>
   </table>
<?
exit();
}

// handles database updates for patients who walkout after being triaged.
function flag_walkout ($mrn)
{

   connect($database);

   $r = mysql_query("SELECT call_count FROM lobby WHERE MRN = '$mrn'");
   while($row = mysql_fetch_array($r)){$curr_ct = $row[call_count];}
   $curr_ct++;

   // update lobby table
   $r = mysql_query("UPDATE lobby SET walkout = 'yes',
                                      call_count = '$curr_ct',
				      last_call_time = now()
                     WHERE MRN='$mrn'
                    ")
                         or die("ERROR updating lobby.walkout to -yes-.  MRN= $mrn");


   $r = mysql_query("DELETE FROM lobby WHERE call_count > '3' ");

echo "<script>
        window.opener.location.reload();
        window.close(); 
     </script> ";

}
*/
?>

