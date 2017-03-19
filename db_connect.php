<?php

function connect()
{
    $hostname='localhost';
    $database='mtit';
    $username='root';
    $password='';
   $conn= mysql_connect($hostname, $username, $password);
    if($conn)
    {
        if(mysql_select_db($database))
        {
            return $conn;

        }
        else
        {
            die('DB Error');
            return $conn;
        }
    }
    else
    {
        die('failed'.mysqli_connect_error());
        return $conn;

    }
}

function disconnect($connection)
{
    mysql_close($connection);

}





?>