<?php
include_once('FunctionHandler.php');
   session_start();
  $code=  previewCode( $_SESSION['previousElArray']);
$allForms=previewAllForms();

    echo '<div align="center" style="background: coral;color: azure"><h2>SOURCE CODE</h2></div><br>';
    echo '<div align="center">'.$code.'</div><br>';


displaySaveButton();
saveForm();
closeWindow();

echo $allForms;
 deleteForm();
?>