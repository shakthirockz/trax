

<html>
<head>
<title>Re-Triage</title>
<?
include "util.php";
include "config.php";
?>

<script type="text/javascript">
<!--
function setFocus()
{
   document.forms[0].last_name.focus()
}

  function c(){
     msg = "Enter triage data?";
     return confirm(msg);
  }
-->
</SCRIPT>

</head>

<body bgcolor=cyan text=black link=blue vlink=#blue alink=#00FFFF onLoad="setFocus();">
<?
include "navbar.php";
?>
 <h3>Re- Triage </h3>
 <?
foreach($_GET as $k=>$v) {echo "$k=>$v<br>";}

if($mode == "flag_walkout")
{
   connect($database);
   
   $IND = $_GET[ind];
   
   $t = mysql_query("SELECT CALL_COUNT FROM sign_in WHERE IND='$IND' ");
   while($row=mysql_fetch_array($t)){
      $old_count = $row[CALL_COUNT];
   }
   echo "OLD_COUNT => $old_count <p>";
   $new_count = $old_count +1;
   echo "NEW_COUNT => $new_count <p>";

   $result = mysql_query("UPDATE sign_in SET WALKOUT ='yes', 
                       LAST_TRIAGE = '$timestamp',
                       CALL_COUNT = '$new_count'
                       WHERE IND='$IND' ");
                                     
echo "PT # $_GET[ind] flagged as walkout.<p>";

echo "<script language='JavaScript'>
 this.document.location='/trax/show_wait_walkout.php';
</script>";
exit();
}

if($mode == "walkout_ck")
{
connect($database);

$sql = "SELECT sign_in.IND,
                        sign_in.TSTAMP,
                        DATE_FORMAT(sign_in.TSTAMP, '%H:%i') AS SIGN_IN_TIME,
		        pt_status.PATIENT_FIRST_NAME,
			pt_status.PATIENT_LAST_NAME
                        FROM sign_in LEFT JOIN pt_status
                        ON sign_in.MRN = pt_status.MRN
                         WHERE MRN = '$_GET[MRN]'   ";

$r = mysql_query($sql);

echo "<b>Patient Name:</b> $_GET[last_name], $_GET[first_name]  <p>
 <b>Sign in Time:</b>
";

while($row = mysql_fetch_array($result)){

   echo "$row[SIGN_IN_TIME] <p>";
   $ind = $row[IND];
   $tstamp = $row[TSTAMP];
   $waiting_time = get_time_from_now($row[TSTAMP], $database);
}

echo "<b>Call to triage time: </b>"; echo date('H:i'); echo "<p>";

echo "<b>Waiting time: </b> $waiting_time <p>";

echo "<table width='50%'>
 <tr><td>
 <input type='button' value='No Response' ";
 
 ?> onClick='this.document.location="<?echo "$_SERVER[PHP_SELF]?mode=flag_walkout&ind=$ind"?>"'>  <?
echo "</td><td>
 <input type='button' value='Continue to Triage'";
 ?>
onClick='this.document.location="
<?echo "$_SERVER[PHP_SELF]?mode=form&first_name=$_GET[first_name]&last_name=$_GET[last_name]&stamp=true&ind=$ind&tstamp=$tstamp"?>"'>


</td> <td> <input type='button' value='Cancel' onClick='this.document.location="show_wait_walkout.php"'>
       </td>
    </tr>
   </table>
<?
exit();
}


if($mode == "form")
{
    $MRN = $_GET[MRN];

    $r = mysql_query("SELECT * FROM triage_data WHERE MRN = '$MRN' ");

    while($row = mysql_fetch_array($r) {

?>
<form action='enter_triage_data.php' onSubmit='return c()'>

<center>
<Table border='1' width='60%'>
<CAPTION align="left"> <b>Patient Name</b> </CAPTION>
<tr><td>
First     </td><td>  <input type="text" name="first_name" value='<?echo "$_GET[first_name]";?>' size="10" maxlength="10">  </td><td>
Last      </td><td>   <input type="text" name="last_name" value='<?echo "$_GET[last_name]";?>' size="15" maxlength="25"> </td></tr>
</table>
<p>

<Table border='1' width='60%'>
<tr><td><b>Age:&nbsp </b> </td><td><input name="age" size="4"
value='<?echo "$row[age]";?>> </td>
<td><b>Sex:&nbsp </b> </td><td>
Male <input type="radio" name="sex" value="male"
             <?if($row[sex] =="male"){echo "selected";}?>
	     Female <input type="radio" name="sex" value="female"
	     <?if($row[sex] =="female"){echo "selected";}?>>
		      </td>
</tr>
</table><p>

<!--
<Table border='1' width='60%'>
<tr><td>
Medical Record Number:    </td><td> <input type="text" name="MRN" size="7" maxlength="7">
<font size='-1'><i> (seven digits) </i></font> </td></tr>
</table><p>
-->

<Table border='1' width='60%'> <CAPTION align="left"> <b>Chief Complaint</b> </CAPTION>
<tr><td> Choose:

<?
$complaints = get_complaint_list($database);
sort($complaints);

echo "<SELECT name='complaint_1'>";
foreach($complaints as $comp)
{
if ($comp == "empty")
{
 echo "<option value='$comp' selected> </option>";
}
else
{
 echo "<option value='$comp'>$comp</option>";
}
}
?>
</SELECT>

</td> 
<td>or write in:
   <input type="text" name="complaint_2"  size="25" maxlength="25"> 
</td></tr>
</table>
<p>

<Table border='1' width='60%'> <CAPTION align="left"> <b>Vital Signs</b> </CAPTION>
<tr>
    <td> Temp:<input name="temp" size="6" maxlength='5'>      </td>
    <td> BP:<input name="SBP" size="3" maxlength='3'> /
	    <input name="DBP" size="3" maxlength='3'>         </td> </tr>
<tr>
    <td> Pulse:<input name="pulse" size="4" maxlength='3'>    </td>
    <td> Resp:<input name="resp" size="8" maxlength='2'>      </td> </tr>
<tr>
    <td> Sat:<input name="pox" size="4" maxlength='3'>%        </td>
    <td> Glucose:<input name="glu" size="4" maxlength='4'>   </td>
</tr>

<tr>   <td> Pain Scale:</td><td>
    <? for($i=1; $i<=10; $i++) { echo "$i<input type='radio' name='pain_scale' value='$i'>";} ?>   </td>
</tr>

</table>
<p>

<Table border='1' width='60%'> <CAPTION align="left"> <b>Past Medical History</b> </CAPTION>

<tr><td> <textarea name="pmh" rows="3" cols="40"></textarea> </td> </tr>
</table>

<p>     
<Table border='1' width='60%'>
<tr><td> <b> Medications </b> </td>
    <td> <b> Allergies   </b> </td> 
</tr>
<tr><td> <textarea name="meds" rows="4" cols="20"></textarea> </td>
    <td> <textarea name="allergies" rows="4" cols="20"></textarea> </td>
</tr>
</table>
<p>

<Table border='1' width='60%'>  <CAPTION align="left"> <b>History of Present Illness</b> </CAPTION>
   	<tr><td> <textarea name="hx" rows="5" cols="40"></textarea> </td> </tr>
  </table>
  <p>

  
   function c(){
     msg = "Enter triage data?";
     return confirm(msg);
  }

  <Table border='1' width='60%'>  <CAPTION align="left"> <b>Acuity</b> </CAPTION>
   	<tr>
            <td>    high <input type="radio" name="acuity"  value='emergent'> </td>
   	    <td>    med  <input type="radio" name="acuity"  value='urgent'>  </td>
   	    <td>    low  <input type="radio" name="acuity"  value='stable'>  </td>
        </tr>
   </Table>
   <p>
  
  <Table border='1' width='60%'>  <CAPTION align="left"> <b>Assigned Waiting Area</b> </CAPTION>
   	<tr>
<?
   connect($database);
   $result = mysql_query("SELECT * FROM WAITING_AREAS");
   while ($row = mysql_fetch_array($result))
     {
        echo "<td> $row[NAME] <input type='radio' name='lobby_type'  value='$row[NAME]'> </td> ";
     }
   ?>
   	</tr>
   </Table>
   <p>
   <?
   echo "<input type='hidden' name='MRN' value='$MRN'>";
   echo "<input type='hidden' name='signin_ind' value='$_GET[ind]'>";
   ?>
    <input type="submit" name="Submit" value="Submit" size='10'>
    <input type=reset value="Clear" size='10'>
    </p>
   </form>
   <p>
   </body>
   </html>
   <?
}

}
