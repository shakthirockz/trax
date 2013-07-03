
<html> <head><title>Trax_RT System</title></head>
<body bgcolor='cyan' text='black' link='blue' vlink='green'> 
<h2>Trax_RT System </h2><p><b><p><hr></hr><p>


<? 
include "config.php"; 
?>

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/show_ER_patients.php?er_select=All';">Tracking</a> <p>

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/triage_form.php?mode=form';">Triage Patients</a> <p>

<!--
<a href="#" onClick="window.open('http://172.20.80.39/')">Patient Sign In</a> <p>
-->

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/show_wait_walkout.php';">Triage View</a> <p>

<!--
<a href="#" onClick="parent.parent.frames[1].document.location='/<?echo $doc_root;?>/RN_assign.php';">Nursing Assignments</a> <p>
-->

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/admitted_list.php';">Admitted Patients</a> <p>

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/admin.php';">Administer</a> <p>

<a href='#' onClick='window.open("/_trax_test/symbol_key.php","Symbol_Key","width=400,height=200,toolbar=no,directories=no,menubar=no,status=no,left=100,top=100");'>Symbol Key</a><p>

<!--"Symbol Key", "width=200,height=200,toolbar=no,directories=no,menubar=no,status=no,left=100,top=100");'>Symbol Key</a> <p>-->
<a href="#" onClick="parent.comments.document.location='/<?echo $doc_root;?>/comments/index.php';">Chat</a> <p>

<a href="#" onClick="parent.mainFrame.document.location='/<?echo $doc_root;?>/report.php';">Generate Reports</a> <p>
</center>

<hr></hr>

</body>
</html>



