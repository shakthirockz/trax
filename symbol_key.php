<?
include "config.php";

echo "<html><head><title>Symbol Key</title></head>";
echo "<body bgcolor 'FFFFFF' fgcolor='000000'>";

echo " <TABLE border='2' width=40%> ";
echo " <CAPTION><i>Key to lab symbols:</i></CAPTION>";
echo " <tr> <td> <i> none </i> </td> <td> <image src='$image_dir/white.jpg'> </td> ";
echo "      <td> <i> ordered </i> </td> <td> <image src='$image_dir/green.jpg'> </td> ";
echo "      <td> <i> pending </i> </td> <td> <image src='$image_dir/yellow.jpg'> </td> ";
echo "      <td> <i> complete </i> </td> <td> <image src='$image_dir/red.jpg'> </td> ";
echo " </tr>";
echo " </TABLE> <p>";

echo "&nbsp";

echo " <TABLE border='2' width=40%> ";
echo " <CAPTION><i>Key to message symbols:</i></CAPTION>";
echo "      <td> <i> Nurse </i> </td> <td> <image src='$image_dir/green_msg_mini.gif'> </td> ";
echo "      <td> <i> Doctor </i> </td> <td> <image src='$image_dir/red_msg_mini.gif'> </td> ";
echo "      <td> <i> Secretary </i> </td> <td> <image src='$image_dir/yellow_msg_mini.gif'> </td> ";
echo " </tr>";
echo " </TABLE> <p>";

?>
