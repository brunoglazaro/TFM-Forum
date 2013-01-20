<?php

function readThread($packet) {

	global $idCategorie;
	global $categories;
	global $numPage;
	global $lang;
	global $catTitle;

	$idThread = unpack("N",substr($packet, 0, 4))[1];

	// $edition = ord($packet[4]);
	// $peutSuppr = ord($packet[5]);
	// $can_reply = ord($packet[6]);
	// $closed = ord($packet[7]);
	// ord($packet[8]);

	$lenTitle = unpack("n",substr($packet, 9, 2))[1];
	$title = substr($packet, 11, $lenTitle);
	$currentPage = unpack("n",substr($packet, 11+$lenTitle, 2))[1]+1;
	$totalPage = unpack("n",substr($packet, 13+$lenTitle, 2))[1]+1;


	$packet = substr($packet, 16+$lenTitle);
	$count = ($numPage % 10000)*20;

	forumHeader($title);

	echo '<div id="brdmain">
		<div class="linkst">
			<div class="inbox crumbsplus">
				<ul class="crumbs">
					<li>
						<a href="./">Forums</a>
					</li>
					<li>
						<span>»&nbsp;</span>
						<a href="?c='.$idCategorie.'"><img src="./images/'.$lang.'.png" alt="'.$lang.'" />'.$catTitle.'</a>
					</li>
					<li>
						<span>»&nbsp;</span>
						<a href="./?s='.$idThread.'&amp;p=0"><strong>'.$title.'</strong></a><a href="http://www.transformice.com/forum/?s='.$idThread.'&amp;p='.($currentPage-1).'"><img src="images/ext.png" alt="'.$title.'" class="ext" /></a>
					</li>
				</ul>
				<div class="pagepost">
					<p class="pagelink conl">
						<span class="pages-label">Pages&nbsp;:</span>
						'. paginate($totalPage, $currentPage, '?s='.$idThread) .'
					</p>
				</div>
			</div>
		</div>';


	while(strlen($packet) > 0 ) {


		$idPost = unpack("N", substr($packet, 0, 4))[1];

		//$oldPosterType = ord($packet[4]);
		$lenPoster = unpack("n", substr($packet, 5, 2))[1];
		$poster = substr($packet, 7, $lenPoster);
		
		$posterType = ord($packet[7+$lenPoster]);

		$avatar = unpack("N", substr($packet, 8+$lenPoster, 4))[1];
		$date = unpack("N", substr($packet, 12+$lenPoster, 4))[1];

		$lenMsg = unpack("n", substr($packet, 16+$lenPoster, 2))[1];
		$msg = substr($packet, 18 + $lenPoster, $lenMsg);
		
		$lenPostInfo = unpack("n", substr($packet, 18 + $lenPoster + $lenMsg, 2))[1];
		// $postInfo = substr($packet, 20 + $lenPoster + $lenMsg, $lenPostInfo);
		// ? = ord($packet[20 + $lenPoster + $lenMsg + $lenPostInfo]);
		// $multipleLanguages = ord($packet[21 + $lenPoster + $lenMsg + $lenPostInfo]);

		$count++;


		$msg = htmlspecialchars($msg, ENT_NOQUOTES | ENT_HTML401);
		$msg = strtr($msg, array(pack("c", 10) => '<br/>'));		// LF => <br>
		$msg = translations($msg, $idPost);	// Multilingual blocs
		$msg = replaceHTML($msg);


		switch($posterType) {
			case 11:
				$coloredPoster = '<span class="CV">' . $poster . '</span>';		// Sents
			break;
			case 14:
				$coloredPoster = '<span class="CR">' . $poster . '</span>';		// Admins
			break;
			case 17:
				$coloredPoster = '<span class="CJ">' . $poster . '</span>';		// Mods
			break;
			default:
				$coloredPoster = $poster;
			break;
		}

		// Get avatar url
		$avatarUrl = "images/avatar.jpg";
		if($avatar > 0)
			$avatarUrl = 'http://www.transformice.com/avatar/'.($avatar % 10000).'/'.$avatar.'.jpg';

		echo '<div class="blockpost" id="'.$idPost.'">
			<h2>
				<span>
					<span class="conr"><a href="./?s='.$idThread.'&amp;p='. ($currentPage-1) .'#'.$idPost.'">#'.$count.'</a></span>
					'.strftime("Posté le %d/%m/%Y à %T", $date).'
				</span>
			</h2>
			<div class="inbox">
				<div class="postleft">
					<dl>
						<dt>
							<strong>'.$coloredPoster.'</strong>
						</dt>
						<dd class="postavatar">
							<img width="100" height="100" alt="" src="'.$avatarUrl.'">
						</dd>
						<dd class="usercontacts">
							<span class="website">
								<a href="'.'http://cheese.formice.com/mouse/'.$poster.'">Profil CFM</a>
							</span>
						</dd>
					</dl>
				</div>
				<div class="postright">
					<div class="postmsg">
						'.$msg.'
					</div>
				</div>
			</div>
		</div>';


		$packet = substr($packet, 22 + $lenPoster + $lenMsg + $lenPostInfo);
	}
?>
	
		<div class="postlinksb">
			<div class="inbox crumbsplus">
				<div class="pagepost">
					<p class="pagelink conl">
						<span class="pages-label">Pages&nbsp;:</span>
						<?php echo paginate($totalPage, $currentPage, '?s='.$idThread) ?>
					</p>
				</div>
				<ul class="crumbs">
					<li>
						<a href="./">Forums</a>
					</li>
					<li>
						<span>»&nbsp;</span>
						<?php echo '<a href="?c='.$idCategorie.'"><img src="./images/'.$lang.'.png" alt="'.$lang.'" />'.$catTitle.'</a>'; ?>
					</li>
					<li>
						<span>»&nbsp;</span>
						<?php echo '<a href="./?s='.$idThread.'&amp;p=0"><strong>'.$title.'</strong></a><a href="http://www.transformice.com/forum/?s='.$idThread.'&amp;p='.($currentPage-1).'"><img src="images/ext.png" alt="'.$title.'" class="ext" /></a>'; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php
	forumFooter();
}
?>