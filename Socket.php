<?php
class Socket {

	private $socket = null;
	private static $serverIP = '37.59.46.220';
	private static $ports = array(443, 44444, 44440, 5555, 3724, 6112);


	// Socket connection
	public function connect()
	{

		// Loop through ports & connect
		for($i = 0; $i < count(self::$ports); $i++) {
			if(($this->socket = stream_socket_client('tcp://' . self::$serverIP . ':' . self::$ports[$i], $errno, $errstr, 15)) !== false)
				break;
		}

		// Non-blocking socket
		stream_set_blocking($this->socket, 0);

	}


	// Send a packet
	public function send($packet, $debug = false)
	{

		// Prepend packet with its size
		$packetSize = pack('N', 4 + strlen($packet));
		$packet =  $packetSize . $packet;

		// Send packet
		return fwrite($this->socket, $packet);

	}


	// Send handshake packet
	public function handshake($version)
	{
		return $this->send(pack('c2N', 28, 1, $version));
	}


	// Parsing
	public function parse($packet)
	{

		$C = ord($packet[4]);
		$CC = ord($packet[5]);


		if($C == 40 && $CC == 40) {		// ping

			$this->send(pack("c2", 40, 40));

		}

		else if($C == 2 && $CC == 2) {	// Handshake answer

			// Nothing yet

		}

		else if($C == 2 && $CC == 4) {	// Thread list

			// Nothing yet

		}
		else if($C == 2 && $CC == 5) {	// Post lists

			// Nothing yet

		}
		else {

			// Nothing yet

		}

	}


	// Is connection alive ?
	public function isAlive()
	{
		return !feof($this->socket);
	}


	// Read the socket
	public function getContent()
	{
		return fread($this->socket, 8192);
	}


	// Close socket
	public function close()
	{
		return stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
	}

}
?>