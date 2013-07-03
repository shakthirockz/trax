<?
//foreach($_GET as $k => $v) {echo "$k => $v <br>";}
include 'util.php';
include "config.php";
 
$MRN = $_GET[MRN];
$er_select = $_GET[er_select];

echo "<html><head><title> Update Test Status.</title></head>";
echo "<body bgcolor='cyan' text='black' link='blue' vlink='green'>";

connect($database);
	
$sql = "UPDATE pt_status SET LAB_STATUS = '$_GET[lab_order]', 
                            xray_STATUS = '$_GET[xray_order]',
                             EKG_STATUS = '$_GET[EKG_order]',	
                              CT_status = '$_GET[CT_order]',
                            SONO_status = '$_GET[sono_order]'
                     WHERE MRN= $MRN";
        
   mysql_query($sql)or die ("Invalid sql query setting test order status.  MRN: $MRN");


echo "<a href='show_ER_patients.php?er_select=$er_select'>To Tracking</a>";
 
       echo "<script language='JavaScript'>
                 this.location='show_ER_patients.php?er_select=$er_select';
             </script>";

include "footer.php";         
?>
