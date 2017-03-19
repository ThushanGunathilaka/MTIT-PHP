<?php
include_once('db_connect.php');


/* FUNCTIONS FOR Index.php*/

function printPage()
{


    echo '<html>
<head >
    <div align="center" >
    <h2 style="background:coral;color: azure"> HTML FORM CREATOR</h2>
    </div>
</head>
<body><div align="center" style="background: cadetblue">
<br>
<form name="submitForm" method="post" >
    <table>
        <tr>
            <td>Select Element</td>
            <td><select id="selectElement" name="selectElement">
                    <option value="title"  >Title</option>
                     <option value="paragraph"  >Paragraph</option>
                    <option value="text box" >Text Box</option>
                    <option value="radio button" >Radio Button </option>
                     <option value="list box" >List Box </option>
                     <option value="submit button" >Submit </option>
                     <option value="reset button" >Reset </option>
                </select>
            </td>
        </tr>
        <tr>
            <td> Element Name</td>
            <td><input  required="true" type="text" name="elementName" placeholder="Name of Element" maxlength="20"> </td>

        </tr>
        <tr>
            <td> Element Value(s)</td>
            <td><input   type="text" name="elementValue" placeholder=" (value1,value2,value3)"> </td>

        </tr>


    </table>
    <br><br>
    <table>
    <tr><input type="submit" name="AddButton" value="Add Element"></form>
          <form method="post">
          <input type="submit" name="UndoButton" value="Undo Changes">
          <input type="submit" name="CleanButton" value="Clear All">
          </form>
         <form  method="post"  action="previewCode.php" target="_blank"><input type="submit" name="PreviewButton" value="Preview Code"></form>
          </tr>
    </table>

</div>
</div>
</body>
<table>

</table>

</html>';


}



function displayComponents($previousComponents=null)
{
    if($_SESSION['previousElArray']==null)
    {
        $_SESSION['previousElArray']=array();
    }

    if (isset($_POST['AddButton'])) {
        if (isset($_POST['selectElement']) && isset($_POST['elementName']) && isset($_POST['elementValue']))
        {
            $element = $_POST['selectElement'];
            $name = $_POST['elementName'];
            $value=$_POST['elementValue'];


            if ($element == 'text box')
            {

                $result = '<br><div align="center">'.$name . ' <input type="text" placeholder="'.$value.'" name="' . $name . '" ></div>';
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);

            }
            elseif ($element == 'title')
            {
                $result = '<div align="center"> <h3 name="'.$name.'">' . $value . '</h3></div>';
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);
            }
            elseif($element=='radio button')
            {
                $result =dispatchRadioList($name) ;

                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);

            }
            elseif($element=='paragraph')
            {
                $result='<div align="center"><p name="'.$name.'">'.$_POST['elementValue'].'</p></div>';
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);
            }
            elseif($element=='list box')
            {
                $result=dispatchListBox($name);
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);

            }
            elseif($element=='reset button')
            {
                $result='<br><div align="center"><input type="reset" name="'.$name.' " value="'.$value.'"></div>';
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);

            }
            elseif($element=='submit button')
            {
                $result='<br><div align="center"><input type="submit" name="'.$name.' " value="'.$value.'"></div>';
                array_push($_SESSION['previousElArray'], $result);
                printAllComponents($_SESSION['previousElArray']);
            }


        } else
        {
            return null;
        }
    }
}

function dispatchRadioList($name=null)
{

    $values=$_POST['elementValue'];
    $array= explode(",",$values);
    $result='<div align="center"><b>'.$name.'</b><br><br>';
    foreach ($array as $item)
    {
    $result=$result.'<input type="radio" name="'.$name.'" value="'.$item.'" checked> '.$item.'<br>';
    }
    return $result.'</div>';


}
function dispatchListBox($name=null)
{
    $values=$_POST['elementValue'];
    $array= explode(",",$values);
    $result='<br><div align="center">'.$name.'<select name="'.$name.'">';
    foreach ($array as $item)
    {
        $result=$result.' <option value="'.$item.'"  >'.$item.'</option>';
    }
   return $result=$result."</select></div><br>";


}

function undoChange()
{
    if(isset($_POST['UndoButton']))
    {
        array_pop($_SESSION['previousElArray']);
        printAllComponents($_SESSION['previousElArray']);
    }

}

function cleanSession()
{
    if (isset($_POST['CleanButton']))
    {
        $_SESSION['previousElArray']=null;
        $_SESSION['previousElArray']=array();

    }
}

function printAllComponents($array=array())
{
    foreach($array as $item)
    {
        echo $item;
    }
}



/*Functions for PreviewCode.php*/

function previewCode($array=array())
{


    $output='<html><head></head><body><form method="post"';
    foreach($array as $item)
    {
       $output=$output. $item;
    }
   return str_replace('>','&gt',str_replace("<","&lt", $output.'</form></body></html>'));


}


function previewAllForms()
{
    if($conn=connect())
    {
        $query ="select name from documents";
        $results =mysql_query($query);
        $output='<h3>Saved Documents</h3><table border="1" style="width:30%" cellpadding="2px" style="background: blanchedalmond">';
        if(mysql_num_rows($results)>0)
        {
            while($row =mysql_fetch_array($results))
            {
               $output=$output.'<tr><td><b>'.$row['name'].'</b></td>
                                   <td> <form method="post" action="download.php"><input type="hidden" name="hidden_val" value="'.$row['name'].'"><input type="submit" value="Download" name="download_record"> </form>
                                   <form method="post"><input type="hidden" name="hidden_val" value="'.$row['name'].'"><input type="submit" value="Remove" name="del_record"> </form> </td>
                              </tr> ' ;
            }
            return $output.'</table>';
        }
        else
        {
            return '<tr><td>No Saved HTML Documents Found!</td></tr></table>';
        }


    }
}
function displaySaveButton()
{
    echo '
            <div  align="right" style="background: cadetblue">
            <form method="post">

             <input type="submit" name="homeButton" value="< Back">

            </form>
            <form method="post"><br>
            <input type="text" required="true" maxlength="20" name="documentName" placeholder="Document Name">
            <input type="submit" name="saveButton" value="Save">

             </form>

              </div>';

}



function saveForm()
{

    if(isset($_POST['saveButton']))
    {$conn=connect();
        if ($conn)
        {
            $output = previewCode($_SESSION['previousElArray']);
            $docName=$_POST['documentName'];
            $query="insert into documents(name,value) values('".$docName."','".$output."');";

            if(mysql_query($query))
            {
                echo '<div align="center" style="background: lawngreen">Your Document Successfully Saved</div>';
                header("Refresh:0");
                disconnect($conn);
            }
            else
            {
                echo '<div align="center" style="background: indianred;color: azure">Erro :Input Value Error</div>';

                disconnect($conn);
            }

        }
        else
        {
            header("Refresh:0");
            disconnect($conn);
            echo '<div align="center" style="background: indianred">Error :Cannot Connect Database</div>';
        }
    }

}

function closeWindow()
{
    if(isset($_POST['homeButton']))
    {
        echo "<script>window.close();</script>";
      //  header("Location: http://localhost:8080/MTIT_Assignment/");



    }


}
function deleteForm()
{
    if(isset($_POST['del_record']))
    {


        $conn=connect();
        if($conn)
        {
            $deleteKey= $_POST['hidden_val'];
            $query="delete from documents where name='".$deleteKey."'";

            if(mysql_query($query))
            {
                header("Refresh:0;url=previewCode.php");

            }
            else
            {
                echo '<div align="center" style="background: indianred;color: azure">Erro :Could not remove document</div>';
                disconnect($conn);

            }



        }
        else
        {
            disconnect($conn);

        }

    }

}
function downloadForm()
{
    if(isset($_POST['download_record']))
    {
        $content =getSourceCode();
        if($content!=null)
        {
            $handle = fopen("file.txt", "w");
            echo fwrite($handle, $content);
            echo fclose($handle);

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename('file.txt'));
            header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize('file.txt'));
            readfile('file.txt');
            exit;
        }
        else
        {
            echo 'Cannot download file!';
        }
    }
}


function getSourceCode()
{
    $conn=connect();
    if($conn)
    {
        $name=$_POST['hidden_val'];
        $query="select name,value from documents where name='".$name."'";
        $results=mysql_query($query);
        $output='';

        if(mysql_num_rows($results)>0)
        {
            while($row =mysql_fetch_array($results))
            {
                $temp1=str_replace('&lt','<',$row['value']);
                $temp2=str_replace('&gt','>',$temp1);
                $output=$output.$row['name']."  ".$temp2;
            }
            return $output." --created on --".date("Y/m/d")."--";
        }
        else
        {
            return null;

        }

    }
    else
    {
        return null;
    }

}




?>