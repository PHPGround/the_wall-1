<?php
session_start();
require_once('connection.php'); 

function logout()
{
        $_SESSION = array(); 
        session_destroy(); 
} // LOGOUT FUNCTION
                                
function register($connection, $post)  
{               
        foreach ($post as $name => $value) 
        {
                if(empty($value))
                {
                        $_SESSION['error'][$name] = "sorry, " . $name . " cannot be blank";
                }
                else
                {
                        switch ($name) {
                                case 'first_name':
                                case 'last_name':
                                        if(is_numeric($value))
                                        {
                                                $_SESSION['error'][$name] = $name . ' cannot contain numbers';
                                        }
                                break;
                                case 'email':
                                        if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                                        {
                                                $_SESSION['error'][$name] = $name . ' is not a valid email';
                                        }
                                break;
                                case 'password':
                                        $password = $value; //keeptrack of password they've entered in
                                        if(strlen($value) < 5)
                                        {
                                                $_SESSION['error'][$name] = $name . ' must be greater than 5 characters';
                                        }
                                break;
                                case 'confirm_password':
                                        if($password != $value) //check that the 2 are equal to each other
                                        {
                                                $_SESSION['error'][$name] = 'Passwords do not match'; //if not equal send back error
                                        }
                                break;

                        
                        }//END SWITCH
                }//END ELSE
            }//END REGISTER FOR EACH     
        if(!isset($_SESSION['error']))
    {
            $_SESSION['success_message'] = "Congratulations you are now a member!"; 
            $salt = bin2hex(openssl_random_pseudo_bytes(22)); 
            $hash = crypt($post['password'], $salt); 
            $query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
                              VALUES('".$post['first_name']."', '".$post['last_name']."', '".$post['email']."', '".$hash."', NOW(), NOW())";                                                            //passoword     //v. created above
                            
            mysqli_query($connection, $query);
            $user_id = mysqli_insert_id($connection);
            $_SESSION['user_id'] = $user_id;
            header('Location: wall.php?id='.$_SESSION['user_id']);
            exit;
    } //END ERROR ISSET
}//END REGISTER FUNCTION
function message_post($connection, $post)
{  
    if(empty($post))
    { 
        $_SESSION['error']['message'] = "Sorry, you can't post nothing.";
        header('Location: wall.php?id='.$_SESSION['user_id']);
        exit;
    }
    else
    {
        $query = "INSERT INTO messages (user_id, message, created_at, updated_at)
                    VALUES (".$_SESSION['user_id'].",'".$post."', NOW(), NOW())";
        mysqli_query($connection, $query);
        $user_message = mysqli_insert_id($connection);
        $_SESSION['user_message'] = $user_message;
        header('Location: wall.php?id='.$_SESSION['user_id']);
        exit;
    }         
}//END MESSAGE_POST FUNTION

function comment_post($connection, $post)
{ 
    if(empty($post))
        { 
            $_SESSION['error']['message'] = "Sorry, you can't post nothing.";
            // header('Location: wall.php?id='.$_SESSION['user_id']);
            exit;
        }
        else
        {
            $query = "INSERT INTO comments (user_id, message_id, comment, created_at, updated_at)
                        VALUES (".$_SESSION['user_id'].",".$post['message_id'].",'".$post['comment']."', NOW(), NOW())";
            mysqli_query($connection, $query);
            // var_dump($query);
            $user_message = mysqli_insert_id($connection);
            $_SESSION['user_message'] = $user_message;
            header('Location: wall.php?id='.$_SESSION['user_id']);
            exit;
        }
}//END COMMONET_POST FUNTION
function login($connection, $post)
{ 
        if(empty($post['email']) || empty($post['password'])) 
        {
                $_SESSION['error']['message'] = "Email or Password cannot be blank"; 
        }
        else
        {
                $query = "SELECT id, password
                                  FROM users
                                  WHERE email = '".$post['email']."'"; 
                $result = mysqli_query($connection, $query); 
                $row = mysqli_fetch_assoc($result);

                if(empty($row)) // if the row is empty
                {
                        $_SESSION['error']['message'] = 'Could not find Email in database'; //send this message
                }
                else //if there is an email continue to check for errors.
                {
                        if(crypt($post['password'], $row['password']) != $row['password']) //check if the posted password and the hashed pw in db matches the hash ps in db. seems redundant, but how it's done.
                        {
                                $_SESSION['error']['message'] = 'Incorrect Password'; //sets the error message to this.
                        }
                        else
                        {   //if all of the above has not errors
                                $_SESSION['user_id'] = $row['id']; //set the same as in registration, but here it is equal to $row['id'], instead of user_id
                                header('Location: wall.php?id='.$row['id']); //redirect back to profile of user who just logged in.
                                exit;
                        }
                }
        }
        header('Location: index.php'); //if there are errors, it will redirect to login.php, if no errors, it will redirect to page in header
        exit;
}//END LOGIN FUNCTION

if(isset($_POST['action']) && $_POST['action'] == 'register') //when somebody posts something in reg form
{
        register($connection, $_POST); //runs the registration function using the current connection($connection) and current post info ($_POST)
}
else if(isset($_POST['action']) && $_POST['action'] == 'login') //when somebody posts in login form
{
        login($connection, $_POST);
}
else if(isset($_GET['logout']))  
{
        logout();  
}
else if(isset($_POST['post_message']))
{
    message_post($connection, $_POST['message']);
}
else if(isset($_POST['post_comment']))
{
    comment_post($connection, $_POST);
}
header('Location: index.php');

?>