<?php
session_start(); 
require_once('connection.php');

if(!isset($_SESSION['user_id']))
{
    header('Location: index.php');
    exit;    
}

?>
<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title>Wall</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

       <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
 
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">

    <style>
      body {
        font-family: 'Source Sans Pro', sans-serif;
        color: #39454b;
      }

      input {
        margin-top: 10px;
        border-radius: 5px;
      }

      textarea {
        border-radius: 5px;
      }

      .intro {
        margin-top: 5%;
      }

      .float {
        float: right;
      }
      h1 {
        width: 400px;
      }

      h3 {
        font-size: 16px;
      }

      h1, h2{
        display: inline-block;
        font-size: 20px;
      }

      .wrapper {
        margin-top: 5%;
      }

      .logout {
        margin-left: 10px;
      }

      .btn {
        font-size: 10px;
        padding: 2px;

      }

      .comment {
        margin-left: 50px;
      }

      .header {
        background: beige;
        padding: 10px 30px 10px 30px;
        margin-top: 5%;
      }

  </style>
        <style type="text/css">

        </style>
</head>
<body>
    <div class="row">
     
            
                <?php    
                $query = "SELECT first_name, last_name, email
                            FROM users
                           WHERE id = ".$_GET['id'];
                $result = mysqli_query($connection, $query); 
                $row = mysqli_fetch_assoc($result); 

                if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) 
                {
                    ?>
                    <div class="header">
                        <h1>welcome to the wall, <strong><?= $row['first_name'].' '.$row['last_name'] ?></strong></h1> 
                        <h2 class="float logout"><a href="process.php?logout=1">Logout</a></h2>
                        <h2 class="float"><?= $row['email']?></h2>
                    </div>
                       <div class="col-md-1"></div>
        <div class="col-md-10">
                    <?php                                                                        
                }
                else
                {
                    header('Location: index.php');
                    exit;
                }
                ?>
                <!--<div class="header">
                        <h1><?= $row['first_name'].' '.$row['last_name']?></h1> 
                        <h2><?= $row['email']?></h2>
                    </div>  -->
            <div class="wrapper">
                <div class="message">
                    <form action="process.php" method="post">
                        <input type="hidden" name="post_message">
                        <textarea cols="50" rows="3" name="message"></textarea><br>
                        <input type="submit" class="btn btn-success" value="Post a message">
                    </form>
                </div> <!-- end class message -->
                <?php
                $query ="SELECT users.first_name, users.last_name, messages.message, messages.created_at, 
                                messages.id as message_id, comments.user_id, comments.message_id as comment_id,
                                comments.comment, comments.created_at as comment_created
                            FROM users
                            JOIN messages ON users.id = messages.user_id
                       LEFT JOIN comments ON comments.message_id = messages.id";
                $result = mysqli_query($connection, $query); 
                $data = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $data[] = $row;
                } 
                $last_message = '';
                foreach($data as $current_row)
                {
                    if($last_message != $current_row['message_id'])
                    {
                      $date = date('M d, Y');
                        $last_message = $current_row['message_id'];
                        ?>
                        <h3><strong><?= $current_row['first_name']?> <?= $current_row['last_name']?> - <?= date('m/d', strtotime($current_row['created_at']))?></strong></h3>
                        <p><?= $current_row['message']?></p>
                        <?php
                    } //end iff
                    if($current_row['message_id'] == $current_row['comment_id']) 
                    {
                        ?>
                        <?php
                        $query2 = "SELECT users.id as message_user, comments.user_id as comment_user
                                     FROM users
                                     JOIN comments ON users.id = comments.user_id";
                        $comm = mysqli_query($connection, $query2); 
                        $comm_row = mysqli_fetch_assoc($comm);
                        if($comm_row['message_user'] == $comm_row['comment_user'])
                        {
                            ?>
                            <h3 class="comment"><strong><?= $current_row['first_name']?> <?= $current_row['last_name']?> - <?= $current_row['created_at']?></strong></h3>
                            <p class="comment"><?= $current_row['comment']?></p>
                            <?php
                        } //end comm_row
                        
                    } //end if current row
                        ?>
                    <div class="comment">
                        <form action="process.php" method="post">
                            <input type="hidden" name="post_comment">
                            <input type="hidden" name="message_id" value="<?= $current_row['message_id']; ?>">
                            <textarea cols="35" rows="2" name="comment"></textarea><br>
                            <input type="submit" class="btn btn-success" value="Post a comment">
                        </form>
                    </div> <!-- end class comment -->
                    <?php  
                } //end foreach
                    ?> 
            </div> <!-- end class wrapper -->
        </div> <!-- end md 10 -->
        <div class="col-md-1"></div>
    </div> <!-- end class row -->
</body>
</html>