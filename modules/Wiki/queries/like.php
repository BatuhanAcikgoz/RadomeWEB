<?php
require(ROOT_PATH . '/modules/Wiki/classes/Wiki.php');

$queries = new Queries();
$wiki = new Wiki($queries);
$wiki->getPages();

$_POST = json_decode(file_get_contents("php://input"), true);
$target_user = new User($_GET['id']);
    if (!$target_user->data()) {
        die(json_encode(array('html' => 'User not found')));
    } else {
        $user_query = $user_query[0];
    }
    
//var_dump($user->data()->username);
$pageid = $_POST['pageid'];
$username = $user->data()->username;

if (isset($_POST['liked'])) {
  if(!$wiki->isPageLikedByUser($username, $pageid)){
    if($wiki->reaction($pageid, $username, true)){
      echo('Like givin successfully.');
    } else {
      echo('Something went wrong while trying to like.');
    }
  }
  exit;
}
if (isset($_POST['unliked'])) {
  if($wiki->isPageLikedByUser($username, $pageid)){
    if($wiki->reaction($pageid, $username, false)){
      echo('Unliked successfully.');
    } else {
      echo('Something went wrong while trying to unlike.');
    }
    echo('Unliking the page '.$pageid.' for user '.$username);
  }
  exit;
}
die();
