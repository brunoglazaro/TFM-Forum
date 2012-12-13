<?php

require('Socket.php');

$versionForum = 13;


$socket = new Socket();
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