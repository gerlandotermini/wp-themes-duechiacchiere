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
	$intro_title = '';
	$title_tag = 'h2';

	if ( is_single() ) {
		if ( has_post_thumbnail( $GLOBALS[ 'post' ]->ID ) ) {
			$page_image = wp_get_attachment_image_src( get_post_thumbnail_id( $GLOBALS[ 'post' ]->ID ), 'medium' );
		}
		else {
			$page_image = get_template_directory_uri() . '/img/boys/ingresso.webp';
		}
		
		if ( $page_description = $GLOBALS[ 'post' ]->post_excerpt ) {
			$page_description = str_replace( '"', "'", strip_tags( $GLOBALS[ 'post' ]->post_excerpt ) );
		}
		else {
			$page_description = get_bloginfo( 'description' );
		}

		if ( $GLOBALS[ 'post' ]->comment_count > 0 ) {
			$page_description .= '. Numero di commenti: ' . $GLOBALS[ 'post' ]->comment_count;
		}

		$categories = wp_get_post_terms( $GLOBALS[ 'post' ]->ID, 'category', 'orderby=id' );
		if ( count( $categories ) > 0 ) {
			$category_boy = $categories[ 0 ]->slug;
		}

		$title_tag = 'h1';
?>
	<meta property="og:title" content="<?php echo the_title(); ?>"/>
	<meta property="og:description" content="<?php echo $page_description; ?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="<?php echo the_permalink(); ?>"/>
	<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
	<meta property="og:image" content="<?php echo $page_image; ?>"/>
<?php }
	else if ( is_category() ) {
		$category = get_queried_object();
		$ancestors = get_ancestors( $category->term_id, 'category' );

		// If this category has ancestors, use the topmost ancestor for the boy
		if ( !empty( $ancestors ) ) {
			$category = get_category( $ancestors[ count( $ancestors ) - 1 ] );
		}

		$category_boy = $category->slug;
		$intro_title = "<h1 class=\"visually-hidden\">Articoli recenti in {$category->name}</h1>";
	}
	else if ( is_404() ) {
		$category_boy = '404';
	}
	else if ( is_front_page() ) {
		$intro_title = "<h1 class=\"visually-hidden\">Articoli recenti</h1>";
	}
	else if ( is_page() ) {
		$title_tag = 'h1';
	}
	else if ( is_search() ) {
		$search_keywords = duechiacchiere::scrub_field( $_GET[ 's' ] );
		$intro_title = "<h1 class=\"visually-hidden\">Risultati della ricerca per: $search_keywords</h1>";
	}

	$bg_month = isset( $_GET[ 'colors' ] ) ? duechiacchiere::scrub_field( $_GET[ 'colors' ] ) : strtolower( date( 'F' ) );
	if ( $category_boy == 'ingresso' ) {
		switch ( $bg_month ) {
			case 'june':
			case 'july':
			case 'august':
				$category_boy .= '-manichecorte';
				break;

			case 'december':
				$category_boy .= '-natale';
				break;

			default:
				break;
		}
	}
?>
	<!-- END: Editorial info -->

	<!-- BEGIN: Google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Outfit:wght@300;500&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<!-- END: Google fonts -->

	<!-- BEGIN: RSS feed -->
	<link rel="alternate" type="application/rss+xml" title="Articoli del blog" href="https://feeds.feedburner.com/duechiacchiere" />
	<!-- END: RSS feed -->

	<!-- BEGIN: WP_Head -->
	<?php wp_head(); ?>
	<!-- END: WP_Head -->
</head>

<body <?= body_class( $bg_month ) ?>>
	<a class="skip" href="#contenuto">Salta al contenuto</a>

	<header id="header-container">
		<div id="branding">
			<a href="/" title="Torna alla pagina iniziale del sito"><img id="logo" src="<?= get_template_directory_uri() ?>/img/boys/<?= $category_boy ?>.webp" alt="un ragazzo con la testa appoggiata in avanti sulle braccia conserte" width="200" height="120" />
			<h2 id="name">due chiacchiere</h2></a>
			<button class="hamburger hamburger--squeeze" type="button" id="mobile-menu-trigger" aria-expanded="false">
				<span class="visually-hidden">Pulsante per aprire e chiudere il menu del sito</span>
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</button>
		</div>

		<nav aria-label="Navigazione primaria" id="primary-menu">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => '',
					'depth' => 2
				) );
				?>
		</nav>
	</header>