
<html>
    <head>
        <link rel="stylesheet" href="lib/css/slideshow.css">
        <script src="lib/js/slideshow.js"></script>

        <link rel="stylesheet" href="lib/css/autocomplete.css">
        <script src="lib/js/autocomplete.js"></script>

    </head>

    <body>

<?php
    require_once 'env_load.php';
    require_once 'lib/vendor/autoload.php';
    
    session_start();
    
    include_once "login_check.php";

    use Abraham\TwitterOAuth\TwitterOAuth;

    $connection = new TwitterOAuth($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET_KEY'], $_SESSION['access_token']['oauth_token'],$_SESSION['access_token']['oauth_token_secret']);
    
    echo "<div class='form-container'><form autocomplete='off'>
            <div class='autocomplete'>
                <input id='myInput' type='text' name='handler' placeholder='followers'>
            </div>
        </form></div>
        <script>autocomplete(document.getElementById('myInput'));</script>
        ";

    $statuses = $connection->get("statuses/home_timeline",["count"=>2,"exclude_replies"=>TRUE]);
    echo "<div class='slideshow-container'>";
            
    foreach($statuses as $index=>$status)
    {
        $status = (array) $status;
        $tweet_text = $status['text'];
        $user =(array) $status['user'];
        $user_name = $user['name'];
        $profile_img = $user['profile_image_url'];


        $entities = (array) $status['entities'];

        if (array_key_exists("media",$entities))
            $medias = $entities['media'];

        echo "<div class='mySlides fade'>";
        echo "<div class='numbertext'>".($index+1)."/".count($statuses)."</div>";
        
        echo "<div class='tweet-container'>";
            echo "<img class='profile-img' src='".$profile_img."' />";
            echo "<div class='user-name'>".$user_name."</div>";
            echo "<div class='tweet-text'>".$tweet_text."</div>";

                if(isset($medias))
                {
                    foreach($medias as $media_index=>$media)
                    {
                        $media_obj = (array)$media;
                        if($media_obj['type']=='photo')
                        {
                            $media_img_url = $media_obj['media_url'];
                            echo "<img class='media-img' src='".$media_img_url."' />";
                        }
                    }
                    $medias = null;
                }
        echo "</div>";

        echo "</div>";
    }
    
    echo "<a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
            <a class='next' onclick='plusSlides(1)'>&#10095;</a>
            </div>
            <br>";
    
    echo  "<div style='text-align:center'>";
    for ($x = 1; $x <= count($statuses); $x++) {
        echo "<span class='dot' onclick='currentSlide(".$x.")'></span>";
    }
    echo "</div>";
    $followers = ((array) $connection->get("followers/list",["count"=>3,"exclude_replies"=>TRUE]))['users'];
    print_r((array) $connection->get("followers/list",["count"=>3,"exclude_replies"=>TRUE]));
    foreach ($followers as $follower_index=>$follower)
    {
        echo "<div class='followers-list'>";
        echo display_user_profile($follower);
        echo "</div>";
    }

    function display_user_profile($follower)
    {
        $follower = (array)$follower;
        echo "<div class='follower'>";
            echo "<img class='profile-img' src='".$follower['profile_image_url']."' />";
            echo "<div class='user-name'>".$follower['name']."</div>";
            echo "<div class='user-handle'>"."@".$follower['screen_name']."</div>";
        echo "</div>";
    }

?>

    </body> 

</html>