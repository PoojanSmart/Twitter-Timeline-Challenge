<?php
    require_once 'env_load.php';
    require_once 'lib/vendor/autoload.php';

    session_start();
    use Abraham\TwitterOAuth\TwitterOAuth;

    $is_loggin = FALSE;

    if(isset($_SESSION['access_token']))
    {
        $is_loggin = TRUE; 
    }
    else if(isset($_SESSION['oauth_token']) && isset($_GET['oauth_token']) && $_SESSION['oauth_token'] ==$_GET['oauth_token'] )
    {
        $connection = new TwitterOAuth($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET_KEY'], $_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_GET['oauth_verifier']]);
        $_SESSION['access_token'] = $access_token;
        $is_loggin = TRUE; 
    }
    else
    {
        $connection = new TwitterOAuth($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET_KEY']);
    
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => "http://localhost/twitterApp/login.php"));
    
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    
        header("Location:".$url);
    }

    if($is_loggin == TRUE)
    {
        header("Location:"."index.php");
    }
?>