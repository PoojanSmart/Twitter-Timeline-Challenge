<?php
    require_once 'env_load.php';
    require_once 'lib/vendor/autoload.php';

    session_start();
    use Abraham\TwitterOAuth\TwitterOAuth;
    $connection = new TwitterOAuth($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET_KEY'], $_SESSION['access_token']['oauth_token'],$_SESSION['access_token']['oauth_token_secret']);
    $followers = ((array) $connection->get("followers/list",["count"=>10,"exclude_replies"=>TRUE]))['users'];
    $myArr = [];
    foreach ($followers as $follower_index=>$follower)
    {
        $follower = (array) $follower;
        array_push($myArr,$follower['name']);
    }
    $myJSON = json_encode($myArr);
    
    echo $myJSON;
?>