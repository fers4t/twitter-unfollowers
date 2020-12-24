<?php

// print list to result.htm

require_once('vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

DEFINE('CONSUMER_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxxxx');
DEFINE('CONSUMER_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxxxx');
$access_token = "xxxxxxxxxxxxxxxxxxxxxxxxxx";
$access_token_secret = "xxxxxxxxxxxxxxxxxxxxxxxxxx";

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);

$followers = $connection->get("followers/ids", ["cursor" => -1, "screen_name" => "fers4t", "count" => "5000"]);
$friends = $connection->get("friends/ids", ["cursor" => -1, "screen_name" => "fers4t", "count" => "5000"]);

$friends_array = $friends->ids;
$friends_array = array_values($friends_array);

$followers_array = $followers->ids;
$followers_array = array_values($followers_array);

$unfollowers = array_diff($friends_array, $followers_array);

foreach ($unfollowers as $key => $value) {
    $user_array = $connection->get("users/show", ["user_id" => $value]);
    $user_name = $user_array->screen_name;
    $full_name =  $user_array->name;
    $followers_count = $user_array->followers_count;

    $fp = fopen('result.htm', 'a');
    fwrite($fp, $full_name . " (<a href='https://twitter.com/$user_name'>@$user_name</a>) is not following you back. Follower Count: <b>$followers_count</b><br>");
    fclose($fp);
}
