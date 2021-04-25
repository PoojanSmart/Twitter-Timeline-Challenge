<?php
    $is_loggin = FALSE; 

    if(isset($_SESSION['access_token']))
    {
        $is_loggin = TRUE; 
    }

    if($is_loggin==FALSE)
    {
        echo "<h2>You are not logged in.</h2>";
        echo "<br/><a href='login.php'>Login</a>";
        die();
    }
?>