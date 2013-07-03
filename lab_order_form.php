<?
include "util.php";
include "config.php";
$MRN = $_GET[MRN];
$er_select = $_GET[er_select];

$table ="pt_status"; //the table to search

echo "<html>";
echo "<head><title>Order Labs, X-rays, EKG's and CT's</title></head>";

echo "<body bgcolor='cyan' text='black' link='blue' vlink='green'>";

connect($database);

$sql = "SELECT * FROM $table WHERE MRN = '$MRN'";
       
$result = mysql_query("$sql");

$row = mysql_fetch_array ($result);

$MRN= $row[MRN];
$ER_MD = $row[ER_MD];

echo "<p> <h4>Order Lab, X-ray, Specials for $row[PATIENT_FIRST_NAME] $row[PATIENT_LAST_NAME] </h4><p>";

echo "<table border='1' width= 70%>";
echo "   <form action='/trax/update_test_status.php'>";
echo "      <input type='hidden' name='caller' value='$caller'>";
echo "      <input type='hidden' name='MRN' value='$row[MRN]'>";
echo " <tr> <td> Lab </td>";

echo "	     <td>Ordered:  <input type='radio' name='lab_order' value='ordered' ";
              if ($row[LAB_STATUS] == 'ordered') {echo "checked";}
echo "       ></td>";

echo "	     <td>Pending:  <input type='radio' name='lab_order' value='pending'";
              if ($row[LAB_STATUS] == 'pending') {echo "checked";}
echo "       > </td>";

echo "	     <td>Complete: <input type='radio' name='lab_order' value='complete'";
              if ($row[LAB_STATUS] == 'complete') {echo "checked";}
echo "       ></td>";

echo "	     <td>none:     <input type='radio' name='lab_order' value='none'";
              if ($row[LAB_STATUS] == 'none') {echo "checked";}
echo "       </td>";
echo "</tr>";

echo " <tr> <td> X-Ray </td>";
echo "	     <td>Ordered:   <input type='radio' name='xray_order' value='ordered' ";
              if ($row[xray_STATUS] == 'ordered') {echo "checked";}
echo "       ></td>";

echo "	     <td>Pending:   <input type='radio' name='xray_order' value='pending' ";
              if ($row[xray_STATUS] == 'pending') {echo "checked";}
echo "       > </td>";

echo "	     <td>Complete:  <input type='radio' name='xray_order' value='complete' ";
              if ($row[xray_STATUS] == 'complete') {echo "checked";}
echo "       ></td>";

echo "	     <td>none:      <input type='radio' name='xray_order' value='none' ";
              if ($row[xray_STATUS] == 'none') {echo "checked";}
echo "       ></td>";
echo "</tr>";


echo " <tr> <td> EKG </td>";
echo "	     <td>Ordered:   <input type='radio' name='EKG_order' value='ordered'";
              if ($row[EKG_STATUS] == 'ordered') {echo "checked";}
echo "      ></td>";

echo "	     <td>Pending:   <input type='radio' name='EKG_order' value='pending'";
              if ($row[EKG_STATUS] == 'pending') {echo "checked";}
echo "      ></td>";

echo "	     <td>Complete:  <input type='radio' name='EKG_order' value='complete'";
              if ($row[EKG_STATUS] == 'complete') {echo "checked";}
echo "      ></td>";

echo "	     <td>none:      <input type='radio' name='EKG_order' value='none'";
              if ($row[EKG_STATUS] == 'none') {echo "checked";}
echo "      ></td>";

echo "</tr>";


echo " <tr> <td> CT </td>";
echo "	     <td>Ordered:   <input type='radio' name='CT_order' value='ordered' ";
              if ($row[CT_STATUS] == 'ordered') {echo "checked";}
echo "      ></td>";


echo "	     <td>Pending:   <input type='radio' name='CT_order' value='pending' ";
              if ($row[CT_STATUS] == 'pending') {echo "checked";}
echo "      ></td>";


echo "	     <td>Complete:  <input type='radio' name='CT_order' value='complete' ";
              if ($row[CT_STATUS] == 'complete') {echo "checked";}
echo "      ></td>";


echo "	     <td>none:      <input type='radio' name='CT_order' value='none' ";
              if ($row[CT_STATUS] == 'none') {echo "checked";}
echo "      ></td>";

echo "</tr>";


echo " <tr><td> Ultrasound </td>";
echo "	     <td>Ordered:   <input type='radio' name='sono_order' value='ordered' ";
              if ($row[SONO_STATUS] == 'ordered') {echo "checked";}
echo "      ></td>";

echo "	     <td>Pending:   <input type='radio' name='sono_order' value='pending' ";
              if ($row[SONO_STATUS] == 'pending') {echo "checked";}
echo "      ></td>";

echo "	     <td>Complete:  <input type='radio' name='sono_order' value='complete' ";
              if ($row[SONO_STATUS] == 'ordered') {echo "checked";}
echo "      ></td>";

echo "	     <td>none:      <input type='radio' name='sono_order' value='none'";
              if ($row[SONO_STATUS] == 'none') {echo "checked";}
echo "      ></td>";

echo "</tr>";

echo "</table><p>";

echo "<input type='hidden' name='er_select' value='$er_select'>";
echo "  <input type='submit' value= 'Update lab, X-ray and Special Study status'> ";
echo "</form>";

//include "phone/call_pmd.php";
include "footer.php";

?>
