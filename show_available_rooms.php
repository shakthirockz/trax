<?
include 'util.php';
include "config.php";

echo "<html>";
echo "<head>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";
echo "</head>";

echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";
echo "<b>Available Rooms</b>";

//foreach($_GET as $k => $v){echo "$k => $v <br> ";}
$er_select = $_GET[er_select];

$areas = array();
connect("trax_test");

if(!$er_select) {$er_select = "All";}

if($er_select != "All") 
{
   $area = $er_select;
   make_available_room_list($area);
}

else
{
   $result = mysql_query("SELECT * FROM AREAS");
   while($row=mysql_fetch_array($result)) {array_push($areas, $row[NAME]);}
   
   foreach($areas as $area)
   {
      make_available_room_list($area);
   }
}

function make_available_room_list($area)
{
  $fixed_area = str_replace("_", " ", $area);
  $sql = "SELECT NAME FROM ROOMS
          WHERE ER_TYPE='$area' 
	  AND (STATUS IS NULL OR STATUS = '') 
	  ORDER BY NAME";

   $result = mysql_query($sql);

   echo  "<center><table border='1' width='70%'>
          <CAPTION> $fixed_area </CAPTION>
          <tr><td>"; // <ul compact>
         //";
      
   while ($row=mysql_fetch_array($result))
   {
     //echo " <li> $row[NAME] </li> ";
     echo "$row[NAME] <br>";
   }
   echo "</ul> </td></tr></table></center><p>";
}    


print "</TABLE>";

echo "</body>";
echo "<head>";
echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
echo "<META HTTP-EQUIV='Refresh' CONTENT='60'>";
echo "</head>";
echo "</html>";

?>


