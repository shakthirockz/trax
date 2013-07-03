<?
include 'util.php';
include "config.php";

if($_GET[mode] == "delete"){triage_delete($_GET[IND]);}

$table ="sign_in"; //the table to search

echo "<html>";
echo "<head><title>Patients in $er_select.</title>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";

echo "</head>";

echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";


connect ($database);

if(!$order) {$order = "TSTAMP";}
	   

$sql = "SELECT IND, FIRST_NAME, LAST_NAME, TSTAMP from sign_in 
        WHERE WALKOUT != 'yes' AND TRIAGED = 'no' ORDER BY $order";
       
$result = mysql_query("$sql");

$num_pts = mysql_num_rows($result);

echo "<center><TABLE BORDER=0 CELLSPACING=2 CELLPADDING=2 WIDTH='90%'>"; 

echo "<CAPTION> <h3> $num_pts Patient";
$s = ($num_pts == "1") ? " " :  "s "; echo "$s";
echo "waiting for triage</h3> </CAPTION>";
echo "<p>";
echo "<tr bgcolor=88FFFF>";
echo     "<td> <b> <a href='$PHP_SELF?order=LAST_NAME'>Name </b> </a> </td> ";
echo     "<td> <b> <a href='$PHP_SELF?order=TSTAMP'>Time </b> </a> </td> ";

while ($row = mysql_fetch_array ($result)) 
{

   $total_time = get_time_from_now($row[TSTAMP], $database);

   //$needs_retriage = check_needs_retriage($total_time);
    echo "<tr> <td>";

   $is = "last_name=$row[LAST_NAME]&first_name=$row[FIRST_NAME]&ind=$row[IND]";
    
    
    ?>
    <a href="#" onClick="parent.frames[1].document.location='triage_form.php?mode=walkout_ck&<?echo $is;?>';">
    <?
    //if($needs_retriage == 'true' ) {echo "<img src='$image_dir/red_star.gif'>";}

    echo "$row[LAST_NAME], $row[FIRST_NAME]</a>";

     
     echo "  </td> 
             <td> $total_time </td>
             <td><a href='$PHPSELF?mode=delete&IND=$row[IND]'><img src='$image_dir/delete.gif'></a></td>
          </tr>
        ";
}

   echo " </TABLE> <p>";


?>
<p><center>
<input type='button' value='Main Menu' onClick="this.document.location='links.php';">
&nbsp<p>
<input type='button' value='Show Waiting' onClick='parent.frames[1].location="show_wait_walkout.php"'>
<p>
 <!-- <input type='button' value='Home' onClick="parent.location='http://wjepg';"> -->
</center>
<?

echo "</body>";
echo "<head>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";
echo "</head>";
echo "</html>";
?>

