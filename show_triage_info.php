<?
echo "<html><head><title>Triage Info</title><head>";
echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";

include_once "util.php";
connect("trax");

$result = mysql_query("SELECT * FROM triage_data WHERE MRN = '$_GET[MRN]' ");

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
   echo "
        <table width='90%' border='1' cellpadding='3' cellspacing='3'>
        <caption><b> Triage info for $row[first_name] $row[last_name]  </caption>
       ";
   
   foreach ($row as $k => $v)
   {
      if($k == "IND" ) {continue;}
      if($k == "pox") {$k = "pulse ox";}
      $k = str_replace("_", " ", $k);
      $k = strtoupper($k);
      echo "<tr><td><b>$k </td> <td> $v </b></td></tr> ";
   }
   
  echo "</table>";
}
?>

