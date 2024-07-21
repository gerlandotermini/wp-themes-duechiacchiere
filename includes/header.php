<?php
	ob_start();
	$default_category = 'ingresso';
	$title_tag = 'h2';
	
	$og_meta = array(
		'title' => '',
		'description' => get_bloginfo( 'description' ),
		'type' => 'website',
		'url' => home_url( $GLOBALS[ 'wp' ]->request ),
		'image' => ''
	);

	$heading_title = '';
	$heading_hidden = true;
	$schema_code = '';

	if ( is_single() ) {
		// Redirect video attachments to their post, if their description is empty
		if ( $GLOBALS[ 'post' ]->post_type == 'attachment' && strpos( $GLOBALS[ 'post' ]->post_mime_type, 'video' ) !== false && empty( $GLOBALS[ 'post' ]->post_content ) && !empty( $GLOBALS[ 'post' ]->post_parent ) ) {
			header( 'HTTP/1.1 301 Moved Permanently' );
			header( 'Location: ' . get_permalink( $GLOBALS[ 'post' ]->post_parent ) );
			exit;
		}
		
		$categories = wp_get_post_terms( $GLOBALS[ 'post' ]->ID, 'category', 'orderby=id' );
		if ( count( $categories ) > 0 ) {
			$default_category = $categories[ 0 ]->slug;
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
			$og_meta[ 'image' ] = get_template_directory_uri() . '/assets/img/camu/ingresso.webp';
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

			if ( strpos( $og_meta[ 'image' ], 'camu/ingresso.webp' ) ) {
				$og_meta[ 'image' ] = get_template_directory_uri() . '/assets/img/video-thumbnail.webp';
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
							"url": "' . get_template_directory_uri() . '/assets/img/camu/ingresso.webp"
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
				$ancestor_category = get_category( $ancestors[ count( $ancestors ) - 1 ] );
				$default_category = $ancestor_category->slug;
			}
			else {
				$default_category = $category->slug;
			}

			$og_meta[ 'title' ] = "Archivio degli articoli in {$category->name}";
			$og_meta[ 'description' ] = "Sfoglia gli articoli conservati in {$category->name}";
		}
		else if ( is_date() ) {
			$month_names = array(
				'gennaio',
				'febbraio',
				'marzo',
				'aprile',
				'maggio',
				'giugno',
				'luglio',
				'agosto',
				'settembre',
				'ottobre',
				'novembre',
				'dicembre'
			);
		
			$date_string = '';
			if ( is_year() ) {
				$date_string = "l'anno " . get_query_var( 'year' );
			}
			else if ( is_month() ) {
				$date_string = ' mese di ' . $month_names[ intval( get_query_var( 'monthnum' ) ) - 1 ] . ' ' . get_query_var( 'year' );
			}
			else if ( is_day() && !empty( $month_names[ intval( get_query_var( 'monthnum' ) ) - 1 ] ) ) {
				$date_string = ' ' . intval( get_query_var( 'day' ) ) . ' ' . $month_names[ intval( get_query_var( 'monthnum' ) ) - 1 ];
				$date_year = intval( get_query_var( 'year' ) );
				if ( !empty( $date_year ) ) {
					$date_string .= ' ' . $date_year;
				}
				else {
					$heading_title = 'Post scritti il ' . intval( get_query_var( 'day' ) ) . ' ' . $month_names[ intval( get_query_var( 'monthnum' ) ) - 1 ] . ' negli anni precedenti';
				}
			}

			$og_meta[ 'title' ] = "Archivio del$date_string";
			$og_meta[ 'description' ] = "Sfoglia gli articoli del$date_string in ordine cronologico inverso";
		}
	}
	else if ( is_404() ) {
		$og_meta[ 'title' ] = 'Pagina non trovata';
		$default_category = '404';
	}
	else if ( is_front_page() ) {
		$heading_title = 'Articoli recenti';
		$og_meta[ 'title' ] = 'Due chiacchiere, la dimora virtuale di Camu';
	}
	else if ( is_page() ) {
		$title_tag = 'h1';
		$og_meta[ 'title' ] = get_the_title();

		if ( !empty( $GLOBALS[ 'post' ]->post_excerpt ) ) {
			$og_meta[ 'description' ] = str_replace( '"', "'", strip_tags( $GLOBALS[ 'post' ]->post_excerpt ) );
		}
	}
	else if ( is_search() && !empty( $_GET[ 's' ] ) ) {
		$search_keywords = duechiacchiere::scrub_field( $_GET[ 's' ], false );
		$og_meta[ 'title' ] = "Risultati della ricerca per: <strong>$search_keywords</strong>";
		$heading_hidden = false;

		if ( !have_posts() ) {
			$default_category = '404';
		}
	}

	if ( is_paged() ) {
		$og_meta[ 'title' ] .= ", pagina " . get_query_var( 'paged' );
	}

	if ( $title_tag != 'h1' ) {
		$heading_title = '<h1' . ( $heading_hidden ? ' class="visually-hidden"' : '' ) .'>' . ( !empty( $heading_title ) ? $heading_title : $og_meta[ 'title' ] ) . '</h1>';
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

	$bg_month = strtolower( isset( $_GET[ 'colors' ] ) ? duechiacchiere::scrub_field( $_GET[ 'colors' ] ) : date( 'M' ) );
	if ( !in_array( $bg_month, array( 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec' ) ) ) {
		$bg_month = strtolower( date( 'M' ) );
	}

	if ( $default_category == 'ingresso' ) {
		switch ( $bg_month ) {
			case 'jun':
			case 'jul':
			case 'aug':
				$default_category .= '-manichecorte';
				break;

			case 'dec':
				$default_category .= '-natale';
				break;

			default:
				break;
		}
	}
	if ( date_i18n( 'md' ) == '0401' || !empty( $_GET[ 'fool' ] ) ) {
		$bg_month .= ' april-fools';
	}
?><!DOCTYPE html>
<html lang="it" dir="ltr">
<head>
	<!-- BEGIN: Technical info -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
	$parsed_url = parse_url( $_SERVER[ 'REQUEST_URI' ] );
	if ( !empty( $parsed_url[ 'query' ] ) ) {
		echo '<meta name="robots" content="noindex,nofollow">';
	}
?>
	<!-- END: Technical info -->

	<!-- BEGIN: Editorial info -->
	<title><?= $og_meta[ 'title' ]; ?></title>
	<meta name="author" content="camu">
<?php
	if ( !empty( $og_meta[ 'description' ] ) ) {
		echo '<meta name="description" content="' . $og_meta[ 'description' ] . '">' . "\n";
	}
	foreach ( $og_meta as $meta_key => $meta_value ) {
		if ( !empty( $meta_value ) ) {
			echo '<meta property="og:' . $meta_key . '" content="' . $meta_value . '">' . "\n";
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

	<!-- BEGIN: Favicon -->
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri() ?>/assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?php echo get_stylesheet_directory_uri() ?>/assets/img/favicon/android-chrome.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() ?>/assets/img/favicon/apple-touch-icon.png">
	<!-- END: Favicon -->

	<!-- BEGIN: Font Preloading -->
	<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/assets/fonts/outfit.woff2" as="font" type="font/woff2" crossorigin />
	<!-- END: Font Preloading -->

	<!-- BEGIN: RSS feed -->
	<link rel="alternate" type="application/rss+xml" title="Articoli del blog" href="<?= get_bloginfo( 'url' ) ?>/feed">
	<link rel="alternate" type="application/rss+xml" title="Scrissi oggi" href="<?= get_bloginfo( 'url' ) ?>/feed/scrissi-oggi">
	<!-- END: RSS feed -->

	<!-- BEGIN: WP_Head -->
	<?php wp_head(); ?>
	<!-- END: WP_Head -->
</head>

<body <?= body_class( 'theme-' . $bg_month . ' category-' . $default_category ) ?> id="body">
	<a class="skip" id="page-top" href="#content">salta al contenuto</a>
	<header id="header-container">
		<div id="branding">
			<div id="camu" aria-label="un ragazzo con la testa appoggiata in avanti sulle braccia conserte"></div>
			<a id="name" href="/" aria-label="Torna alla pagina iniziale del sito">due chiacchiere</a>
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