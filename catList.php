<?php
function readCatList($packet) {

	global $categories;

	$catList = array();

	// Get user's locale
	$lenLocale =  unpack("n",substr($packet, 0, 2))[1];
	$locale = substr($packet, 2, $lenLocale);

	// Output forum header
	forumHeader('Transformice');

	$packet = substr($packet, 2+$lenLocale);
	$count = 0;
?>
			<div id="brdmain">
				<div class="linkst">
					<div class="inbox crumbsplus">
						<ul class="crumbs">
							<li>
								<a href="./">Forums</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="blocktable">

<?php
	// Loop through forum info to store data in $catlist
	while(strlen($packet) > 0 ) {

		// Get forum category id, lang and icon
		$idCategory = unpack("N",substr($packet, 0, 4))[1];
		$lenLang = unpack("n",substr($packet, 4, 2))[1];
		$lang = substr($packet, 6, $lenLang);
		$icon = unpack("n",substr($packet, 6+$lenLang, 2))[1];

		// Store data in $catlist
		$catList[$lang][] = $idCategory;

		// Trim packet
		$packet = substr($packet, 8+$lenLang);	

		$count++;	
	}
?>
					<div id="categories">
						<div id="communities">Communities :
							<ul>
<?php
	// Loop through languages to show flags
	$langs = array_keys($catList);
	foreach($langs as $lang) {
		if($lang == "xx")
			continue;

		$class = ($lang == $locale) ? "catflagsel" : "catflag" ;
		echo '<li class="'.$class.'"><img src="./images/'.$lang.'.png" alt="'.$lang.'" /></li>';
	}
?>
							</ul>
						</div>
						<div id="catlist">
							<ul>
<?php
	// Announcement
	for($i=0; $i < 1; $i++) {
		echo '<li class="cat"><img src="./images/xx.png" alt="xx" /><a href=".?c='.$catList['xx'][$i].'">'.$categories['xx'][$catList['xx'][$i]].'</a></li>';
	}

	// Forum categories according to selected language
	foreach($langs as $lang) {

		if($lang == "xx") continue;

		$class =  ($lang == $locale) ? "cat" : "cathidden" ;

		foreach($catList[$lang] as $cat) {

			echo '<li class="'.$class.'"><img src="./images/'.$lang.'.png" alt="'.$lang.'" />';

			// Check if language is French
			if($cat >= 500 && $cat <= 520)
				echo '<a href=".?c='.$cat.'">'.$categories['fr'][$cat%20].'</a>';
			else
				echo '<a href=".?c='.$cat.'">'.$categories['en'][$cat%20].'</a>';

			echo '</li>';
		}
	}

	// Show international categories
	$totalXX = count($catList['xx']);
	for($i=1; $i < $totalXX; $i++) {
		echo '<li class="cat"><img src="./images/xx.png" alt="xx" /><a href=".?c='.$catList['xx'][$i].'">'.$categories['xx'][$catList['xx'][$i]].'</a></li>';
	}
?>

							</ul>
						</div>
					</div>
				</div>
				<div class="postlinksb">
					<div class="inbox crumbsplus">
						<ul class="crumbs">
							<li>
								<a href="./">Forums</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
<?php
	forumFooter();
}
?>