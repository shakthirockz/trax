<?

switch $mode
{
	case self_reg:
            self_reg();
            break;
            
        case triage:
            triage();
            break;
          
        case sign_in:
            sign_in($name);
            break;
}

function sign_in($name)
{
  open_connection();
  
  $timestamp = date("Y-m-d G:i:s");
  
  $sql = "INSERT INTO self_reg $name $timestamp";
  
  $suc = mysql_query($sql);
  
  echo "<p>&nbsp<p> <center><h2><i> Thank you. <c/enter></h2></i>";


function triage ()
{
   include triage.php;
}


function self_reg()
{
   include self_reg_form.php;
}


?>







