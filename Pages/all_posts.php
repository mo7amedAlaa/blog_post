<?php
require_once('../config/database.php');
require_once('../classes/Post.php');


$post = new Post($pdo);


$posts = $post->readAllPosts();

if($posts)
print_r($posts);


?>