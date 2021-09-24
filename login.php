<?php   
        require_once('connectvars.php');
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_start();
        //Clear the error message
        $error_msg = "";

        if(!isset($_SESSION['user_id'])){
            if(isset($_POST['submit'])){
                $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
                $password = mysqli_real_escape_string($dbc, trim($_POST['password']));

                if(!empty($username) && !empty($password)){
                    $query = "SELECT username, user_id FROM mismatch_user WHERE
                    username = '$username' AND password = SHA('$password') ";
                    $data = mysqli_query($dbc, $query);
        
                    if(mysqli_num_rows($data) == 1){
                        $row = mysqli_fetch_array($data);
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_id'] = $row['user_id'];
                        $home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
                        header('Location: '.$home_url);
                    }else{
                    $error_msg = "You must enter valid username, password for log in and access";
                    }
                }else{
                    $error_msg = "You must enter username, password for login and success";
                }
            }
        }
            
           // <h3>You must enter valid username, password for log in and access'.
           // 'If you aren\'t a register member, please <a href="signup.php">sign up</a>
    
        
        
 ?>
      
<html>
<title>Login</title>

<body>
      <?php 
        if(empty($_SESSION['user_id'])){
            echo '<p class="error">'.$error_msg.'</p>';
            ?>
             <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username"><br>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"><br>
                    <input type="submit" name="submit" value="submit">
                </fieldset>
            </form>
    <?php
        }else{
            echo ('<p class="login">You logged in: '.$_SESSION['username'].' </p>');
        }
      ?>
        
</body>

</html>