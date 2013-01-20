<?php

require('Socket.php');
require('forum.php');
$versionForum = 17;

$socket = new Socket('37.59.46.220');
$socket->connect();
$socket->handshake($versionForum);

$incompletePacket = '';
$lenIncompletePacket = 0;


while($socket->isAlive())
{
	if($content = $socket->getContent()) {

		$content = $incompletePacket. $content;

		if(unpack("N", substr($content, 0, 4))[1] > strlen($content)) {
			if($lenIncompletePacket == 0) {
				$lenIncompletePacket = unpack("N", substr($content, 0, 4))[1];
			}
			$incompletePacket = $content;
			continue;
		}
		else {
			$lenIncompletePacket = 0;
			$incompletePacket = '';
		}

		$socket->parse($content);

	}

}

$socket->close();

?>