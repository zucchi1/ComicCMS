<?php
if (isset($_SESSION['user_id'])) {
    include_once __DIR__.'/../comment.php';
}else{
    include_once __DIR__.'/../login.php';
}
?>