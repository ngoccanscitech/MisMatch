<?php 
    echo '<hr />';
    if(isset($_SESSION['user_id'])){
        echo '&#10084; <a href="viewprofile.php">View Profile</a><br />';
        echo '&#10084; <a href="editprofile.php">Edit Profile</a><br />';
        echo '&#10084; <a href="questionnaire.php">Questionnaire</a><br />';
        echo '&#10084; <a href="mymismatch.php">My Mismatch</a><br />';
        echo '&#10084; <a href="logout.php">Logout ('.$_SESSION['username'].')</a><br />';
      }else{
        echo '&#10084; <a href="login.php">Log in</a><br />';
        echo '&#10084; <a href="signup.php">Sign Up</a><br />';
      }
      echo '<hr/>';
?>