<?php

  require '../db_conn.php';

  session_start();
      /*
        $id = 0;
        

        if(isset($_POST['title'])) 
        {
          

          $id = $_POST['id'];
          $title = $_POST['title'];

          $sql = "UPDATE todos SET title='$title' WHERE id=$id";
          $stmt = $conn->prepare($sql);
          $stmt->execute(['title' => $title, 'id' => $id]);
          $_SESSION['message'] = "Task updated!"; 
          header('location: ../index.php');
        }
        */
      
       if(isset($_GET['edit-task']))//Note the GET parameter now has a Hyphen instead of an underscore
        {
          $e_id = $_GET['edit-task'];
        }

        if(isset($_POST['edit_task']))
        {
          $edit_task = $_POST['title'];

          $query = "UPDATE todos SET title = '$edit_task' WHERE id = $e_id";
          $stmt = $conn->prepare($query);
          $stmt->execute(['title' => $title, 'id' => $id]);

          if(!$stmt)
          {
            die("Failed");
          }
          else
          {
            header("Location: ../index.php?update");
          }
        }
       
        

        /*
        if(isset($_GET['update']))
        {
          $id = $_GET['update'];  
        }

        if(isset($_POST['update']))
        {
          $update = $_POST['title'];

          $sql = 'UPDATE todos SET title = :title WHERE id = :id';
          $stmt = $conn->prepare($sql);
          $stmt->execute(['title' => $title, 'id' => $id]);
          echo 'Task Updated';

          if(!$stmt)
          {
            die("Failed");
          }
          else
          {
            header("Location: index.php");
            exit();
          }
        }
        */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="../css/style.css">
    <!--Font awsomw CDN for the Delete icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
<body>
  <!--////////////////////This is the update section////////////////////////-->
  <div class="main-section" id="add-section">
    <div class="add-section"  id="add-section">
              <form action="" method="POST" autocomplete="off">
                 <?php 

                    
                    $query = ("SELECT * FROM todos WHERE id=$e_id");
                    $result = $conn->query($query);
                    $data = $result->fetch(PDO::FETCH_ASSOC);//fetches the data which will be edited
          
                    //This is the error checking code
                    if(isset($_GET['mess']) && $_GET['mess'] == 'error')
                    { ?>
                      <div id="demotext">Update Task</div>
                        <input type="text" name="title"
                        value="" style="border-color: #ff6666" placeholder="Cannot Leave Field Empty, Please Add A Task!" />

                         <center><small><img src="https://img.icons8.com/color/48/000000/calendar.png" height=30 width=30/><br/><b>Edit Due Date:</b></small></center>

                      <input class="form-control" type="date" name="ddate" placeholder="Due date" required="Please select a due date!">

                      <button type="submit">Add &nbsp; <span>&#43;</span></button>
                      <!--When there are no errors, the code below is served-->
                     <?php }else{ ?>
                      <div id="demotext">Update Task</div>
                      <input type="text" 
                             name="title" 
                             value="<?php echo $data['title']; ?>"
                             placeholder="Update your list items..." />

                      <button type="submit" name="edit_task">UPDATE</button>
                      
                     <?php } ?>
                  </form>
           </div>
         </div>
    <!--/////////////////////////Update section ends here/////////////////////-->
</body>
</html>
