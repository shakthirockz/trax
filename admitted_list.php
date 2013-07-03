<?
echo "<html><head><title>Admitted Patient List</title><head>";
echo "<body bgcolor='00CCFF' text='black' link='blue' vlink='blue'>";
echo "<p>";
include "navbar.php";

echo"<p><h3>Patients Admitted in Last 48 Hours </h3><p>";
include_once "util.php";
connect("trax");
$PHPSELF = $_SERVER[PHP_SELF];

$r = mysql_query("SELECT UNIX_TIMESTAMP(now() ) ");

while($row= mysql_fetch_array($r)  )
 {
  // echo "NOW => $row[0]<p>";
   $now = $row[0];
}

$order = $_GET[order];
if(!$order) {$order="PATIENT_LAST_NAME";}

$rn = mysql_query("SELECT UNIX_TIMESTAMP(REG_TIME ), pt_status.MRN, complaint, PMD, ADMIT_ROOM_NUM  
                     from pt_status LEFT JOIN triage_data 
                     ON pt_status.MRN = triage_data.MRN
                     WHERE CURRENT_STATUS Like '%ADMIT%' 
                     ORDER by $order  ");

echo "<center> <table border='5' width='80%' cellpadding='3' cellspacing='3'>";
echo "<tr bgcolor='88FFFF'>
       <td><a href='$PHPSELF?order=PATIENT_LAST_NAME'<b>Name</a>       </td>
       <td><a href='$PHPSELF?order=complaint'><b>Diagnosis  </a>       </td>
       <td><a href='$PHPSELF?order=PMD'><b>Admit Doctor </a>           </td> 
       <td><a href='$PHPSELF?order=ER_ROOM_NUMBER'><b>ER Room </a>        </td> 
       <td><a href='$PHPSELF?order=ADMIT_ROOM_NUM'><b>Room Number</a>  </td>
    </tr>";

while($row= mysql_fetch_array($rn)  )
 {
   if($now - $row[0] < 172800) {
       $rz = mysql_query("SELECT * FROM pt_status LEFT JOIN triage_data 
                          ON pt_status.MRN = triage_data.MRN 
                          WHERE pt_status.MRN = '$row[MRN]' 
                          ORDER BY $order ");

       while ($rew = mysql_fetch_array($rz)) {

       $pref = "<a href='/trax/patient.php?MRN=$rew[MRN]&er_select=$rew[ER_TYPE]'>";

	   echo "<tr bgcolor='FFFFFF'>
	                       <td> $pref$rew[PATIENT_LAST_NAME], $rew[PATIENT_FIRST_NAME] </a>        </td>
			       <td> $rew[complaint]        </td>
                               <td> $rew[PMD]              </td>
			       <td> $rew[ER_ROOM_NUMBER]   </td>
			       <td> $rew[ADMIT_ROOM_NUM]  </td>
		</tr>";
	}
   }
}
echo "<table></center>";

?>

