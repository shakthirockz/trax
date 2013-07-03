<?
include "util.php";
include "config.php";

if (!$mode) {$mode = "menu";}

if ($mode == 'menu')
{
   echo "<center>";
   echo "<a href='$PHPSELF?mode=order'>Order Labs</a><p>";
   echo "<a href='$PHPSELF?mode=status'>Set Lab Status</a><p>";
   echo "</center>";
   include "footer.php";
}

if ($mode == 'order')
{
   echo "<html><head><title>Lab Orders</title></head>";

   echo "<h3>Lab Orders:</h3><p>";

   $labs = array("CBC", "ELECTROLYTES", "PT/PTT & INR", "CARDIAC MARKERS", "BNP", "D-DIMER", "CMP", "AMYLASE", "LIPASE",
              "LIVER FUNCTION PANEL", "SERUM PREGNANCY TEST", "URINALYSIS", "URINE PREGNANCY TEST");
   echo "<p>";
   echo "<form action='$PHPSELF?mode=$mode'>";
   echo "<input type='hidden' name='mode' value='insert_order'>";
   $c = 0;
   echo "<center><TABLE border='3' cellpadding='3' cellspacing='3' width='30%'> <tr>";

   sort($labs);
   foreach($labs as $lab)
   {
      $c++;
      echo "<td><input type='checkbox' value='ordered' name='$lab'></td><td>$lab</td></tr>";
      //if($c%2 == 0) {echo "</tr>";}
   }
   echo "</tr></table>";
   echo "<p><input type='submit' value='ORDER'>";
   echo "</center></form>";
}

if ($mode == 'insert_order')
{  
   $sql = "INSERT INTO ORDERS "; 
 
   foreach ($HTTPVARS as $lab => $status)
   {
      if($status == 'ordered')
      {
      $sql .= "\'$lab\', ";
      } 
   }
