<?php
    session_start();
    //If the use is log in, delete the cookie to log them out
    if(isset($_SESSION['user_id'])){
        $_SESSION = array();
        if(isset($_COOKIE[session_name()]))
        setcookie(session_name(),'' ,time() - 3600);
    }
    session_destroy();
    //Redirect to the home page
    $home_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER[PHP_SELF]).'/index.php';
    header('Location: '.$home_url);
?>