<?php
session_start();

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>The Wall</title>
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

      .intro {
          margin-top: 5%;
      }

      div.row.intro {
        background: beige;
      }

  </style>
</head>
<body>
  <div class="row intro">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <h1>the wall</h1>
      <p>a simple php project using mysql and twitter bootstrap.</p>
      <!-- </p>users can make posts and comment on other users posts.</p> -->
    </div>
    <div class="col-md-3"></div>
  </div>
  <div class="row forms">
    <div class="col-md-3">
      <?php
      if(isset($_SESSION['error']))
      {
        foreach($_SESSION['error'] as $name => $message)
        {
          ?>
          <p><?=$message ?></p>
          <?php
        }
      }
      elseif(isset($_SESSION['success_message']))
      {
        ?>
        <p><?=$_SESSION['success_message'] ?></p>
        <?php
      }
      ?>
   
    </div>
  <div class="col-md-3">
    <h2>Register</h2>
    <form action="process.php" method="post">
      <input type="hidden" name="action" value="register"></br>
      <input type="text" name="first_name" placeholder="First Name"></br>
      <input type="text" name="last_name" placeholder="Last Name"></br>
      <input type="text" name="email" placeholder="Email"></br>
      <input type="password" name="password" placeholder="Password"></br>
      <input type="password" name="confirm_password" placeholder="Confirm Password"> </br>
      <input type="submit" class="btn btn-success" value="Register"></br>
    </form>
  </div>
  <div class="col-md-3">
    <h2>Log In</h2>
    <form action="process.php" method="post"> <!-- //don't need enctype cuz no file -->
      <input type="hidden" name="action" value="login"> <!-- //NOTE value is now changed to login, so still goes to process.php, but this determines which process gets run and which one doesn't. -->
      <input type="text" name="email" placeholder="Email">
      <input type="password" name="password" placeholder="Password">
      <input type="submit" class="btn btn-success" value="Login">
    </form>
  </div>
  <div class="col-md-3">
    <?php
    if(isset($_SESSION['error']['message'])) 
    {
      ?>
      <p><?= $_SESSION['error']['message'] ?></p>
      <?php
    }
    ?>
  </div>
</body>
</html>
<?php
$_SESSION = array();
?>