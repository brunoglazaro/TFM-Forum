<?php

function readThreadList($packet, $display = true) {

	
	global $idCategorie, $categories, $lang, $catTitle;

	$idCategorie = unpack("N",substr($packet, 1, 4))[1];
	$lenLang = unpack("n", substr($packet, 5, 2))[1];
	$lang = unpack("a$lenLang", substr($packet, 7, $lenLang))[1];
	$page = unpack("N",substr($packet, 9+$lenLang, 4))[1];
	$pageTotal = unpack("N",substr($packet, 13+$lenLang, 4))[1];
	
	switch($lang) {
		case 'xx':
			$catTitle = $categories['xx'][$idCategorie];
			break;
		case 'fr':
			$catTitle = '[FR] ' . $categories['fr'][$idCategorie%20];
			break;
		default:
			$catTitle = '['.strtoupper($lang).'] ' . $categories['en'][$idCategorie%20];
			break;
	}


	$packet = substr($packet, 18+$lenLang);


	if($display) {
		
		forumHeader($catTitle);
?>
			<div id="brdmain">
				<div class="linkst">
					<div class="inbox crumbsplus">
						<ul class="crumbs">
							<li>
								<a href="./">Forums</a>
							</li>
							<li>
								<span>»&#160;</span>
								<a href="./?c=<?php echo $idCategorie; ?>"><img src="./images/<?php echo $lang ?>.png" alt="<?php echo $lang ?>" /><strong><?php echo $catTitle ?></strong></a>
							</li>
						</ul>
						<div class="pagepost">
							<p class="pagelink conl">
								<span class="pages-label">Pages&#160;:</span>
								<?php echo paginate($pageTotal, $page + 1, '?c='.$idCategorie); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="blocktable">
					<div class="box">
						<div class="inbox">
							<table>
								<thead>
									<tr>
										<th class="tcl" scope="col">Discussion</th>
										<th class="tc2" scope="col">Réponses</th>
										<th class="tcr" scope="col">Dernier message</th>
									</tr>
								</thead>
								<tbody>
<?php
	
	$count = 0;

	while(strlen($packet) > 0) {

		$idThread = unpack("N",substr($packet, 0, 4))[1];
		$lenTitle = unpack("n", substr($packet, 4, 2))[1];
		$threadTitle = substr($packet, 6, $lenTitle);
		$date = unpack("N",substr($packet,6+$lenTitle, 4))[1];


		$lenAuthor = unpack("n", substr($packet, 10+$lenTitle, 2))[1];
		$author = substr($packet, 12+$lenTitle, $lenAuthor);
		$authorType = ord($packet[12+$lenTitle+$lenAuthor]);

		$lenLastPoster = unpack("n", substr($packet, 13+$lenTitle+$lenAuthor, 2))[1];
		$lastPoster = substr($packet, 15+$lenTitle+$lenAuthor, $lenLastPoster);

		$repCount = unpack("n", substr($packet, 15+$lenTitle+$lenAuthor+$lenLastPoster, 2))[1];
		$thStatus = ord($packet[17+$lenTitle+$lenAuthor+$lenLastPoster]);
		$isPinned = ord($packet[18+$lenTitle+$lenAuthor+$lenLastPoster]);


		$packet = substr($packet, 19+$lenTitle+$lenAuthor+$lenLastPoster);

		$trClass = "row";
		if($isPinned) $trClass .= " sticky";
		
		$iconClass = "icon";
		if($thStatus == 2) $iconClass .= "-locked";

		echo '<tr class="' . $trClass . '">
							<td class="tcl">
								<div class="'.$iconClass.'">
									<div class="nosize">'.$count.'</div>
								</div>
								<div class="tclcon">
									<a href="?s=' . $idThread . '" class="topicLink">' . $threadTitle . '</a>
									<span class="byuser">par ' . color($author) . '</span>';

								if($repCount > 20)
									echo '<span class="pagestext"> ['.paginate(ceil($repCount/20), -1, '?s='.$idThread).']</span>';

								echo '</div>
							</td>
							<td class="tc2">' . $repCount . '</td>
							<td class="tcr">
								<a href="?c='.$idCategorie.'&amp;s=' . $idThread . '&amp;p=' . (ceil($repCount/20) - 1) . '">'. strftime("le %d/%m/%Y à %T", $date) .'</a> <span class="byuser">par ' . color($lastPoster) . '</span>
							</td>
						</tr>';
		
		
		$count++;
	}
	
	?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="postlinksb">
					<div class="inbox crumbsplus">
						<div class="pagepost">
							<p class="pagelink conl">
								<span class="pages-label">Pages&#160;:</span>
								<?php echo paginate($pageTotal, $page + 1, '?c='.$idCategorie); ?>
							</p>
						</div>
						<ul class="crumbs">
							<li>
								<a href="./">Forums</a>
							</li>
							<li>
								<span>»&#160;</span>
								<a href="./?c=<?php echo $idCategorie; ?>"><img src="./images/<?php echo $lang ?>.png" alt="<?php echo $lang ?>" /><strong><?php echo $catTitle; ?></strong></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
	<?php
		forumFooter();
	}
}
?>