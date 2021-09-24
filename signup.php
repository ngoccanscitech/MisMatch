<html>
    <head>
        <title>Sign Up</title>
    </head>
    <body>
        <?php
            require_once('connectvars.php');
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if(isset($_POST['submit'])){
                $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
                $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
                $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
            }

            if(!empty($username) && !empty($password1) 
            && !empty($password2) && ($password1 == $password2)) {
                $query = "SELECT username FROM mismatch_user WHERE username = '$username'";
                $data = mysqli_query($dbc, $query);
                if(mysqli_num_rows($data) == 0){
                    $query = "INSERT INTO mismatch_user (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
                    mysqli_query($dbc, $query);
                    echo '<p>You sign up success</p><a href="editprofile.php">edit your profile</a>';
                    mysqli_close($dbc);
                    exit();
                }else{
                    echo '<p class="error">Username is exist. Please use a different</p>';
                    $username = "";
                }
            }else{
                echo '<p class="error">Fields not empty, desire password</p>';
            }
            // ngochan abc123 
        ?>
        
        <p>Please enter username and desired password to sign up mismatch</p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset>
                <label for="username">Username</label>
                <input type="text" id="username" name="username"><br>
                <label for="password1">Password</label>
                <input type="password" id="password1" name="password1"><br>
                <label for="password2">Password (Retype)</label>
                <input type="password" name="password2" id="password2"><br>
                <input type="submit" name="submit" value="sign up">
            </fieldset>
        </form>
    </body>
</html>