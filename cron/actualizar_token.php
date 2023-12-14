<?php
require_once '../classes/token.class.php';
$_token = new Token();
$date = date('Y-m-d');
echo $_token->updateToken($date);