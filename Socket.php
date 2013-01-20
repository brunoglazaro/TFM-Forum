<?php
class Socket {

	private $socket = NULL;
	private $serverIP;
	private static $ports = array(443, 44444, 44440, 5555, 3724, 6112);
	private $start = NULL;
	private $timeout = NULL;

	// Constructor
	public function __construct($IP, $timeout = 1) {
		$this->serverIP = $IP;
		$this->timeout = $timeout;
		$this->start = microtime(true);
	}

	// Socket connection
	public function connect()
	{

		// Loop through ports & connect
		for($i = 0; $i < count(self::$ports); $i++) {
			if(($this->socket = stream_socket_client('tcp://' . $this->serverIP . ':' . self::$ports[$i], $errno, $errstr, 15)) !== false)
				break;
		}

		// Non-blocking socket
		//stream_set_blocking($this->socket, 0);

	}


	// Send a packet
	public function send($packet)
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


	// Request thread list
	public function requestThreadList($category, $page = 0)
	{
		return $this->send(pack('c2N2', 2, 4, $category, $page));
	}

	// Request thread page
	public function requestThread($thread, $page = 0)
	{
		return $this->send(pack('c2Nnc', 2, 5, $thread, $page, 1));
	}
	

	// Parsing
	public function parse($packet)
	{

		// Get C & CC
		$C = ord($packet[4]);
		$CC = ord($packet[5]);

		// Trim packet
		$packet = substr($packet, 6);


		if($C == 40 && $CC == 40) {		// ping

			$this->send(pack("c2", 40, 40));

		}

		else if($C == 2 && $CC == 2) {	// Handshake answer

			if(!isset($_GET['c']) && !isset($_GET['s'])) {
				include("catList.php");
				readCatList($packet);
				$this->close();
			}
			else if(isset($_GET['c']) && !isset($_GET['s'])) {
				isset($_GET['p']) ? $this->requestThreadList($_GET['c'], $_GET['p']) : $this->requestThreadList($_GET['c']);
			}
			else {
				isset($_GET['p']) ? $this->requestThread($_GET['s'], $_GET['p']) : $this->requestThread($_GET['s']);
			}
		}

		else if($C == 2 && $CC == 4) {	// Thread list

			$display = isset($_GET['s']) ? false : true;

			include("threadList.php");
			readThreadList($packet, $display);

			if($display)
				$this->close();

		}
		else if($C == 2 && $CC == 5) {	// Post lists

			include("thread.php");
			readThread($packet);
			$this->close();

		}
		else {

			// Nothing yet

		}

	}


	// Check TimeOut
	private function checkTimeOut() {

		if((microtime(true) - $this->start) > $this->timeout) {

			forumHeader("Erreur");
			?>
				<div id="brdmain">
					<div class="block" id="msg">
						<h2><span>Erreur</span></h2>
						<div class="box">
							<div class="inbox">
								<p>Le lien que vous avez suivi est incorrect ou périmé.</p>
								<p><a href="javascript: history.go(-1)">Retour</a></p>
							</div>
						</div>
					</div>
				</div>
		<?php
			forumFooter();
			
			$this->close();
		}
	}


	// Is connection alive ?
	public function isAlive() {

		$this->checkTimeOut();

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