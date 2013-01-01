<?php

global $categories;

$categories = array(
	'fr' => array(
		1 => 'Discussions', 2 => 'Hors-Sujet', 3 => 'Éditeur de cartes', 4 => 'Tribus', 5 => 'Fanarts', 6 => 'Suggestions',
		7 => 'Bugs', 8 => 'Jeux de forum', 9 => 'Reports', 10 => 'Soumission cartes', 11 => 'Archives'
	),
	'en' => array(
		1 => 'Discussions', 2 => 'Off Topic', 3 => 'Map Editor', 4 => 'Tribes', 5 => 'Fanarts', 6 => 'Suggestions',
		7 => 'Bugs', 8 => 'Forum game', 9 => 'Reports', 10 => 'Map Submission', 11 => 'Archives'
	),
	'xx' => array(
		1 => 'Annonces', 10 => 'Fanarts', 44 => 'Corbeille',
		400 => 'Atelier 801', 401 => 'Sanctions', 402 => 'Modérateurs', 403 => 'Sentinelles', 404 => 'Map crew', 405 => 'Tigrounette', 406 => 'Arbitres', 407 => 'Soumission cartes'
	),
);

function forumHeader($titre) {
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Forums - <?php echo $titre ?></title>
		<base href="http://zap.olympe.in/forum/">
		<meta charset="UTF-8" />
		<link href="forum.css" type="text/css" rel="stylesheet">
		<link rel="icon" type="image/png" href="images/favicon.png" />
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="container">
			<header>
				<div class="inbox" id="brdtitle">
					<h1>
						<a href="index.php">Forums</a>
					</h1>
				</div>
				<nav class="inbox">
					<ul>
						<li id="navextra1">
							<a href=".">Accueil</a>
						</li>
						<li id="navextra2">
							<a href="http://www.transformice.com/forum/">Forum officiel</a>
						</li>
						<li class="isactive" id="navindex">
							<a href="http://www.transformice.com/">Transformice</a>
						</li>
						<li id="navextra3">
							<a href="http://fr.cheese.formice.com/">Cheese.Formice</a>
						</li>
						<li id="navextra4">
							<a href="http://fr.cheese.formice.com/online-mods">Modos connectés</a>
						</li>
						<li id="navextra5">
							<a href="http://www.transformice.com/irc-webchat/">IRC</a>
						</li>
					</ul>
				</nav>
			</header>
<?php
}

function forumFooter() {
?>
		</div>
		<footer>
			<div class="inbox" id="brdfooternav">
				<div class="conl">
					<p>Logo et favicon : <a href ="http://www.transformice.com/">Transformice</a><br/>
					Icônes par <a href="http://www.famfamfam.com/">famfamfam</a></p>
				</div>
				<div class="conr">
					<p>
						<span class="poweredby">Inspiré par
							<a href="http://fluxbb.org/">FluxBB</a>
						</span>
						<br/>
						<span class="pika">Pika Pika</span>
					</p>
				</div>
			</div>
		</footer>
<?php
	if(isset($_GET['s'])) {
?>
		<script type="text/javascript">
			var id, flags, flagDivs = document.getElementsByClassName("flags");

			for(i = 0; i < flagDivs.length; i++) {

				flagDiv = flagDivs[i];
    			flags = flagDiv.getElementsByClassName("flagImg");

    			for(j = 0; j < flags.length; j++) {

        			flags[j].addEventListener("click", function(){

            			var el, pElement = document.getElementById("flag"+this.getAttribute("alt")+this.parentNode.parentNode.getAttribute("id").replace(/multilang/,""));

            			for(k = 0; k < pElement.parentNode.childNodes.length; k++) {
							el = pElement.parentNode.childNodes[k];

							if(el.nodeType != 1) continue;				
							if(el.getAttribute("class") == "flag selected")
								el.setAttribute("class","flag");
						}

            			pElement.setAttribute("class", "flag selected");

        			}, false);
    			}
			}
		</script>
<?php
	}
	elseif(!isset($_GET['s']) && !isset($_GET['c'])) {
?>
		<script type="text/javascript">
			var flag, flags = document.getElementById('communities').getElementsByTagName('li');

			for(i = 0; i < flags.length; i++) {
				flag = flags[i];
				flag.addEventListener("click", function() {

					flags = document.getElementById('communities').getElementsByTagName('li');

					for(i = 0; i < flags.length; i++) {
						if(flags[i]==this) flags[i].setAttribute("class", "catflagsel");
						else  flags[i].setAttribute("class", "catflag");
					}

					categories = document.getElementById('catlist').getElementsByTagName('li');

					for(i = 0; i < categories.length; i++) {
            			if(categories[i].getElementsByTagName('img')[0].alt == this.getElementsByTagName('img')[0].alt || categories[i].getElementsByTagName('img')[0].alt == 'xx') categories[i].setAttribute("class", "cat");
            			else  categories[i].setAttribute("class", "cathidden");
					}
				}, false);
			}
		</script>
<?php
	} ?>
	</body>
</html>
<?php
}


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