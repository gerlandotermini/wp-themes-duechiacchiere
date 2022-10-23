<?php

class duechiacchiere {
	public static function init() {
		// This theme uses wp_nav_menu() above and below the main page header
		register_nav_menus( array(
			'primary' => 'Primary Navigation',
			'sidebar' => 'External Links'
		) );

		// Redirect shortlinks (with post id) to the actual canonical URLs
		add_filter( 'template_redirect', array( __CLASS__, 'redirect_post_id_to_canonical_url' ) );

		// Sorry, no Gutenberg allowed
		add_filter( 'use_block_editor_for_post_type', '__return_false' );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ), 100 );

		// Enqueue styles and scripts, except on CSS Naked Day - https://css-naked-day.github.io/
		if ( !self::is_naked_day() ) {
			add_action( 'wp_head', array( __CLASS__, 'print_styles' ) );
			add_action( 'wp_footer', array( __CLASS__, 'print_scripts' ) );
		}

		// Make the main menu more accessible
		add_filter( 'nav_menu_link_attributes', array( __CLASS__, 'nav_menu_link_attributes' ), 10, 4 );
		add_filter( 'walker_nav_menu_start_el', array( __CLASS__, 'walker_nav_menu_start_el' ), 10, 4 );

		// Add nofollow to the monthly archive links in the footer
		add_filter( 'get_archives_link', array( __CLASS__, 'get_archives_link' ) );

		// Customize image HTML wrappers
		add_shortcode( 'caption', array( __CLASS__, 'img_caption_html' ) );

		// Add excerpt to pages
		add_post_type_support( 'page', 'excerpt' );

		// Filter really long comments (spam)
		add_filter( 'preprocess_comment' , array( __CLASS__, 'preprocess_comment' ) );

		// Update cache after a comment is posted or edited or deleted
		add_action( 'comment_post', array( __CLASS__, 'comment_post' ), 10, 3 );
		add_action( 'edit_comment', array( __CLASS__, 'edit_comment' ), 10, 2 );
		add_action( 'trash_comment', array( __CLASS__, 'edit_comment' ), 10, 2 );

		// Move the 'Cancel reply' link next to the button to submit a comment
		add_filter( 'cancel_comment_reply_link', '__return_empty_string' );

		// Don't generate thumbnails, this theme only uses full size
		add_filter( 'intermediate_image_sizes', '__return_empty_array' );

		// Tweak the YouTube and Video oEmbed code
		add_filter( 'embed_oembed_html', array( __CLASS__, 'responsive_youtube_embed' ), 10, 4 );
		add_filter( 'style_loader_src', array( __CLASS__, 'script_loader_src' ), 20, 2 );
		add_filter( 'script_loader_src', array( __CLASS__, 'script_loader_src' ), 20, 2 );

		// Update the sitemap file whenever a new post is published
		add_action( 'transition_post_status', array( __CLASS__, 'transition_post_status' ), 10, 3 );

		// Generate a today's posts feed
		add_feed( 'scrissi-oggi', array( __CLASS__, 'feed_today_in_the_past' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );

		// Customize the TinyMCE Editor
		add_filter( 'mce_external_plugins', array( __CLASS__, 'mce_external_plugins' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'mce_buttons' ) );
		add_filter( 'tiny_mce_before_init', array( __CLASS__, 'tiny_mce_before_init' ) );

		add_action( 'admin_head-post.php', array( __CLASS__, 'admin_head_post' ) );
		add_action( 'admin_head-post-new.php', array( __CLASS__, 'admin_head_post' ) );
		
		// Add a Post List button to the admin bar
		add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 100 );

		// Miscellaneous clean up
		self::_remove_emoji_hooks();
		self::_remove_wp_headers();
	}

	public static function redirect_post_id_to_canonical_url() {
		if ( is_404() ) {
			$canonical = get_permalink( intval( str_replace( '/', '', $_SERVER[ 'REQUEST_URI' ] ) ) );

			if ( !empty( $canonical ) ) {
				wp_redirect( $canonical, 301 );
				return;
			}
		}
	}

	public static function wp_enqueue_scripts() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
		wp_dequeue_style( 'global-styles' );

		wp_enqueue_script( 'comment-reply' );
	}

	public static function print_styles() {
		$css = file_get_contents( get_template_directory() . '/assets/css/style.css' );
		echo '<style>' . str_replace( array( 'themeuri', 'my.site.domain' ), array( get_stylesheet_directory_uri(), parse_url( get_home_url(), PHP_URL_HOST ) ), $css ) . '</style>';
	}

	public static function print_scripts() {
		$js = file_get_contents( get_template_directory() . '/assets/js/script.js' );
		echo '<script>' . str_replace( 'COOKIEHASHVALUE', COOKIEHASH, $js ) . '</script>';
	}
	
	public static function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		// Add [aria-haspopup] and [aria-expanded] to menu items that have children
		$item_has_children = in_array( 'menu-item-has-children', $item->classes );
		if ( $item_has_children ) {
				$atts[ 'aria-haspopup' ] = 'true';
				$atts[ 'aria-expanded' ] = 'false';
		}
	
		return $atts;
	}

	public static function walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
		if ( !empty( $item->description ) ) {
			$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a>' . '<p class="menu-item-description">' . $item->description . '</p>', $item_output );
		}
	
		return $item_output;
	}

	public static function get_archives_link( $link_html = '' ) {
		return str_replace( '<a href=', '<a rel="nofollow" href=',  $link_html );
	}

	public static function img_caption_html( $attr, $content = null ) {
		extract( shortcode_atts( array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'caption' => ''
		), $attr ) );
		
		// New approach implemented in WP 3.4: caption is not an attribute anymore
		if ( empty( $caption ) ) {
			if ( substr( $content, 0, 2 ) == '<i' ) {
				list( $image, $caption ) = explode( '/>', $content );
				$separator = "/>";
			}
			else {
				list( $image, $caption ) = explode( '/a>', $content );
				$separator = 'span></span></a>';
			}
		}
		else {
			$image = $content;
			$separator = '';
		}
	
		if ( 1 > (int) $width || empty( $caption ) )
			return $content;
	
		if ( $id ) {
			$id = 'id="' . esc_attr( $id ) . '" ';
		}
		$caption = trim( $caption );

		return "<figure $id class=\"wp-caption $align\" style=\"max-width:{$width}px\">$image$separator <span class=\"wp-caption-text\" aria-hidden=\"true\">$caption</span></figure>";
	}

	public static function preprocess_comment( $commentdata = array() ) {
		if ( count( preg_split('/\n/', $commentdata[ 'comment_content' ] ) ) > 100 ) {
			die( 'Pussa via brutta bertuccia' );
		};

		return $commentdata;
	}

	public static function comment_post( $comment_id = 0, $comment_approved = 0, $commentdata = array() ) {
		// Delete cached version of this page (footer will regenerate it) and refresh homepage
		if ( !empty( $commentdata[ 'comment_post_ID' ] ) ) {
			$permalink_path = str_replace( home_url(), '', get_permalink( $commentdata[ 'comment_post_ID' ] ) );
			duechiacchiere::delete_from_cache( $permalink_path );
		}
	}

	public static function edit_comment( $comment_id = 0, $comment = '' ) {
		duechiacchiere::comment_post( $comment_id, 1, array( 'comment_post_ID' => $comment->comment_post_ID ) );
	}

	public static function comment_callback( $comment, $args, $depth ) { ?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $args[ 'has_children' ] ? 'parent' : '', $comment ); ?>>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-meta">
					<div class="comment-author vcard">
					<?php 
						if ( 0 != $args[ 'avatar_size' ] ) {
							echo get_avatar( $comment, $args[ 'avatar_size' ], 'mystery', 'Avatar di ' . $comment->comment_author, array( 'extra_attr' => 'aria-hidden="true"' ) );
						}
						
						echo get_comment_author_link( $comment ) . ' <span class="says">ha scritto:</span>';
					?>
					</div>

					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php
								/* translators: 1: comment date, 2: comment time */
								printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
							</time>
						</a>
						<?php edit_comment_link( '[M]', ' <span class="edit-link">', '</span>' ); ?>
					</div>

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation">Il tuo commento &egrave; in attesa di essere moderato.</p>
					<?php endif; ?>
				</header>

				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
					
				<?php
					echo str_replace( 'aria-label', 'title', get_comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth' => $depth,
						'max_depth' => $args[ 'max_depth' ],
						'before' => '<div class="reply">',
						'after' => '</div>'
					) ) ) );
				?>
			</div>
			<?php
			if ( $args[ 'has_children' ] ) {
				echo '<h' . ( intval( $depth ) + 2 ) . ' class="visually-hidden">Risposte al commento di ' . $comment->comment_author . '</h' . ( intval( $depth ) + 2 ) . '>';
			}
	}

	public static function responsive_youtube_embed( $html, $url, $attr, $post_ID ) {
		return '<p class="video-container">' . $html . '</p>';
	}

	// This function assumes that WordPress is installed in the 'wp' subfolder
	public static function script_loader_src( $src, $handle ) {
		return str_replace( get_site_url(), get_home_url() . '/wp', $src );
	}

	public static function transition_post_status( $new_status = '', $old_status = '', $post = 0 ) {
		// Cache

		// Bail if we're not dealing with a published post, which shouldn't be cached anyway
		if ( $old_status != 'publish' && $new_status != 'publish' ) {
			return 0;
		}

		// Note: get_sample_permalink doesn't add a leading slash to the permalink, and returns an array
		$permalink = get_sample_permalink( $post->ID );
		$permalink_path = '/' . $permalink[ 1 ];

		// Delete the old version from the cache
		duechiacchiere::delete_from_cache( $permalink_path );

		// If the new status is publish, generate a new cached version by pinging the page itself
		if ( $new_status == 'publish' ) {
			file_get_contents( home_url() . $permalink_path );
		}

		// Sitemap

		// Bail if the status didn't change (like saving a new version of a draft)
		if ( $old_status == $new_status ) {
			return 0;
		}

		$sitemap_file = '';
		if ( !empty( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) {
			if ( file_exists( $_SERVER[ 'DOCUMENT_ROOT' ] . '/wp-config.php' ) ) {
				$sitemap_file = $_SERVER[ 'DOCUMENT_ROOT' ] . '/sitemap.xml';
			} else if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/../wp-config.php' ) ) {
				$sitemap_file = $_SERVER[ 'DOCUMENT_ROOT' ] . '/../sitemap.xml';
			}
		}

		if ( empty( $sitemap_file ) ) {
			return 0;
		}

		// Don't generate the sitemap more than once every hour
		if ( file_exists( $sitemap_file ) && ( date( 'U' ) - date( 'U', filemtime( $sitemap_file ) ) < 3600 ) ) {
			return 0;
		}

		$posts = get_posts( array(
			'numberposts' => -1,
			'orderby' => 'modified',
			'order' => 'DESC',
			'post_status' => 'publish',
			'post_type' => array( 'post' )
		) );
	
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach ( $posts as $a_post ) {
			$postdate = explode( ' ', $a_post->post_modified );
			$sitemap .= '<url><loc>' . get_permalink( $a_post->ID ) . '</loc><lastmod>' . $postdate[ 0 ] . '</lastmod><changefreq>yearly</changefreq><priority>0.7</priority></url>';
		}

		$sitemap .= '</urlset>';
		file_put_contents( $sitemap_file, $sitemap );
	}

	// Make sure to save the Permalinks settings for WP to add this feed to its rewrite rules
	public static function feed_today_in_the_past() {
		load_template( ABSPATH . WPINC . '/feed-rss2.php' );
	}

	public static function pre_get_posts( $query ) {
		if ( $query->is_feed( 'scrissi-oggi' ) ) {
			$query->set( 'post_type', 'post' );
			$query->set( 'monthnum', date_i18n( 'm' ) );
			$query->set( 'day', date_i18n( 'd' ) );
			$query->set( 'posts_per_page', -1 );
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'desc' );
			$query->set( 'date_query', array( 'before' => array( 'year' => date_i18n( 'Y' ), 'month' => 1, 'day' => 1 ), 'inclusive' => false ) );

			// Change the feed title
			add_filter( 'wp_title_rss', array( __CLASS__, 'wp_title_rss' ) );
			add_filter( 'get_post_time', array( __CLASS__, 'get_post_time' ) );
			add_filter( 'get_feed_build_date', array( __CLASS__, 'get_feed_build_date' ), 10, 2 );
		}
	}

	public static function wp_title_rss( $title ) {
		return "Dall'archivio di $title";
	}
	public static function get_post_time( $title ) {
		return date_i18n( 'Y-m-d 00:00:01' );
	}
	public static function get_feed_build_date( $max_modified_time, $format ) {
		return date( 'D, d M Y H:i:s +0000' );
	}

	// Add custom styles to TinyMCE
	public static function tiny_mce_before_init( $settings ) {
		// Only show the block elements that we should use
		$settings[ 'block_formats' ] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Code=code;Preformatted=pre';

		// Insert the array, JSON ENCODED, into 'style_formats'
		$settings[ 'style_formats' ] = json_encode( array(
			array(
				'title' => 'lang="en"',
				'selector' => '*',
				'attributes' => array( 'lang' => 'en' )
			),
			array(
				'title' => 'hreflang="en"',
				'selector' => 'a',
				'attributes' => array( 'hreflang' => 'en' )
			)
		) );

		return $settings;
	}

	public static function mce_external_plugins( $plugin_array ) {
		$plugin_array[ 'tinymce_duechiacchiere' ] = get_template_directory_uri() . '/assets/js/tinymce.js';
		return $plugin_array;
	}

	// Callback function to insert 'styleselect' into the $buttons array
	public static function mce_buttons( $buttons ) {
		// Add the styles dropdown
		array_unshift( $buttons, 'styleselect' );
		
		// No advanced buttons, please
		if ( ( $key = array_search( 'wp_adv', $buttons ) ) !== false ) {
			unset( $buttons[ $key ] );
		}

		// Move the wp_more button 
		if ( ( $key = array_search( 'wp_more', $buttons ) ) !== false ) {
			unset( $buttons[ $key ] );
		}

		array_push( $buttons, 'duechiacchiere_code' );
		array_push( $buttons, 'duechiacchiere_recipe' );
		array_push( $buttons, 'duechiacchiere_abbr' );
		array_push( $buttons, 'wp_more' );

		return $buttons;
	}

	public static function admin_head_post(){
		if ( 'page' != get_post_type() ) {
			echo '
				<script>jQuery(document).ready(function(){
					jQuery("#postexcerpt .handlediv").after("<div style=\"position:absolute;top:auto;bottom:18px;left:auto;right:15px;\">Character count: <span id=\"excerpt_counter\"></span></div>");
					jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
					jQuery("#excerpt").keyup( function() {
						jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
					});
				});</script>';
		}
	}

	public static function admin_bar_menu( $wp_admin_bar ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'editorial-calendar/edcal.php' ) ) {
			$args = array(
				'id' => 'post-list',
				'title' => __( 'Posts' ),
				'href' => get_admin_url( 1, 'admin.php?page=cal' )
			);
			$wp_admin_bar->add_node($args);	
		}
	}

	public static function get_substr_words( $string, $desired_length ) {
		$parts = preg_split( '/([\s\n\r]+)/u', strip_tags( $string ), null, PREG_SPLIT_DELIM_CAPTURE );
		$parts_count = count( $parts );
	
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen( $parts[ $last_part ] );
			if ( $length > $desired_length ) {
				break;
			}
		}
	
		return implode( array_slice( $parts, 0, $last_part ) ) . ( ( $parts_count > $last_part) ? '...' : '' );
	}

	public static function scrub_field( $header ) {
		$headers_to_remove = array(
			'/to\:/i',
			'/from\:/i',
			'/bcc\:/i',
			'/cc\:/i',
			'/content\-transfer\-encoding\:/i',
			'/content\-type\:/i',
			'/mime\-version\:/i' 
		);
	
	 	return stripslashes( strip_tags( urldecode( preg_replace( $headers_to_remove, '', $header ) ) ) );
	}

	public static function minify_output( $html = '' ) {
		// No need to have type defined in the script tag anymore
		$html = str_replace( array( " type='text/javascript'", ' type="text/javascript"' ), '', $html );

		// ... or trailing slashes in tags
		$html = preg_replace( '/ ?\/>/', '>', $html );

		if ( strpos( $html, '<pre>' ) !== false ) {
			// Find the code blocks and put them aside
			$blocks = preg_split( '/(<\/?pre>)/', $html, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY );
			$html = '';
		
			// Minify what can be minified
			$i = 0;
			while ( !empty( $blocks[ $i ] ) ) {
				if ( $blocks[ $i ] == '<pre>' ) {
					$html .= '<pre>' . preg_replace( array( "/[\r\n]+/", '/ /' ), array( '<br>', '&nbsp;' ), $blocks[ $i + 1 ] ) . '</pre>';
					$i = $i + 3;
				}
				else {
					$html .= $blocks[ $i ];
					$i++;
				}
			}
		}

		// or HTML or Javascript comments
		$html = preg_replace( array( '/<!--(.*?)-->/', '/(\s+)(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/' ), '', $html );

		// ... or multiple spaces
		$html = preg_replace( '/  +/', ' ', $html );

		// Finally, remove all the line breaks and tabs
		$html = preg_replace( "/[\r\n\t]*/", '', $html );

		return $html;
	}

	public static function get_cache_path( $permalink = '' ) {
		// Is this the homepage?
		if ( $permalink == '/' ) {
			return WP_CONTENT_DIR . '/cache/index.html';
		}
		else {
			return WP_CONTENT_DIR . '/cache' . $permalink . '.html';
		}
	}

	// Save a copy of a page in the cache
	public static function add_to_cache( $html = '' ) {
		// Please add the following lines to your .htaccess, inside a mod_rewrite block:
		// # Homepage
		// RewriteCond %{REQUEST_URI} ^/$
		// RewriteCond %{DOCUMENT_ROOT}/content/cache/index.html -f
		// RewriteCond %{REQUEST_URI} !^/content/cache/ [NC]
		// RewriteCond %{HTTP_COOKIE} !wordpress_logged_in [NC]
		// RewriteRule .* /content/cache/index.html [L]

		// # All other pages
		// RewriteCond %{DOCUMENT_ROOT}/content/cache/$1.html -f
		// RewriteCond %{REQUEST_URI} !^/content/cache/ [NC]
		// RewriteCond %{HTTP_COOKIE} !wordpress_logged_in [NC]
		// RewriteRule (.*) /content/cache/$1.html [L]    

		// Cache only individual posts and the homepage, not categories or other archives, or 404s, and don't cache pages with a query string
		if ( ( !is_single() && !is_front_page() ) || substr_count( $_SERVER[ 'REQUEST_URI' ], '/' ) != 1 || substr_count( $_SERVER[ 'REQUEST_URI' ], '?' ) != 0 || is_user_logged_in() ) {
			return false;
		}

		// In case I change my mind, this is the code to create all the subfolders
		// if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/' ) !== false && !is_dir( dirname( $target ) ) ) {
		// 	mkdir( dirname( $target ), 0755, true );
		// }

		file_put_contents( duechiacchiere::get_cache_path( $_SERVER[ 'REQUEST_URI' ] ), $html . '<!--' . date( DATE_RFC2822 ) . '-->' );
	}

	// Delete a page from the cache and refresh the homepage
	public static function delete_from_cache( $permalink_path = '' ) {
		@unlink( duechiacchiere::get_cache_path( $permalink_path ) );

		// Homepage
		@unlink( duechiacchiere::get_cache_path( '/' ) );
		file_get_contents( home_url() );
	}

	public static function first_post_image( $post_content ) {
		$output = preg_match_all( '/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post_content, $matches );

		if ( !empty( $matches[ 1 ][ 0 ] ) ) {
			return $matches[ 1 ][ 0 ];
		}

		return '';
	}

	public static function is_naked_day() {
		if ( !empty( $_GET[ 'naked' ] ) ) {
			return true;
		}

		$start = date( 'U', mktime( -12, 0, 0, 4, 9, date( 'Y' ) ) );
		$end = date( 'U', mktime( 36, 0, 0, 4, 9, date( 'Y' ) ) );
		$z = date( 'Z' ) * -1;
		$now = time() + $z;
		if ( $now >= $start && $now <= $end ) {
			return true;
		}

		return false;
	}

	private static function _remove_emoji_hooks() {
		// All actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'wp_resource_hints', 2 );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	private static function _remove_wp_headers() {
		// Remove the REST API lines from the HTML Header
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	
		// Remove WLW Manifest and Generator
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head');
		remove_action( 'rss2_head', 'the_generator' );
		remove_action( 'rss_head',  'the_generator' );
		remove_action( 'rdf_header', 'the_generator' );
		remove_action( 'atom_head', 'the_generator' );
		remove_action( 'commentsrss2_head', 'the_generator' );
		remove_action( 'opml_head', 'the_generator' );
		remove_action( 'app_head',  'the_generator' );
		remove_action( 'comments_atom_head', 'the_generator' );
	
		// Remove the REST API endpoint.
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	
		// Turn off oEmbed auto discovery.
		add_filter( 'embed_oembed_discover', '__return_false' );
	
		// Don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	
		// Remove oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	
		// Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	
		// Enable title tag
		add_theme_support( 'title-tag' ); 
	
		// Remove XML-RPC and feed links
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'index_rel_link' );
	}
}

// Let's go, baby!
add_action( 'init', array( 'duechiacchiere', 'init' ), 20 );