
<?php
include "config.php";
include "util.php";

$orig_first_name = $_GET[orig_first_name];
$orig_last_name  = $_GET[orig_last_name];

$first_name = $_GET[first_name];
$last_name  = $_GET[last_name];

$MRN = $_GET[MRN];
$complaint_1 = $_GET[complaint_1];
$complaint_2 = $_GET[complaint_2];

$acuity = $_GET[acuity];
$peds = $_GET[peds];


echo " <html><head><title>Patient Triaged </title>
       <body bgcolor='c0c0c0' text='000000' link='0000ff' alink='00ffff' vlink='5c3317'> <h2></h2><p>
     ";

foreach($_GET as $k => $v){echo "$k => $v <br> ";}


//allows for either select or text box input
if ($_GET[complaint_2]) {
       $complaint = $_GET[complaint_2];
       }
       else {
           $complaint = $_GET[complaint_1];
           }

connect ($database);

/*Insert into Database*/ 

/* By definition lobby patients:
Have status = 'Waiting for Room'
Have room = 'Lobby'
*/

if($_GET[er_type] == 'Lobby' || $_GET[er_type] == 'LOBBY'){
   $status= 'Waiting for room';
   $location= 'Lobby';
}
else{$status="READY";
     $location = $_GET[room];
    }
     

check_for_duplicate($MRN);

//allow null or missing MRN
if ($MRN == '000' || $MRN == '') {$MRN = time();}

$sql = "INSERT INTO triage_data (
                MRN,
		first_name,
		last_name,
		age,
		sex,
		complaint,
		temp,
		SBP,
		DBP,
		pulse,
		resp,
		pox,
		glu,
		pain_scale,
		pmh,
		meds,
		allergies,
		hx,
		acuity,
		LAST_TRIAGE ) 
	VALUES ('$_GET[MRN]',
	        '$_GET[first_name]',
	        '$_GET[last_name]',
	        '$_GET[age]',
	        '$_GET[sex]',
	        '$complaint',
	        '$_GET[temp]',
	        '$_GET[SBP]',
	        '$_GET[DBP]',
	        '$_GET[pulse]',
	        '$_GET[resp]',
	        '$_GET[pox]',
		'$_GET[glu]',
		'$_GET[pain_scale]',
	        '$_GET[pmh]',
	        '$_GET[meds]',
	        '$_GET[allergies]',
	        '$_GET[hx]',
	        '$_GET[acuity]',
                '$timestamp'
		) ";
	        
//echo "SQL => $sql <p>";
			 	
$result = mysql_query($sql) or die ("From enter_triage_data: Invalid sql query loading triage data. :$sql");

$stamp_result = STAMP($_GET[MRN], "TRIAGE_COMPLETE", "no");

$sql = "INSERT INTO pt_status(PATIENT_LAST_NAME,
                             PATIENT_FIRST_NAME,
                             MRN,
                             ER_TYPE,
                             ER_ROOM_NUMBER,
			     CURRENT_STATUS,
			     REG_TIME,
                             LAB_STATUS,
			     EKG_STATUS,
			     xray_STATUS,
			     CT_STATUS,
			     SONO_STATUS
			    )
                    VALUES ('$_GET[last_name]',
                            '$_GET[first_name]',
                            '$_GET[MRN]', 
                            '$_GET[er_type]',
			    '$location',
			    '$status',
			    now(),
 			    'none', 'none', 'none', 'none', 'none'
			   )";
   
$result = mysql_query($sql) or die ("From enter_triage_data: Invalid sql query initalizing pt_status.<p> $sql"); 

if($location != "Lobby") {

   $sql = "UPDATE ROOMS SET STATUS = 'OCCUPIED', MRN = '$_GET[MRN]'
           WHERE (ER_TYPE = '$_GET[er_type]' AND NAME = '$_GET[room]') ";

   
   $r = mysql_query($sql) or die ("Unable to assign patient $_GET[last_name] to area: $_GET[er_type] and room: 
                                  $_GET[room].  Perhaps you enetered them incorrectly? <p> $sql<p>");

}

echo "LOCATION => $location <br>";
if($location == "Lobby"){
  
  $sql = "INSERT INTO lobby (MRN, last_call_time) VALUES( '$_GET[MRN]', now() )";
  echo "SQL => $sql <p>";
  $r = mysql_query($sql) or die("Error entering patient in lobby table: $sql <p>");
}


/*                
$sql = "UPDATE pt_status SET LAB_STATUS ='none',
                         EKG_STATUS='none', 
                         xray_STATUS='none', 
                         CT_STATUS='none',
                         SONO_STATUS = 'none',
                         ER_ROOM_NUMBER = 'LOBBY',
                         REG_TIME = 'now()',
                         CURRENT_STATUS = 'Waiting for room', 
          WHERE MRN = '$MRN' ";
echo "SQL 2 => $sql <p>";

$result = mysql_query($sql) or die ("From enter_triage_data: Invalid sql query initalizing pt_status null values");  
*/

/*
$result = mysql_query("UPDATE sign_in SET TRIAGED ='yes', MRN = '$_GET[MRN]' WHERE IND='$_GET[signin_ind]' ") or die("Error updating sign_in status to triaged");

								     // echo "UPDATE sign_in SET TRIAGED ='yes',
                                                                     // MRN = '$_GET[MRN]'
								     // WHERE IND='$_GET[signin_ind]' ";
*/


print "<p>The patient $first_name $last_name has been entered into the trax system.<p>";

print "<hr> </hr>";

function check_for_duplicate($MRN) 
{
   $sql = "SELECT * from pt_status WHERE MRN='$MRN'";
   $result = mysql_query($sql);
   $found = mysql_fetch_array($result);
   $found = $found[MRN];
   
   if ($found == "") 
   {
    $sql = "SELECT * from triage_data WHERE MRN='$MRN'";  
    $result = mysql_query($sql);
    $found = mysql_fetch_array($result);
    $found = $found[MRN];  
    if ($found == "") {return;}
   }
      else{
         print "There is already a patient with medical record # $MRN.<p>";
         print "Please check the number and <a href='triage_form.php'>click here</a> to try again.";
         exit;
         }
   }  

echo "<script language='JavaScript'>
         this.location='/$doc_root/triage_form.php';
      </script>";

?>
</body></html>


