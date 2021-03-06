<?php 
  require 'db_conn.php';
  session_start();

  //This is an Off-check php script that restrict you from serving the index page without Login authentication 
  if(!isset($_SESSION['username']))
  {
    header("Location: login.php");
  }
  
?>

<?php

  //fetching recods to be updated 
  if (isset($_GET['edit'])) 
  {
    
    $id = $_GET['edit'];
    $query = "SELECT * FROM todos WHERE id=$id";
    $record = $conn->query($query);
    
  }

  if (@count($record) == 1 ) 
  {
    $n = $record->fetch(PDO::FETCH_ASSOC);
    $title = $n['title'];
    $id = $n['id'];
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--Font awsomw CDN for the Delete icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    

</head>
<body>
    <div class="main-section" id="add-section"> 
      <div class="todo">
            <div id="demotext"><img src="img/user.png" class="user"/><br>User Account: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
            </div>
            <br>
        
      
      <center>
        <p>
          <a href="resetpassword.php" class="btn btn-warning">Reset Password</a>
          <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </p>
      </center>

      <?php if (isset($_SESSION['message'])): ?>
        <div class="msg">
          <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
          ?>
        </div>
      <?php endif ?>

       <div class="add-section"  id="add-section">
          <form action="app/add.php" method="POST" autocomplete="off">
              <!--Holds the value of the selected hidden id row-->
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
            <?php 

            //This is the error checking code
            if(isset($_GET['mess']) && $_GET['mess'] == 'error')
            { ?>
                <div id="demotext">To Do List</div>
                <input type="text" name="title"
                value="<?php echo $title; ?>" style="border-color: #ff6666" placeholder="Cannot Leave Field Empty, Please Add A Task!" />

                 <center><small><img src="https://img.icons8.com/color/48/000000/calendar.png" height=30 width=30/><br/><b>Add Due date:</b></small></center>

              <input class="form-control" type="date" name="ddate" placeholder="Due date" required="Please select a due date!">

              <button type="submit">Add &nbsp; <span>&#43;</span></button>
              <!--When there are no errors, the code below is served-->
             <?php }else{ ?>
              <div id="demotext">To Do List</div>
              <input type="text" 
                     name="title" 
                     value=""
                     placeholder="Add tasks to your list items..." />

              <center><small><img src="https://img.icons8.com/color/48/000000/calendar.png" height=40 width=40/><br/><div id="demotext">Add Due Date:</div></small></center>

              <input class="form-control" type="date" name="ddate" placeholder="Due date" required="Please add a due date!">

              <button type="submit">ADD &nbsp; <span>&#43;</span></button>
              
             <?php } ?>
          </form>
       </div>



<!--When the Task list is empty code, starts here-->     
       <?php 
         
          $user_id = $_SESSION['id'];
          $todos = $conn->query("SELECT * FROM todos WHERE user_id = $user_id ORDER BY id DESC");

       ?>
       <div class="show-todo-section">
            <?php if($todos->rowCount() <= 0){ 
                
              ?>

                <div class="todo-item">
                    <div class="empty">
                        <img src="img/notebook.jpg" width="100%" />
                        <img src="img/Ellipsis.gif" width="80px">
                    </div>
                </div>
            <?php } ?>
<!--When the Task list is empty code, ends here-->

<!--When the Task list is NOT empty code, starts here-->
            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item" id="todo-item">
                    <!--Delete button-->
                    <span id="<?php echo $todo['id']; ?>"
                          class="remove-to-do"><i style="font-size:24px" class="fa">&#xf014;</i></span>

                    <a href="app/edit.php?edit-task=<?php echo $todo['id']; ?>"><i style="font-size:24px" class="fa">&#xf044;</i></a>

                    <!-- /////////// This anchor tag takes us to the edit section //////////-->

                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $todo['title']; ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title']; ?></h2>
                    <?php } ?>
                    <br>
                    <small>Created: <?php echo $todo['date_time']; ?></small> <br>
                    <small><b><u>Due date: <?php echo $todo['ddate']; ?></b></u></small>
                </div>
            <?php } ?>
       </div>
    </div>

    

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php",              //Deleting AJAX script
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('app/check.php',               //Task Done AJAX script
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>