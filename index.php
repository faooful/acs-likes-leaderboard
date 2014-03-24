<html>
<head>
    <title>Aber Comp Sci Contribution Counter (24 hours)</title>
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
<h5>24 hour totals over "Aber Comp Sci" and "Aber Comp Sci Q+A" (likes^2 / posts^2)</h5><br /><br />
<ul>
<?php

// Facebook API location
require_once('facebook-sdk/facebook.php');

// URL
$url = 'http://acs.assemblyco.de/';

// Groups
$acs_id = 259914077434319;
$acs_qa_id = 272029619549924;
$groups = [$acs_id, $acs_qa_id];

// App config
$config = [
	'appId' => '1234567890',
	'secret' => '1234567890',
	'fileUpload' => false,
	'allowSignedRequest' => false,
];

$fb = new Facebook($config); // Create new Facebook instance

$users = [];
$likes = [];
foreach ($groups as $group) {
    // Get all posts
    $posts = $fb->api($group .
                        '/feed?since=1+day+ago&until=now&limit=10000');

    foreach ($posts['data'] as $post) {
        $id = $post['from']['name'];
        if (! isset($users[$id])) {
            $users[$id] = 1;
        } else {
            $users[$id]++;
        }

        if (! isset($likes[$id])) {
            $likes[$id] = count($post['likes']);
        } else {
            $likes[$id] += count($post['likes']);
        }

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

//echo('<h3>(likes ^ 2) / (posts ^ 2) (in last 48 hours)</h3>');
$popularity = [];
arsort($likes);
foreach($likes as $name => $count) {
    $p = pow($users[$name], 2);
    $l = pow($count, 2);
    $pop = bcdiv($l, $p, 3);
    $popularity[$name] = $pop;
}

arsort($popularity);
foreach($popularity as $name => $ratio) {
    if ($ratio == 0) {
       break;
    }
    echo '<li>';
    echo $name . ': ' . '<b>' . $ratio . '</b>' . '<br />';
    echo '</li>';
}

?>
</ul>
</div>
</html>
