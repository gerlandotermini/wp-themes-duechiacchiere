<!DOCTYPE html>
<html lang="it" xml:lang="it" dir="ltr">
<head>
	<!-- BEGIN: Technical info -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- END: Technical info -->

	<!-- BEGIN: Editorial info -->
	<meta name="author" content="camu" />
<?php
	$category_boy = 'ingresso';
	if ( is_single() ):
		if ( has_post_thumbnail( $GLOBALS[ 'post' ]->ID ) ) {
			$page_image = wp_get_attachment_image_src( get_post_thumbnail_id( $GLOBALS[ 'post' ]->ID ), 'medium' );
		}
		else {
			$page_image = get_template_directory_uri() . '/img/boy_ingresso.png';
		}
		
		if ( $page_description = $GLOBALS[ 'post' ]->post_excerpt ) {
			$page_description = str_replace( '"', "'", strip_tags( $GLOBALS[ 'post' ]->post_excerpt ) );
		}
		else {
			$page_description = get_bloginfo( 'description' );
		}

		if ( $GLOBALS[ 'post' ]->comment_count > 0 ) {
			$page_description .= '. Numero di commenti: '.$post->comment_count;
		}

		$current_categories = wp_get_post_terms( $GLOBALS[ 'post' ]->ID, 'category', 'orderby=id' );
		if ( count( $current_categories ) > 0 ) {
			$category_boy = $current_categories[ 0 ]->slug;
		}
		
?>
	<meta property="og:title" content="<?php echo the_title(); ?>"/>
	<meta property="og:description" content="<?php echo $page_description; ?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo the_permalink(); ?>"/>
	<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
	<meta property="og:image" content="<?php echo $page_image; ?>"/>
<?php endif; ?>
	<!-- END: Editorial info -->

	<!-- BEGIN: Styles and fonts -->
	<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style.css" type="text/css" media="screen,print" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles&family=Outfit:wght@200;400&display=swap" rel="stylesheet">
	<!-- END: Styles and fonts -->

	<!-- BEGIN: RSS feed -->
	<link rel="alternate" type="application/rss+xml" title="Articoli del blog" href="https://feeds.feedburner.com/duechiacchiere" />
	<!-- END: RSS feed -->

	<!-- BEGIN: WP_Head -->
	<?php 
		ob_start();
		wp_head();
		$header = ob_get_contents();
		ob_end_clean();
		echo preg_replace("/\n</", "\n\t<", $header );
		echo "\n"; ?>
	<!-- END: WP_Head -->
</head>

<body class="december">
	<a class="skip" href="#content">Skip to content</a>

	<header id="header-container">
		<section>
			<a id="logo" href="/" title="Torna alla pagina iniziale del sito"><img src="<?= get_template_directory_uri() ?>/img/boy/<?= $category_boy ?>.png" alt="un ragazzo con la testa appoggiata in avanti sulle braccia conserte" width="200" height="120" /></a>
			<p id="site-name"><?= get_bloginfo( 'name' ) ?></p>
		</section>

		<nav>
			<ul>
				<li><a href="#">Menu item</a></li>
				<li><a href="#">Menu item</a></li>
				<li><a href="#">Menu item</a></li>
			</ul>
		</nav>
	</header>



<?php /*	




















<div id="page-wrapper">
	<div id="header">
		<?php if (!is_home() || is_paged()): ?><a href="/" title="torna alla pagina principale"><?php endif ?>
		
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

	*/