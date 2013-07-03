<?
include 'util.php';
$mrn = $_GET[MRN];
   
$suc = toggle_lobby($mrn);
?>
<SCRIPT>
 window.location="/trax/show_ER_patients.php?er_select=All&lobby=true";
</script>



