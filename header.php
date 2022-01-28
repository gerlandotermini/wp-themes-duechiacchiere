<?php

	$category_boy = 'ingresso';
	$title_tag = 'h2';

	$og_meta = array(
		'title' => '',
		'description' => '',
		'type' => 'website',
		'url' => home_url( $GLOBALS[ 'wp' ]->request ),
		'image' => ''
	);

	$heading_title = '';
	$schema_code = '';

	if ( is_single() ) {
		$categories = wp_get_post_terms( $GLOBALS[ 'post' ]->ID, 'category', 'orderby=id' );
		if ( count( $categories ) > 0 ) {
			$category_boy = $categories[ 0 ]->slug;
		}

		$title_tag = 'h1';

		$og_meta[ 'title' ] = get_the_title();

		if ( !empty( $GLOBALS[ 'post' ]->post_excerpt ) ) {
			$og_meta[ 'description' ] = str_replace( '"', "'", strip_tags( $GLOBALS[ 'post' ]->post_excerpt ) );
		}
		else {
			$post_for_content = $GLOBALS[ 'post' ];
			if ( is_attachment() && empty( $GLOBALS[ 'post' ]->post_content ) ) {
				$post_for_content = get_post_parent( $GLOBALS[ 'post' ]->ID );
			}

			if ( !empty( $post_for_content ) ) {
				$og_meta[ 'description' ] = str_replace( '"', "'", duechiacchiere::get_substr_words( $post_for_content->post_content, 100 ) );
			}
		}
		if ( $GLOBALS[ 'post' ]->comment_count > 0 ) {
			if ( !empty( $og_meta[ 'description' ] ) ) {
				$og_meta[ 'description' ] .= ' - ';	
			}

			$og_meta[ 'description' ] .= 'Numero di commenti: ' . $GLOBALS[ 'post' ]->comment_count;
		}

		$og_meta[ 'type' ] = 'article';
		$og_meta[ 'url' ] = get_permalink();

		$og_meta[ 'image' ] = duechiacchiere::first_post_image( $GLOBALS[ 'post' ]->post_content );
		if ( empty( $og_meta[ 'image' ] ) ) {
			$og_meta[ 'image' ] = get_template_directory_uri() . '/img/boys/ingresso.webp';
			if ( has_post_thumbnail( $GLOBALS[ 'post' ]->ID ) ) {
				$og_meta[ 'image' ] = get_the_post_thumbnail_url( $GLOBALS[ 'post' ]->ID, 'full' );
			}
		}

		if ( is_attachment() ) {
			$video_meta = get_post_meta( $GLOBALS[ 'post' ]->ID , '_wp_attachment_metadata', true );
			$schema_duration = 'PT1S';
			if ( !empty( $video_meta[ 'length' ] ) ) {
				$schema_duration = str_replace ( [ '00H', '00M', '00H', '0' ], '', date( '\P\TH\Hi\Ms\S', $video_meta[ 'length' ] ) );
			}

			if ( strpos( $og_meta[ 'image' ], 'boys/ingresso.webp' ) ) {
				$og_meta[ 'image' ] = get_template_directory_uri() . '/img/video-thumbnail.jpg';
			}

			$schema_code = ',
				{
					"@type": "VideoObject",
					"name": "' . get_the_title() . '",
					"description": "' . $og_meta[ 'description' ] . '",
					"uploadDate": "' . get_the_time( 'c' ) . '",
					"duration": "' . $schema_duration . '",
					"contentUrl": "' . $GLOBALS[ 'post' ]->guid . '",
					"embedUrl": "' . get_the_permalink() . '",
					"thumbnailUrl": "' . $og_meta[ 'image' ] . '"
				}';
		}
		else {
			$schema_code = ',
				{
					"@type": "BlogPosting",
					"mainEntityOfPage": {
						"@type": "WebPage",
						"@id": "' .  get_the_permalink() . '#contenuto"
					},
					"headline": "' . get_the_title() . '",
					"description": "' . $og_meta[ 'description' ]. '",
					"image": "' . $og_meta[ 'image' ] . '",
					"author": {
						"@type": "Person",
						"name": "camu",
						"url": "' . get_bloginfo( 'url' ) . '"
					},  
					"publisher": {
						"@type": "Organization",
						"name": "' . get_bloginfo( 'name' ) . '",
						"logo": {
							"@type": "ImageObject",
							"url": "' . get_template_directory_uri() . '/img/boys/ingresso.webp"
						}
					},
					"datePublished": "' . get_the_time( 'c' ) . '",
					"dateModified": "' . get_the_modified_date( 'c' ) . '",
					"inLanguage": "it-IT",
					"potentialAction": [
						{
							"@type": "ReadAction",
							"target": [
								"' . get_the_permalink() . '"
							]
						}
					]
				}';
		}
	}
	else if ( is_archive() ) {
		if ( is_category() ) {
			$category = get_queried_object();
			$ancestors = get_ancestors( $category->term_id, 'category' );

			// If this category has ancestors, use the topmost ancestor for the boy
			if ( !empty( $ancestors ) ) {
				$category = get_category( $ancestors[ count( $ancestors ) - 1 ] );
			}

			$category_boy = $category->slug;

			$og_meta[ 'title' ] = "Articoli recenti in {$category->name}";
			$og_meta[ 'description' ] = "Sfoglia gli articoli conservati in questa stanza.";
			$heading_title = "<h1 class=\"visually-hidden\">{$og_meta[ 'title' ]}</h1>";
		}

		$og_meta[ 'type' ] = 'website';

		if ( home_url( $GLOBALS[ 'wp' ]->request ) != get_bloginfo( 'url' ) ) {
			$schema_code = ',
				{
					"@type": "CollectionPage",
					"@id": "' . home_url( $GLOBALS[ 'wp' ]->request ) . '#contenuto",
					"url": "' . home_url( $GLOBALS[ 'wp' ]->request ) . '",
					"name": "' . ucfirst( strip_tags( get_the_archive_title() ) ) . '",
					"isPartOf": {
						"@id": "' . get_bloginfo( 'url' ) . '#contenuto"
					},
					"inLanguage": "it-IT",
					"potentialAction": [
						{
							"@type": "ReadAction",
							"target": [
								"' . home_url( $GLOBALS[ 'wp' ]->request ) . '"
							]
						}
					]
				}';
		}
	}
	else if ( is_404() ) {
		$category_boy = '404';
	}
	else if ( is_front_page() ) {
		$heading_title = "<h1 class=\"visually-hidden\">Articoli recenti</h1>";
	}
	else if ( is_page() ) {
		$title_tag = 'h1';
	}
	else if ( is_search() ) {
		$search_keywords = duechiacchiere::scrub_field( $_GET[ 's' ] );
		$heading_title = "<h1 class=\"visually-hidden\">Risultati della ricerca per: $search_keywords</h1>";
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
?><!DOCTYPE html>
<html lang="it" xml:lang="it" dir="ltr">
<head>
	<!-- BEGIN: Technical info -->
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
<?php $parsed = parse_url( $_SERVER[ 'REQUEST_URI' ] ); if ( !empty( $parsed[ 'query' ] ) ): ?><meta name="robots" content="noindex,nofollow"><?php endif ?>
	<!-- END: Technical info -->

	<!-- BEGIN: Editorial info -->
	<meta name="author" content="camu"/>
<?php
	if ( !empty( $og_meta[ 'description' ] ) ) {
		echo '<meta name="description" content="' . $og_meta[ 'description' ] . '">';
	}
	foreach ( $og_meta as $meta_key => $meta_value ) {
		if ( !empty( $meta_value ) ) {
			echo '<meta property="og:' . $meta_key . '" content="' . $meta_value . '"/>';
		}
	}
?>
	<!-- END: Editorial info -->

	<!-- BEGIN: Schema.org definitions -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@graph": [
			{
				"@type": "WebSite",
				"@id": "<?= get_bloginfo( 'url' ) ?>#contenuto",
				"url": "<?= get_bloginfo( 'url' ) ?>",
				"name": "<?= get_bloginfo( 'name' ) ?>",
				"description": "<?= get_bloginfo( 'description' ) ?>",
				"potentialAction": [
					{
						"@type": "SearchAction",
						"target": {
							"@type": "EntryPoint",
							"urlTemplate": "<?= get_bloginfo( 'url' ) ?>?s={search_term_string}"
						},
						"query-input": "required name=search_term_string"
					}
				],
				"inLanguage": "it-IT"
			}<?= $schema_code ?>
		]
	}
	</script>
	<!-- END: Schema.org definitions -->

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