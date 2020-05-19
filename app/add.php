<?php

session_start();

require '../db_conn.php';

if(isset($_POST['title']))
{
    
    $title = $_POST['title'];
    $date_time = date('l dS F\, Y');
    $ddate = date('l dS F\, Y', strtotime($_POST['ddate']));
    $user_id=$_SESSION['id'];

    if(empty($title)){
        header("Location: ../index.php?mess=error");
    }
    else 
    {
        $stmt = $conn->prepare("INSERT INTO todos(title, date_time, ddate, user_id) VALUE(?,?,?,?)");
        $res = $stmt->execute([$title, $date_time, $ddate, $user_id]);

        if($res)
        {
            header("Location: ../index.php?mess=success"); 
        }
        else 
        {
            header("Location: ../index.php");
        }

        $conn = null;
        exit();
    }
}
else 
{
    header("Location: ../index.php?mess=error");
}