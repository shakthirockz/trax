<?
include "util.php";
$database = "trax";
$table = "pt_status";
$footer = "<center><a href='show_ER_patients.php?er_select=All'>Back to Patient Tracking</a>";

echo "
<html> <head><title>Trax_RT System - Reports.</title></head>
<body bgcolor='cyan' text='black' link='blue' vlink='green'> 
<h2>Trax System </h2><p><b><p><hr></hr><p>

<h3>Select time interval for report:</h3><p>
<form action='$PHP_SELF?mode=lobbytime'>
";

$current_month = date(F);

print "MONTH: &nbsp&nbsp <select name='month'> ";

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");  

for ($i=0; $i<12; $i++) 
{
    $val = ($i+1);
    if ($i<10)
    {
       print "<option value='0$val'";
    }
          else
          {
             print "<option value='$val'";
          } 
    if ($months[$i] == $current_month) 
    {
       print "selected> $months[$i] </option>";
       continue;
    }   
    print ">$months[$i] </option>";
}

print "</select> ";

echo"
<input type='hidden' name='mode' value='calc'>
Enter Start Day: <input name='start_day' size='2' length='2'> &nbsp &nbsp
Enter End Day: <input name='end_day' size='2' length='2'> &nbsp &nbsp
";
?>
YEAR:
<select name='year'>
  <option value="2002">2002</option>
  <option value="2003" selected>2003</option>
  <option value="2004">2004</option>
  <option value="2005">2005</option>
</select>
<p>
Output in HR:MIN:SEC format<input type='radio' name='output_format' value='ftime' checked>
Output in long format <input type='radio' name='output_format' value='stime'>
<p>
<input type='submit' value='show'>
<input type=reset value="Erase">
</form>
<?
if ($mode != "calc") {echo $footer; exit;}
if (!$start_day || !$end_day) {echo $footer; exit;}

echo "<p><hr></hr><p>";

echo "<center> <table width='50%' border='5' cellspacing='3' cellpadding='3'>";
echo "<caption><h3>Data Report for time period: $month/$start_day to $month/$end_day $year</h3></caption>";

   connect($database);
   
   $sql = "SELECT (UNIX_TIMESTAMP(IN_ER_ROOM_TIME) - UNIX_TIMESTAMP(REG_TIME))
           FROM pt_status 
           
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' ";
   
    $result = mysql_query($sql);   
    
    $times = array();

    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
    }
   
    $ave = array_sum($times)/count($times);
   
    $ave_time = secs_to_ftime($ave);
    
    echo "<tr><td>Average waiting room time </td><td> $ave_time </td></tr>";
    
   /*____________________________________________________________________________*/


    $sql = "SELECT (UNIX_TIMESTAMP(DISCHARGE_TIME) - UNIX_TIMESTAMP(REG_TIME))
           FROM pt_status 
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' 
           AND CURRENT_STATUS = 'Discharged'";
           
    $result = mysql_query($sql);   
    
    $times = array();
    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
      
    }
    
    if(count($times) == 0) {$ave =0;}
    else {$ave = array_sum($times)/count($times);}
    
    $ave_time = secs_to_ftime($ave);
    
    echo "<tr><td>Average Door to Discharge time </td><td> $ave_time </td></tr>";
    
    /*______________________________________________________________________________________*/
    
        $sql = "SELECT(UNIX_TIMESTAMP(ADMIT_TIME) - UNIX_TIMESTAMP(REG_TIME))
           FROM pt_status 
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           CURRENT_STATUS = 'Admitted' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' ";
           
    $result = mysql_query($sql);   
    
    $times = array();
    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
    }
    $ave = array_sum($times)/count($times);

    $ave_time = secs_to_ftime($ave);
    
    echo "<tr><td>Average Door to Admit time </td><td> $ave_time </td></tr>";
    
     /*______________________________________________________________________________________*/
    
           $sql = "SELECT (UNIX_TIMESTAMP(SEND_TO_ROOM_TIME) - UNIX_TIMESTAMP(ADMIT_TIME))
           FROM pt_status 
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' ";
           
    $result = mysql_query($sql);   
    
    $times = array();
    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
    }
    $ave = array_sum($times)/count($times);
    
    $ave_time = secs_to_ftime($ave);
    
    echo "<tr><td>Average time from Admission to up to Room</td><td> $ave_time </td></tr>";
    
    
           $sql = "SELECT (UNIX_TIMESTAMP(LAB_BACK_TIME) - UNIX_TIMESTAMP(LAB_DRAWN_TIME))
           FROM pt_status 
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' ";
           
    $result = mysql_query($sql);   
    
    $times = array();
    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
    }
    $ave = array_sum($times)/count($times);
    
    $ave_time = secs_to_ftime($ave);
    echo "<tr><td>Average time from Lab Drawn to Results back</td><td>  $ave_time </td></tr>";
     

           $sql = "SELECT SEC_TO_TIME((CT_BACK_TIME) - UNIX_TIMESTAMP(CT_READY_TIME))
           FROM pt_status 
           WHERE MONTH(IN_ER_ROOM_TIME) = '$month'
           AND
           DAYOFMONTH(IN_ER_ROOM_TIME) >= '$start_day' AND
           DAYOFMONTH(IN_ER_ROOM_TIME) <= '$end_day' ";
           
    $result = mysql_query($sql);   
    
    $times = array();
    while ($row = mysql_fetch_array($result))
    {
      array_push($times, $row[0]);
    }
    $ave = array_sum($times)/count($times);

    $ave_time = secs_to_ftime($ave);
    echo "<tr><td>Average time from CT order to CT Results back</td><td>  $ave_time </td></tr>";
     
    $start_day = "";  $end_day = "";
    echo "</table>";

echo "<p>" . $footer;

?>


