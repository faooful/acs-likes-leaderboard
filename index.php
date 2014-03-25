<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Charles Newey"/>

    <title>Aber Comp Sci Likes Leaderboard (24 hours)</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-49311179-1', 'assemblyco.de');
  ga('send', 'pageview');
</script>

<div>
<h2>Aber Comp Sci Likes Leaderboard</h2>
<h5>24 hour totals, ((num. likes * 2) - num. posts)</h5><br /><br />
    <ul>
<?php

// Setup
require_once('facebook-sdk/facebook.php');

// URL
$url = 'http://acs.assemblyco.de/';

// Groups
$acs_id = 259914077434319;
$acs_qa_id = 272029619549924;
$groups = [$acs_qa_id, $acs_id];

// App config
$config = [
	'appId' => '428410087261874',
	'secret' => '69186c4b4230aff205f5b5f146e078d5',
	'fileUpload' => false,
	'allowSignedRequest' => false,
];

$fb = new Facebook($config); // Create new Facebook instance

$users = [];
$likes = [];
foreach ($groups as $group) {
    // Get all posts
    $posts = $fb->api($group .
                        '/feed?since=24+hours+ago');

    foreach ($posts['data'] as $post) {
        $id = $post['from']['name'];
        if (! isset($users[$id])) {
            $users[$id] = 1;
        } else {
            $users[$id]++;
        }

        if (! isset($likes[$id])) {
            if (isset($post['likes'])) {
                $likes[$id] = count($post['likes']);
            }
        } else {
            if (isset($post['likes'])) {
                $likes[$id] += count($post['likes']);
            }
        }

        if (isset($post['comments'])) {
            foreach ($post['comments']['data'] as $comment) {
                $id = $comment['from']['name'];
                if (! isset($users[$id])) {
                    $users[$id] = 1;
                } else {
                    $users[$id]++;
                }

                if (! isset($likes[$id])) {
                    $likes[$id] = $comment['like_count'];
                } else {
                    $likes[$id] += $comment['like_count'];
                }
            }
        }
    }
}

$popularity = [];
arsort($likes);
foreach($likes as $name => $count) {
    $c = $count * 2;
    $pop = $c - $users[$name];
    $popularity[$name] = $pop;
}

arsort($popularity);
foreach($popularity as $name => $ratio) {
    echo "\t\t" . '<li>' . PHP_EOL;
    echo "\t\t" . $name . ': ' . '<b>' . $ratio . '</b>';
    echo PHP_EOL . "\t\t" . '<br />' . PHP_EOL;
    echo "\t\t" . '</li>' . PHP_EOL;
}

?>
    </ul>
    <br /><br /><a href="http://github.com/charlienewey/acs-likes-leaderboard">Source code</a> available on <a href="http://www.github.com">GitHub</a>
</div>
</html>
