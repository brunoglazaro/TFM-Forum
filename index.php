<?php

require('Socket.php');
require('forum.php');
$versionForum = 13;


$socket = new Socket('37.59.46.220');
$socket->connect();
$socket->handshake($versionForum);

while($socket->isAlive())
{

	if($content = $socket->getContent()) {

		$socket->parse($content);

	}

}

$socket->close();

?>