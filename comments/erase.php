<?
$filename = "data.txt";
if(is_writable($filename)){
   if(!$handle = fopen($filename, 'w')){echo "can not open $filename. exiting."; exit();}
   if(fwrite($handle, "") === FALSE) {echo "can not write to $filename. exiting."; exit();}
echo "ERASED.";
fclose($handle);
}
else {echo "The file $filename is not writable <p>";}
?>
<script>
   window.location="/trax/comments/index.php";
</script>
