
<?
include_once "util.php";
include "config.php";

connect($database);
echo "<center>";

echo "<a href='show_ER_patients.php?er_select=All&lobby=true'>Lobby</a> &nbsp ";

$result = mysql_query("SELECT * FROM AREAS");

while ($row = mysql_fetch_array($result))
{
   $fixed_name = str_replace("_", " ", $row[NAME]);
   echo "<a href='show_ER_patients.php?er_select=$row[NAME]'>$fixed_name</a> &nbsp ";
}

echo "<a href='show_ER_patients.php?er_select=All'>All</a> &nbsp ";

echo "<hr></hr> </center>";
?>
