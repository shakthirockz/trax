<?
include "util.php";
include "config.php";

echo "<body bgcolor='AAAAAA' fgcolor='000000'>";
connect($database);

$mode = $_GET[mode];

if(!$mode){$mode = "new_message_form";}

if($mode == "new_message_form") {


   $sql = "SELECT * from message WHERE MRN = $_GET[MRN]";

   $result = mysql_query ($sql);
 
   while ($row = mysql_fetch_array($result)){
      $message = $row[MESSAGE];
      $target = $row[TARGET];   
   }

   $pt_name = get_name_from_MRN($_GET[MRN]);

   echo "** New message <p>Patient: $pt_name <p>";
   
   if($target == "SECY"){ echo "For: Secretary<p>";}
   else { echo "For: $target<p>";}
   
   echo "Message: $message <p>";

   $PHPSELF = $_SERVER['PHP_SELF'];
   
   echo "<form action='$PHPSELF'>
         <input type='hidden' name='mode' value='awk'>
         <input type='hidden' name='MRN' value=$_GET[MRN]>
	 <input type='hidden' name='er_select' value=$_GET[er_select]>
         <center><input type='submit' value='Delete Message'></center>
	 </form>
        ";
}

if($mode == "awk") {
    
   $sql = "DELETE FROM message WHERE MRN = '$_GET[MRN]'";

   $r = mysql_query($sql);

echo "ER_SELECT => $_GET[er_select] <p>";

   echo "<SCRIPT Language='JavaScript'>
            window.opener.location='show_ER_patients.php?er_select=$_GET[er_select]';
            window.close();
	</SCRIPT>
	";

}

if($mode == "insert_new_message") {

   $sql = "INSERT INTO message (MESSAGE, TARGET, STATUS, MRN) 
                       VALUES('$_GET[message]', '$_GET[target]', 'new', '$_GET[MRN]') ";

    echo "SQL => $sql <p>";

   $r = mysql_query($sql);


echo "ER_SELECT => $_GET[er_select]<P>";

$er_select = $_GET[er_select];

   echo "<script language='JavaScript'>
             window.location='/$doc_root/show_ER_patients.php?er_select=$_GET[er_select]';
         </script>";

}
?>   

