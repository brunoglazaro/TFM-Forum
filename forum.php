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
						if(flags[i]==this)
							flags[i].setAttribute("class", "catflagsel");
						else
							flags[i].setAttribute("class", "catflag");
					}

					categories = document.getElementById('catlist').getElementsByTagName('li');

					for(i = 0; i < categories.length; i++) {
            			if(categories[i].getElementsByTagName('img')[0].alt == this.getElementsByTagName('img')[0].alt || categories[i].getElementsByTagName('img')[0].alt == 'xx')
							categories[i].setAttribute("class", "cat");
            			else
							categories[i].setAttribute("class", "cathidden");
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


function paginate($num_pages, $cur_page, $link)
{
	$pages = array();
	$link_to_all = false;

	// If $cur_page == -1, we link to all pages (used in viewforum.php)
	if ($cur_page == -1)
	{
		$cur_page = 1;
		$link_to_all = true;
	}

	if ($num_pages <= 1)
		$pages = array('<strong class="item1">1</strong>');
	else
	{
		// Add a previous page link
		if ($num_pages > 1 && $cur_page > 1)
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($cur_page - 2).'">'.'Pr&eacute;c&eacute;dent'.'</a>';

		if ($cur_page > 3)
		{
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p=0">1</a>';

			if ($cur_page > 5)
				$pages[] = '<span class="spacer">'."&hellip;".'</span>';
		}

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = ($cur_page == 5) ? $cur_page - 3 : $cur_page - 2, $stop = ($cur_page + 4 == $num_pages) ? $cur_page + 4 : $cur_page + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $num_pages)
				continue;
			else if ($current != $cur_page || $link_to_all)
				$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($current-1).'">'. $current .'</a>';
			else
				$pages[] = '<strong'.(empty($pages) ? ' class="item1"' : '').'>'. $current.'</strong>';
		}

		if ($cur_page <= ($num_pages-3))
		{
			if ($cur_page != ($num_pages-3) && $cur_page != ($num_pages-4))
				$pages[] = '<span class="spacer">'."&hellip;".'</span>';

			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($num_pages-1).'">'. $num_pages .'</a>';
		}

		// Add a next page link
		if ($num_pages > 1 && !$link_to_all && $cur_page < $num_pages)
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($cur_page).'">'.'Suivant'.'</a>';
	}

	return implode(' ', $pages);
}

function color($str) {

	$patterns = array();
	$patterns[0] = '/<BV>(.*)<\/BV>/ims';
	$patterns[1] = '/<BV>(.*)$/i';
	$patterns[2] = '/<R>(.*)<\/R>/ims';
	$patterns[3] = '/<R>(.*)$/i';
	$patterns[4] = '/<BL>(.*)<\/BL>/ims';
	$patterns[5] = '/<BL>(.*)$/i';
	$patterns[6] = '/<J>(.*)<\/J>/ims';
	$patterns[7] = '/<J>(.*)$/i';
	$patterns[8] = '/<N>(.*)<\/N>/ims';
	$patterns[9] = '/<N>(.*)$/i';
	$patterns[10] = '/<G>(.*)<\/G>/ims';
	$patterns[11] = '/<G>(.*)$/i';
	$patterns[12] = '/<V>(.*)<\/V>/ims';
	$patterns[13] = '/<V>(.*)$/i';
	$patterns[14] = '/<VP>(.*)<\/VP>/ims';
	$patterns[15] = '/<VP>(.*)$/i';
	$patterns[16] = '/<VI>(.*)<\/VI>/ims';
	$patterns[17] = '/<VI>(.*)$/i';
	$patterns[18] = '/<ROSE>(.*)<\/ROSE>/ims';
	$patterns[19] = '/<ROSE>(.*)$/i';
	$patterns[20] = '/<CH>(.*)<\/CH>/ims';
	$patterns[21] = '/<CH>(.*)$/i';
	$patterns[22] = '/<T>(.*)<\/T>/ims';
	$patterns[23] = '/<T>(.*)$/i';
	$patterns[24] = '/<CR>(.*)<\/CR>/ims';
	$patterns[25] = '/<CR>(.*)$/i';
	$patterns[26] = '/<CV>(.*)<\/CV>/ims';
	$patterns[27] = '/<CV>(.*)$/i';
	$patterns[28] = '/<CJ>(.*)<\/CJ>/ims';
	$patterns[29] = '/<CJ>(.*)$/i';

	$replacements = array();
	$replacements[0] = '<span class="BV">$1</span>';
	$replacements[1] = $replacements[0];
	$replacements[2] = '<span class="R">$1</span>';
	$replacements[3] = $replacements[2];
	$replacements[4] = '<span class="BL">$1</span>';
	$replacements[5] = $replacements[4];
	$replacements[6] = '<span class="J">$1</span>';
	$replacements[7] = $replacements[6];
	$replacements[8] = '<span class="N">$1</span>';
	$replacements[9] = $replacements[8];
	$replacements[10] = '<span class="G">$1</span>';
	$replacements[11] = $replacements[10];
	$replacements[12] = '<span class="V">$1</span>';
	$replacements[13] = $replacements[12];
	$replacements[14] = '<span class="VP">$1</span>';
	$replacements[15] = $replacements[14];
	$replacements[16] = '<span class="VI">$1</span>';
	$replacements[17] = $replacements[16];
	$replacements[18] = '<span class="ROSE">$1</span>';
	$replacements[19] = $replacements[18];
	$replacements[20] = '<span class="CH">$1</span>';
	$replacements[21] = $replacements[20];
	$replacements[22] = '<span class="T">$1</span>';
	$replacements[23] = $replacements[22];
	$replacements[24] = '<span class="CR">$1</span>';
	$replacements[25] = $replacements[24];
	$replacements[26] = '<span class="CV">$1</span>';
	$replacements[27] = $replacements[26];
	$replacements[28] = '<span class="CJ">$1</span>';
	$replacements[29] = $replacements[28];

	$str = preg_replace($patterns, $replacements, $str);

	return $str;
}

function removeTags($str) {
	return preg_replace('/<[^>]*?>/s', '', $str);
}

function translations($str, $idPost) {

	preg_match_all("/(?:\s|<br\/?>)*(?:\[#(?:br|cn|en|es|fr|hu|nl|pl|ru|tr|vk|id|ro|de)\](?:\s|<br\/?>)*.*?(?:\s|<br\/?>)*\[\/#(?:br|cn|en|es|fr|hu|nl|pl|ru|tr|vk|id|ro|de)\](?:\s|<br\/?>)*)+(?:\s|<br\/?>)*/si", $str, $blocMultiLang);

	for($iBlocMulti = 0; $iBlocMulti < count($blocMultiLang[0]); $iBlocMulti++) {

		$newStr = '<div id="multilang'.$idPost.'-'.($iBlocMulti+1).'">';

		preg_match_all("/\[#(br|cn|en|es|fr|hu|nl|pl|ru|tr|vk|id|ro|de)\](?:\s|<br\/?>)*(.*?)(?:\s|<br\/?>)*\[\/#\g1\]/si", $blocMultiLang[0][$iBlocMulti], $blocLang);

		$newStr .= '<div class="flags">';
		for($iBloc = 0; $iBloc < count($blocLang[1]); $iBloc++) {
			$newStr .= '<img src="images/'.$blocLang[1][$iBloc].'.png" alt="'.strtoupper($blocLang[1][$iBloc]).'" class="flagImg" />';
		}
		$newStr .= '</div>';
		for($iBloc = 0; $iBloc < count($blocLang[1]); $iBloc++) {
			$isSelected = ($iBloc == 0) ? "flag selected" : "flag";
			$newStr .= '<div id="flag'.strtoupper($blocLang[1][$iBloc]).$idPost.'-'.($iBlocMulti+1).'" class="'.$isSelected.'">';
			$newStr .= $blocLang[2][$iBloc];
			$newStr .= "</div>";
		}

		$newStr .= "</div>";

		$str = str_replace($blocMultiLang[0][$iBlocMulti], $newStr, $str);
	}
	
	return $str;
}

function replaceHTML($str) {

	$patterns[0] = '/&lt;(textformat.*?)&gt;(.*?)&lt;\/textformat&gt;/s';
	$patterns[1] = '/&lt;(img.*?)\/&gt;/s';
	$patterns[2] = '/&lt;br\s*\/?&gt;/s';
	$patterns[3] = '/&lt;(b.*?)&gt;(.*?)&lt;\/b&gt;/s';
	$patterns[4] = '/&lt;(font.*?)&gt;(.*?)&lt;\/font&gt;/s';
	$patterns[5] = '/&lt;(ul.*?)&gt;(.*?)&lt;\/ul&gt;/s';
	$patterns[6] = '/&lt;(li.*?)&gt;(.*?)&lt;\/li&gt;/s';
	$patterns[7] = '/&lt;(u.*?)&gt;(.*?)&lt;\/u&gt;/s';
/*	$patterns[8] = '/&lt;(\/?BV)&gt;/';
	$patterns[9] = '/&lt;(\/?R)&gt;/';
	$patterns[10] = '/&lt;(\/?BL)&gt;/';
	$patterns[11] = '/&lt;(\/?J)&gt;/';
	$patterns[12] = '/&lt;(\/?N)&gt;/';
	$patterns[13] = '/&lt;(\/?G)&gt;/';
	$patterns[14] = '/&lt;(\/?V)&gt;/';
	$patterns[15] = '/&lt;(\/?VP)&gt;/';
	$patterns[16] = '/&lt;(\/?VI)&gt;/';
	$patterns[17] = '/&lt;(\/?ROSE)&gt;/';
	$patterns[18] = '/&lt;(\/?CH)&gt;/';
	$patterns[19] = '/&lt;(\/?T)&gt;/';
	$patterns[20] = '/&lt;(\/?CR)&gt;/';
	$patterns[21] = '/&lt;(\/?CV)&gt;/';
	$patterns[22] = '/&lt;(\/?CJ)&gt;/';*/
	$replacements[0] = '<$1>$2</textformat>';
	$replacements[1] = '<$1/>';
	$replacements[2] = '<br />';
	$replacements[3] = '<$1>$2</b>';
	$replacements[4] = '<$1>$2</font>';
	$replacements[5] = '<$1>$2</ul>';
	$replacements[6] = '<$1>$2</li>';
	$replacements[7] = '<$1>$2</u>';
/*	$replacements[8] = '<$1>';
	$replacements[9] = '<$1>';
	$replacements[10] = '<$1>';
	$replacements[11] = '<$1>';
	$replacements[12] = '<$1>';
	$replacements[13] = '<$1>';
	$replacements[14] = '<$1>';
	$replacements[15] = '<$1>';
	$replacements[16] = '<$1>';
	$replacements[17] = '<$1>';
	$replacements[18] = '<$1>';
	$replacements[19] = '<$1>';
	$replacements[20] = '<$1>';
	$replacements[21] = '<$1>';
	$replacements[22] = '<$1>';*/
	
	$str = preg_replace($patterns, $replacements, $str);



	$patterns = array();

	$patterns[0] = '/([^\.!?;:])\s*<br\/?>\s*([a-z])/s';
	$patterns[1] = '/\[color=(#[a-zA-Z0-9]+)\](.*?)\[\/color\]/s';
	$patterns[2] = '/\[url\](.*?)\[\/url\]/s';
	$patterns[3] = '/\[url=(http[^\]]+)\](.*?)\[\/url\]/s';
	$patterns[4] = '/\[img\](.*?)\[\/img\]/s';
	$patterns[5] = '/(?:\s|<br\/?>)*\[quote\](.*?)\[\/quote\](?:\s|<br\/?>)*/s';
	$patterns[6] = '/(?:\s|<br\/?>)*\[quote=([^\]]+)\](.*?)\[\/quote\](?:\s|<br\/?>)*/s';

	$replacements = array();
	$replacements[0] = '$1 $2';
	$replacements[1] = '<span style="color: $1;">$2</span>';
	$replacements[2] = '<a href="$1">$1</a>';
	$replacements[3] = '<a href="$1">$2</a>';
	$replacements[4] = '<img src="$1" alt="" /><br/>';
	$replacements[5] = "<figure class=\"quotebox\"><blockquote><p>$1</p></blockquote></figure>";
	$replacements[6] = "<figure class=\"quotebox\"><figcaption>$1 a &eacute;crit :</figcaption><blockquote><p>$2</p></blockquote></figure>";

	$str = preg_replace($patterns, $replacements, $str);

	// Replace <font> tags
	if(preg_match('/(<font(?:\s+face="[^"]+")?(?:\s+size="(\d+)")?(?:\s+face="[^"]+")?(?:\s+color="(#[a-zA-Z0-9]+)")?\s*>)/', $str, $matches, PREG_OFFSET_CAPTURE)) {
		while(preg_match('/(<font(?:\s+face="[^"]+")?(?:\s+size="(\d+)")?(?:\s+face="[^"]+")?(?:\s+color="(#[a-zA-Z0-9]+)")?\s*>)/', $str, $matches, PREG_OFFSET_CAPTURE)) {
		
			$span = '<span';
			
			$size = $matches[2][0];
			$color = $matches[3][0];
		
			if(strlen($size) > 0  || strlen($color) > 0) {
				$span .= ' style="';
			
				if(strlen($size) > 0) $span .= 'font-size: ' . (intval($size)/12) . 'em;';
				if(strlen($color) > 0) $span .= 'color: ' . $color . ';';
				
				$span .= '"';
			}
			
			$span .= '>';

			$str = str_replace($matches[1][0], $span, $str);
		}
		
		$str = str_replace('</font>', '</span>', $str);
	}

	// Linkify
	$str = preg_replace('/(?>\b(http:\/\/www\.transformice\.com\/forum\/(\?s=(\d+)(?:&amp;p=\d*)?)))\s*(?!<\/a>)/si','<a href="./$2">Topic $3</a><a href="$1"><img src="images/ext.png" alt="Topic $3" class="ext" /></a>', $str);

	return $str;
}
?>