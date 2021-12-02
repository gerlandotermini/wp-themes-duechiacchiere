<?php
// if (empty($_COOKIE['halloween'])){ setcookie("halloween", 'true'); $do_halloween = 1; }

// Initialize all the values
$category_boy = 'ingresso';
$page_description = 'due chiacchiere: idee sempre fresche da gustare. Ricette, computer, sentimenti, sport e relax';
$my_title = wp_title('',false,'');
$tag_logo = 'p';
$tag_name_open = 'a href="/" title="torna alla pagina principale"'; $tag_name_close = 'a';
$use_lightbox = false;
$doctype = 'strict';

$category_descriptions = array(
	'ingresso' => 'ingresso: la prima impressione, che condiziona tutte quelle successive',

	'angolo-pc' => 'le avventure con il mio compagno elettronico',
	'accessibile' => 'il web a misura di disabili e motori di ricerca',
	'accessori' => 'periferiche e programmi per il computer',
	'eventi' => 'convegni ed incontri intorno al mondo delle nuove tecnologie',
	'riflessioni' => 'un cassetto per i miei pensieri informatici',
	'siti' => 'alcune delle pagine che visito durante le mie scorribande quotidiane per la rete',
	'webtools' => 'gli attrezzi del mestiere per chi sviluppa siti web',

	'biblioteca' => 'il libro, un fedele amico che non tradisce mai',
	'inglese' => 'modi di dire curiosi della lingua di albione',
	'racconti' => 'storie inventate, o forse no',

	'cucina' => 'per arrivare al cuore di una persona, bisogna passare per il suo stomaco',
	'altro' => 'idee, condimenti, riflessioni sul mondo della cucina',
	'contorni' => 'un pranzo non &egrave; completo senza un contorno che lo insaporisca ed esalti',
	'dolci' => 'come deliziare il proprio palato e quello degli amici',
	'primi' => 'pasta, zuppe, risotti e tanto altro',
	'secondi' => 'piatti a base di carne, pesce o verdure... veloci e genuini',
	'siti-cucina' => 'altre idee, altri piccoli e grandi anfratti nella rete culinaria internazionale',

	'ripostiglio' => 'una zona spesso inesplorata che contiene, stratificata, la storia della casa',
	'cartoline' => 'scatti presi in prestito dalla mia enorme collezione fotografica',
	'musica' => 'uno spunto di tanto in tanto, per tradurre il testo di una canzone',
	'umorismo' => 'la vita va presa alla leggera, quando le cose vanno storte e tutto sembra essere contro di noi',

	'salotto' => 'discutere amabilmente davanti ad un camino ed un bicchiere di buon vino',
	'interviste' => 'la blogosfera a confronto, due scrittori alla volta',
	'viaggi' => 'racconti del nostro girovagare per il mondo',

	'angolo-tv' => 'il nuovo agente primario di contatto con la realt&agrave;',
	'sport' => 'sarebbe meglio correre che guardare la tv, ma vista la pigrizia mi accontento di pigiare i tasti del telecomando',

	'zona-notte' => 'il lettone di casa come posto per chiacchierare prima di addormentarsi'
);

if (is_single() || is_page()){
	if (strpos($post->post_content, '<iframe') !== false) $doctype = 'transitional';
	$page_description = get_post_meta($post->ID, 'my_description', true);
	$page_description = (!empty($page_description))?$page_description:trim(wp_title('',false,''));
	if ($post->comment_count > 0) $page_description .= '. Numero di commenti: '.$post->comment_count;
	$page_keywords = get_post_meta($post->ID, 'my_keywords', true);
	$use_lightbox = (get_post_meta($post->ID, 'use_lightbox', true) == 'yes');
	
	if (is_single()){
		// Determine the category to show the 'right' boy for this context
		$current_post_categories = wp_get_post_terms($post->ID, 'category', 'orderby=id');
		if (count($current_post_categories) > 1){
			$parent_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
			$current_category_name = $current_post_categories[1]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[1]->slug, $current_post_categories[1]->name);
			$category_boy = $current_post_categories[0]->slug;
		}
		elseif (count($current_post_categories) == 1){
			$current_category_name = $current_post_categories[0]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
			$category_boy = $current_post_categories[0]->slug;
		}
	}
}

if (is_category()){
	$current_category_id = get_query_var('cat');
	$current_category = get_category($current_category_id);
	$category_boy = $current_category->category_nicename;
	$current_category_name = $current_category->cat_name;
	$current_category_link = duechiacchiere_get_category_link($current_category->category_nicename, $current_category->cat_name);
	if ($current_category->parent != 0){
		$parent_category = get_category($current_category->parent);
		$category_boy = $parent_category->category_nicename;
		$parent_category_link = duechiacchiere_get_category_link($parent_category->category_nicename, $parent_category->cat_name);
	}
	
	$page_description = isset($category_descriptions[$current_category->category_nicename])?$category_descriptions[$current_category->category_nicename]:'idee sempre fresche da gustare, in diretta dagli Stati Uniti';
	if (have_posts()) $my_title .= " - due chiacchiere";
}
if (is_date()){
	$specific_date = (is_day()?'del '.get_the_time('j F Y'):(is_month()?'di '.get_the_time('F Y'):'del '.get_the_time('Y')));
	$my_title = $page_description = 'archivio '.$specific_date;
}
if (is_front_page() && !is_paged()){
	$tag_logo = 'h1'; 
	$tag_name_open = 'span'; $tag_name_close = 'span';
}
$duechiacchiere_cpage = get_query_var('cpage');
$duechiacchiere_paged = get_query_var('paged');
if (!empty($duechiacchiere_paged) || !empty($duechiacchiere_cpage)){
	if (empty($duechiacchiere_paged)) 
		$page_number = " - pagina $duechiacchiere_cpage dei commenti ";
	else
		$page_number = " - pagina ".$duechiacchiere_paged." dell'archivio";
	if (empty($my_title)) $my_title = 'archivio generale';
	$page_description .= $page_number;
	$my_title .= $page_number;
}
if (empty($my_title)) $my_title = 'due chiacchiere';

$duechiacchiere_allowed_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$duechiacchiere_style = (!empty($_GET['month']) && in_array($_GET['month'], $duechiacchiere_allowed_months))?$_GET['month']:date('F');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 <?php echo ucfirst($doctype) ?>//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-<?php echo $doctype ?>.dtd">
<html lang="it" xml:lang="it" dir="ltr" xmlns="https://www.w3.org/1999/xhtml">
<head>
	<title><?= trim($my_title) ?></title>

	<!-- BEGIN: Information about this blog -->
	<link href="https://dublincore.org/2008/01/14/dcterms.rdf" rel="schema.DCTERMS" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon.png" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="cache-control" content="max-age=2073600" />
	<meta http-equiv="content-language" content="it" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta name="author" content="camu" />
	<meta name="DC.format" content="text/html" />
	<meta name="DC.contributor" content="camu" />
	<meta name="DC.language" content="it" />
<?php if (is_singular() && ('https://www.duechiacchiere.it'.$_SERVER['REQUEST_URI'] != get_permalink())) echo "	<link rel='canonical' href='".get_permalink()."' />\n" ?>
	<!-- END: Information about this blog -->

	<!-- BEGIN: Mobile-specific META -->
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width" />
	<!-- END: Mobile-specific META -->

	<!-- BEGIN: Information about this page -->
<?php if ( !empty($page_keywords) ) echo "	<meta name='keywords' content='$page_keywords' />\n" ?>	<meta name="description" content="<?= $page_description ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="DC.title" content="due chiacchiere" />
	<meta name="DC.description" content="<?= $page_description ?>" />
	<!-- END: Information about this page -->

	<!-- BEGIN: Styles -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="https://www.duechiacchiere.it/content/themes/duechiacchiere/style.css" type="text/css" media="screen,print" />
	<link rel="stylesheet" href="https://www.duechiacchiere.it/content/themes/duechiacchiere/months/css/<?= $duechiacchiere_style ?>.css" type="text/css" media="screen" />
	<!--[if lt IE 8]>
		<link rel="stylesheet" href="https://www.duechiacchiere.it/content/themes/duechiacchiere/style-ie7.css" type="text/css" media="screen,print" />
	<![endif]-->
	<!-- END: Styles -->
<?php if ( $use_lightbox ): ?>

	<!-- BEGIN: Lightbox -->
	<link rel="stylesheet" href="https://www.duechiacchiere.it/content/themes/duechiacchiere/lightbox/css/slimbox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="https://www.duechiacchiere.it/content/themes/duechiacchiere/lightbox/js/mootools.js"></script>
	<script type="text/javascript" src="https://www.duechiacchiere.it/content/themes/duechiacchiere/lightbox/js/slimbox.js"></script>
	<!-- END: Lightbox -->
<?php endif ?>
	
	<!-- BEGIN: RSS feeds and more -->
	<link rel="alternate" type="application/rss+xml" title="Articoli del blog" href="https://feeds.feedburner.com/duechiacchiere" />
	<link rel="pingback" href="https://www.duechiacchiere.it/xmlrpc.php" />
	<?php wp_head(); if (function_exists('duechiacchiere_shortlink_wp_head')) duechiacchiere_shortlink_wp_head(); ?>
	<!-- END: RSS feeds and more -->
</head>
<body>
<div id="page-wrapper">
	<div id="header">
		<?php if (!is_home() || is_paged()): ?><a href="/" title="torna alla pagina principale"><?php endif ?><img id="boy" src="https://www.duechiacchiere.it/content/themes/duechiacchiere/img/boy_<?= $category_boy ?>.png" alt="un ragazzo con la testa appoggiata sulle braccia" width="200" height="120" /><?php if (!is_home() || is_paged()): ?></a><?php endif ?>

		<<?= $tag_logo ?> id="blog-name"><<?= $tag_name_open ?>><span class="browser-hidden">Due chiacchiere</span></<?= $tag_name_close ?>></<?= $tag_logo ?>>
		
		<p class="hidden"><a href="#content">Salta al contenuto</a>.</p>
		<p class="hidden">navigazione</p>
		<ul id="top-navigation">
			<li><a href="/corridoio/ingresso" title="<?= $category_descriptions['ingresso'] ?>">ingresso</a></li>
			<li><a class="expand" href="/corridoio/angolo-pc" title="<?= $category_descriptions['angolo-pc'] ?>">angolo pc</a>
				<ul>
					<li><a href="/corridoio/angolo-pc/accessibile" title="<?= $category_descriptions['accessibile'] ?>">accessibile</a></li>
					<li><a href="/corridoio/angolo-pc/accessori" title="<?= $category_descriptions['accessori'] ?>">accessori</a></li>
					<li><a href="/corridoio/angolo-pc/eventi" title="<?= $category_descriptions['eventi'] ?>">eventi</a></li>
					<li><a href="/corridoio/angolo-pc/riflessioni" title="<?= $category_descriptions['riflessioni'] ?>">riflessioni</a></li>
					<li><a href="/corridoio/angolo-pc/siti" title="<?= $category_descriptions['siti'] ?>">siti</a></li>
					<li><a href="/corridoio/angolo-pc/webtools" title="<?= $category_descriptions['webtools'] ?>">strumenti</a></li>
				</ul></li>
			<li><a class="expand" href="/corridoio/biblioteca" title="<?= $category_descriptions['biblioteca'] ?>">biblioteca</a>
				<ul>
					<li><a href="/corridoio/biblioteca/inglese" title="<?= $category_descriptions['inglese'] ?>">corso d'inglese</a></li>
					<li><a href="/corridoio/biblioteca/racconti" title="<?= $category_descriptions['racconti'] ?>">racconti</a></li>
				</ul></li>
			<li><a class="expand" href="/corridoio/cucina" title="<?= $category_descriptions['cucina'] ?>">cucina</a>
				<ul>
					<li><a href="/corridoio/cucina/primi" title="<?= $category_descriptions['primi'] ?>">primi</a></li>
					<li><a href="/corridoio/cucina/secondi" title="<?= $category_descriptions['secondi'] ?>">secondi</a></li>
					<li><a href="/corridoio/cucina/contorni" title="<?= $category_descriptions['contorni'] ?>">contorni</a></li>
					<li><a href="/corridoio/cucina/dolci" title="<?= $category_descriptions['dolci'] ?>">dolci</a></li>
					<li><a href="/corridoio/cucina/siti-cucina" title="<?= $category_descriptions['siti-cucina'] ?>">siti</a></li>
					<li><a href="/corridoio/cucina/altro" title="<?= $category_descriptions['altro'] ?>">altro</a></li>
				</ul></li>
			<li><a class="expand" href="/corridoio/ripostiglio" title="<?= $category_descriptions['ripostiglio'] ?>">ripostiglio</a>
				<ul>
					<li><a href="/corridoio/ripostiglio/cartoline" title="<?= $category_descriptions['cartoline'] ?>">cartoline</a></li>
					<li><a href="/corridoio/ripostiglio/musica" title="<?= $category_descriptions['musica'] ?>">musica</a></li>
					<li><a href="/corridoio/ripostiglio/umorismo" title="<?= $category_descriptions['umorismo'] ?>">umorismo</a></li>
				</ul></li>
			<li><a class="expand" href="/corridoio/salotto" title="<?= $category_descriptions['salotto'] ?>">salotto</a>
				<ul>
					<li><a href="/corridoio/salotto/interviste" title="<?= $category_descriptions['interviste'] ?>">interviste</a></li>
					<li><a href="/corridoio/salotto/viaggi" title="<?= $category_descriptions['viaggi'] ?>">viaggi</a></li>
				</ul></li>
			<li><a class="expand" href="/corridoio/angolo-tv" title="<?= $category_descriptions['angolo-tv'] ?>">tv e cinema</a>
				<ul>
					<li><a href="/corridoio/angolo-tv/sport" title="<?= $category_descriptions['sport'] ?>">sport</a></li>
				</ul></li>
			<li><a href="/corridoio/zona-notte" title="<?= $category_descriptions['zona-notte'] ?>">zona notte</a></li>
		</ul>
		<!-- END: #top-navigation -->
	</div>
	<div id="container"<?php if (!empty($custom_style)) echo $custom_style ?>>