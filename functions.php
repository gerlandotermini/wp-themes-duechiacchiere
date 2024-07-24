<?php

class duechiacchiere {
	public static $comment_edited = false;

	public static function init() {
		// This theme uses wp_nav_menu() above and below the main page header
		register_nav_menus( array(
			'primary' => 'Primary Navigation',
			'sidebar' => 'External Links'
		) );

		// Add excerpt to pages
		add_post_type_support( 'page', 'excerpt' );

		// Redirect shortlinks (with post id) to the actual canonical URLs
		add_filter( 'template_redirect', array( __CLASS__, 'redirect_post_id_to_canonical_url' ) );

		// Sorry, no Gutenberg for you
		add_filter( 'use_block_editor_for_post_type', '__return_false' );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ), 100 );

		// Insert inline styles and scripts, except on CSS Naked Day - https://css-naked-day.github.io/
		// If constant is not defined, wp_enqueue_scripts will take care of adding the external reference as needed
		if ( !self::is_naked_day() && defined( 'USE_INLINE_STYLES_SCRIPTS' ) && USE_INLINE_STYLES_SCRIPTS ) {
			add_action( 'wp_head', array( __CLASS__, 'print_styles' ) );
			add_action( 'wp_footer', array( __CLASS__, 'print_scripts' ) );
		}

		// Make the main menu more accessible
		add_filter( 'walker_nav_menu_start_el', array( __CLASS__, 'walker_nav_menu_start_el' ), 10, 4 );

		// Customize image HTML wrappers
		add_shortcode( 'caption', array( __CLASS__, 'img_caption_html' ) );

		// Add appropriate classes to external links
		add_filter( 'the_content', array( __CLASS__, 'the_content' ) );
		add_filter( 'comment_text', array( __CLASS__, 'the_content' ) );
		add_filter( 'the_content_more_link', array( __CLASS__, 'the_content_more_link' ) );

		// Add a link to leave a comment to the feed
		add_filter( 'the_content_feed', array( __CLASS__, 'the_content_feed') );

		// Don't generate thumbnails, this theme only uses full size
		add_filter( 'intermediate_image_sizes', '__return_empty_array' );

		// Tweak style and script HTML tags
		add_filter( 'style_loader_src', array( __CLASS__, 'script_loader_src' ), 20, 2 );
		add_filter( 'script_loader_src', array( __CLASS__, 'script_loader_src' ), 20, 2 );
		add_filter( 'script_loader_tag', array( __CLASS__, 'script_loader_tag'), 20, 2 );

		// Improve the built-in search a bit
		add_filter( 'posts_clauses', array( __CLASS__, 'post_clauses' ), 10, 2 );

		// Update the sitemap file and the cache whenever a post is added or modified
		add_action( 'transition_post_status', array( __CLASS__, 'transition_post_status' ), 10, 3 );

		// Update cache after a comment is posted or edited or deleted
		add_action( 'comment_post', array( __CLASS__, 'comment_post' ), 10, 3 );
		add_action( 'edit_comment', array( __CLASS__, 'edit_comment' ), 10, 2 );
		add_action( 'transition_comment_status', array( __CLASS__, 'transition_comment_status' ), 10, 3 );

		// Cache Gravatars
		add_filter( 'get_avatar_url', array( __CLASS__, 'get_avatar_url' ), 10, 3 );

		// Filter really long comments (spam)
		add_filter( 'preprocess_comment' , array( __CLASS__, 'preprocess_comment' ) );

		// Remove attribute nofollow from comment author link, if present
		add_filter( 'get_comment_author_link', array( __CLASS__, 'get_comment_author_link' ), 10, 3 );

		// Move the 'Cancel reply' link next to the button to submit a comment
		add_filter( 'cancel_comment_reply_link', '__return_empty_string' );

		// Add nofollow to the monthly archive links in the footer
		add_filter( 'get_archives_link', array( __CLASS__, 'get_archives_link' ) );

		// Generate today's posts feed
		add_feed( 'scrissi-oggi', array( __CLASS__, 'feed_today_in_the_past' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );

		// Customize the TinyMCE Editor
		add_filter( 'mce_external_plugins', array( __CLASS__, 'mce_external_plugins' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'mce_buttons' ) );
		add_filter( 'tiny_mce_before_init', array( __CLASS__, 'tiny_mce_before_init' ) );
		add_action( 'admin_head', array( __CLASS__, 'admin_head' ) );
		
		// Add a Post List button to the admin bar
		add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 100 );

		// Disable built-in sitemap
		add_filter( 'wp_sitemaps_enabled', '__return_false' );

		// Miscellaneous clean up
		self::_remove_emoji_hooks();
		self::_remove_wp_headers();
	}

	public static function redirect_post_id_to_canonical_url() {
		$requested_url = str_replace( '/', '', $_SERVER[ 'REQUEST_URI' ] );

		if ( is_404() && is_numeric( $requested_url ) ) {
			$canonical = get_permalink( intval( $requested_url ) );

			if ( !empty( $canonical ) ) {
				wp_redirect( $canonical, 301 );
			}
		}
	}

	public static function wp_enqueue_scripts() {
		if ( !self::is_naked_day() && ( !defined( 'USE_INLINE_STYLES_SCRIPTS' ) || !USE_INLINE_STYLES_SCRIPTS ) ) {
			wp_enqueue_style( 'duechiacchiere', get_template_directory_uri() . '/assets/css/style.css', array(), null, 'all' );
			wp_enqueue_script( 'duechiacchiere', get_template_directory_uri() . '/assets/js/script.js', array(), null, true );
			wp_localize_script( 'duechiacchiere', 'duechiacchiere',
				array( 
					'COOKIEHASH' => COOKIEHASH
				)
			);
		}

		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
		wp_dequeue_style( 'global-styles' );

		wp_deregister_script( 'comment-reply' );
	}

	public static function print_styles() {
		$css = file_get_contents( get_template_directory() . '/assets/css/style.css' );
		echo "<style>$css</style>";
	}

	public static function print_scripts() {
		$js = file_get_contents( get_template_directory() . '/assets/js/script.js' );
		echo "<script>const duechiacchiere={'COOKIEHASH':'" . COOKIEHASH . "'};$js</script>";
	}

	public static function walker_nav_menu_start_el( $_item_output, $_item, $_depth, $_args ) {
		if ( !empty( $_item->description ) ) {
			$_item_output = str_replace( $_args->link_after . '</a>', $_args->link_after . '</a>' . '<p class="menu-item-description">' . $_item->description . '</p>', $_item_output );
		}
	
		return $_item_output;
	}

	public static function img_caption_html( $_attr, $_content = null ) {
		extract( shortcode_atts( array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'caption' => ''
		), $_attr ) );
		
		// New approach implemented in WP 3.4: caption is not an attribute anymore
		$caption = trim( strip_tags( $_content, '<a>' ) );
		$image = trim( str_replace( $caption, '', $_content ) );
	
		if ( 1 > (int) $width || empty( $caption ) )
			return $_content;
	
		if ( $id ) {
			$id = 'id="' . esc_attr( $id ) . '" ';
		}

		return "<figure {$id}class=\"wp-caption $align\">$image<figcaption class=\"wp-caption-text\" aria-hidden=\"true\">$caption</figcaption></figure>";
	}

	public static function the_content( $html = '' ) {
		// Add appropriate classes to certain links
		$dom = new DomDocument();
		$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR );
		$xpath = new DOMXpath( $dom );
		$links = $xpath->evaluate( '//a[@href]' );

		foreach( $links as $a_link ) {
			$class_attr = $a_link->getAttribute('class');
			$link_attr = $a_link->getAttribute('href');

			if ( !empty( $class_attr ) ) {
				$class_attr .= ' ';
			}

			if ( stripos( $link_attr, '.pdf' ) !== false ) {
				if ( stripos( $class_attr, 'pdf') === false ) {
					$a_link->setAttribute( 'class', $class_attr . 'svg external pdf' );
				}
			}
			else if ( stripos( $link_attr, get_bloginfo( 'url' ) ) === false && stripos( $link_attr, 'http' ) !== false && stripos( $class_attr, 'external') === false ) {
				$a_link->setAttribute( 'class', $class_attr . 'svg external' );
			}
		}

		return wp_kses_post($dom->saveHTML());
	}

	public static function the_content_more_link( $_more_link = '' ) {
		return str_replace( 'more-link', 'svg more-link', $_more_link );
	}

	public static function the_content_feed( $_content = '', $_feed_type = 'rss2' ) {
		return $_content . '<p><a href="' . get_permalink() . '#comments">Lascia un commento</a></p>';
	}

	public static function wp_video_shortcode( $html = '' ) {
		return str_replace( '<video', '<video crossorigin="anonymous"', $html );
	}

	// This function assumes that WordPress is installed in the 'wp' subfolder
	public static function script_loader_src( $src, $handle ) {
		return str_replace( get_site_url(), get_home_url() . '/wp', $src );
	}

	// Defer loading of the Javascript code
	public static function script_loader_tag( $tag, $handle ) {
		if ( 'duechiacchiere' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' defer src', $tag );
	}

	public static function post_clauses( $clauses, $query ) {
		// Do something only if this is a search request
		if ( !$query->is_search && !$query->is_404 ) {
				return $clauses;
		}

		// Get the search term
		if ( empty( $query->query_vars[ 'search_terms' ] ) ) {
			return $clauses;
		}

		$search_terms = $query->query_vars[ 'search_terms' ];

		// Build the query
		$search_query = array();

		foreach ( $search_terms as $a_term ) {
			$search_query[] = $GLOBALS[ 'wpdb' ]->prepare( " ({$GLOBALS[ 'wpdb' ]->posts}.post_title LIKE %s OR {$GLOBALS[ 'wpdb' ]->posts}.post_content LIKE %s)", '%' . $a_term . '%', '%' . $a_term . '%' );
		}
		$clauses[ 'where' ] .= ' AND (' . implode( ' OR ', $search_query ) . ')';
		
		// Restrict search to only published posts
		$clauses[ 'where' ] .= " AND ({$GLOBALS[ 'wpdb' ]->posts}.post_type = 'post' AND {$GLOBALS[ 'wpdb' ]->posts}.post_status = 'publish')";

		// Show the most recent first
		$clauses[ 'orderby' ] = "{$GLOBALS['wpdb']->posts}.post_date DESC";

		return $clauses;
	}

	// Handle cache and sitemap generation
	public static function transition_post_status( $new_status = '', $old_status = '', $post = 0 ) {
		// Bail if we're not dealing with a published post, which shouldn't be cached or listed in the sitemap anyway
		if ( $old_status != 'publish' && $new_status != 'publish' ) {
			return 0;
		}

		// Cache
		// -------------------------------------------------------------------------------

		if ( function_exists( 'get_sample_permalink' ) ) {
			// Note: get_sample_permalink doesn't add a leading slash to the permalink, and returns an array
			$permalink = get_sample_permalink( $post->ID );
			$permalink_path = '/' . $permalink[ 1 ];

			// Delete this post from the cache
			duechiacchiere::delete_from_cache( $permalink_path );
		}

		// Sitemap
		// -------------------------------------------------------------------------------

		// Bail if the status didn't change (like saving a new version of a draft)
		if ( $old_status === $new_status ) {
			return 0;
		}

		self::_generate_sitemap();
	}

	// Refresh the cache whenever a comment is submitted or changes status
	public static function comment_post( $comment_id = 0, $comment_approved = 0, $commentdata = array() ) {

		// Delete cached version of this page (footer will regenerate it) and refresh homepage
		if ( !empty( $commentdata[ 'comment_post_ID' ] ) && $comment_approved ) {
			$permalink_path = str_replace( home_url(), '', get_permalink( $commentdata[ 'comment_post_ID' ] ) );
			duechiacchiere::delete_from_cache( $permalink_path );
		}
	}

	// Refresh the cache whenever a comment is edited or changes status
	public static function edit_comment( $comment_id = 0, $commentdata = array() ) {

		// Delete cached version of this page (footer will regenerate it) and refresh homepage
		if ( !empty( $commentdata[ 'comment_post_ID' ] ) ) {
			$permalink_path = str_replace( home_url(), '', get_permalink( $commentdata[ 'comment_post_ID' ] ) );
			duechiacchiere::delete_from_cache( $permalink_path );
			self::$comment_edited = true;
		}
	}

	// Refresh the cache if a comment is deleted or restored from the trash, or changes status
	public static function transition_comment_status( $new_status = '', $old_status = '', $comment = 0 ) {

		// Bail if we're not dealing with an approved comment, which shouldn't be cached anyway, or if we already cleared the cache in edit_comment
		if ( ( $old_status != 'approved' && $new_status != 'approved' ) || self::$comment_edited ) {
			return 0;
		}

		$permalink_path = str_replace( home_url(), '', get_permalink( $comment->comment_post_ID ) );

		// Delete the old version from the cache
		duechiacchiere::delete_from_cache( $permalink_path );
	}

	public static function get_avatar_url( $_url, $_comment, $_args ) { 
		if ( !empty( $_url ) ) {
			$cached_image_path = '/cache/gravatar/' . md5( $_url ) . '.webp';

			// Do we have this image in cache?
			if ( file_exists( WP_CONTENT_DIR . $cached_image_path ) ) {
				return WP_CONTENT_URL . $cached_image_path;
			}

			// Download the image
			$image_file = imagecreatefromstring( file_get_contents( $_url ) );

			// Convert it to webp
			$w=imagesx($image_file);
			$h=imagesy($image_file);
			$webp=imagecreatetruecolor($w,$h);
			imagecopy($webp,$image_file,0,0,0,0,$w,$h);

			// Save it to our cache
			imagewebp($webp, WP_CONTENT_DIR . $cached_image_path, 80);

			// Free up resources
			imagedestroy($image_file);
			imagedestroy($webp);

			return WP_CONTENT_URL . $cached_image_path;
		}

		return $_url; 
	}

	public static function preprocess_comment( $commentdata = array() ) {
		if ( count( preg_split('/\n/', $commentdata[ 'comment_content' ] ) ) > 100 || preg_match( '/\s/', $commentdata[ 'comment_content' ] ) === 0 ) {
			die( 'Pussa via brutta bertuccia' );
		};

		return $commentdata;
	}

	public static function get_comment_author_link( $link, $author, $comment_ID ) {
        $comment_url = get_comment_author_url( $comment_ID );

		if ( !empty( $comment_url ) ) {
			return sprintf(
				'<a href="%s" rel="external ugc" class="url">%s</a>',
				get_comment_author_url( $comment_ID ),
				$author
			);
		}

		return $link;
	}

	public static function get_archives_link( $link_html = '' ) {
		return str_replace( '<a href=', '<a rel="nofollow" href=',  $link_html );
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
		else if ( $query->is_search() && $query->is_main_query() ) {

			// Attempt to get the parameter, or default to 0
			$category_id = (int)( $_REQUEST[ 'c' ] ?? 0 );
			if ( !empty( $category_id ) ) {
				$query->set( 'cat', $category_id );
			}
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

	public static function mce_external_plugins( $plugin_array ) {
		$plugin_array[ 'tinymce_duechiacchiere' ] = str_replace( get_site_url(), get_home_url(), get_template_directory_uri() ) . '/assets/js/tinymce.js';
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

	public static function admin_head() {
		echo '
		<style>
			#wpbody-content a {
				filter: invert(1) hue-rotate(180deg) saturate(10);
				color: #777 !important;
			}
			#wpbody-content a:hover {
				filter: invert(1) hue-rotate(180deg) saturate(10);
				color: red !important;
			}
			/* Styling for primary content area. */
				.block-editor-page .editor-styles-wrapper {
				color: lightgray;
				background: #262626;
			}
			/* Base styling adjustments. */
			.wp-admin {
				background-color: #262626;
			}
			.wp-admin #wpbody {
				filter: invert(0.85) hue-rotate(185deg);
			}
			.wp-admin #wpbody img {
				filter: invert(1) hue-rotate(-180deg);
				background: white;
			}/* Enhancements for hyperlink visuals. */
			.block-editor-page .editor-styles-wrapper a {
				filter: invert(0.85) hue-rotate(185deg);
			}
			#wp-content-editor-tools {
				background: initial;
			}
			/* Filter reset for specific editor sections. */
			.block-editor-page #wpbody {
				filter: unset;
			}/* Adjustments for the main body appearance. */
			/* Sidebar appearance customization. */
			.block-editor-page .interface-interface-skeleton__sidebar,
			.block-editor-page .interface-interface-skeleton__secondary-sidebar {
				filter: invert(0.85) hue-rotate(185deg);
			}/* Configuration for top navigation bar. */
			.block-editor-page .interface-interface-skeleton__header {
				filter: invert(0.85) hue-rotate(185deg);
			}
			/* Primary action button styling. */
			.block-editor-page .is-primary {
				color: black !important;
			}
			/* Lower section layout adjustments. */
			.block-editor-page .edit-post-layout__metaboxes {
				border-top: 0px;
				background-color: #262626;
			}/* Reset various button BG colours */
			.wrap .add-new-h2, .wrap .add-new-h2:active, .wrap .page-title-action, .wrap .page-title-action:active {
				background:#f6f7f700;
			}
		</style>';
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

	// Used in comments.php to customize each comment's structure
	public static function comment_callback( $comment, $args, $depth ) {
		// Don't show pending comments
		if ( '0' == $comment->comment_approved ) {
			return '';
		}
		?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $args[ 'has_children' ] ? 'parent' : '', $comment ); ?>>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-meta">
					<div class="comment-author vcard">
					<?php 
						if ( 0 != $args[ 'avatar_size' ] ) {
							echo get_avatar( $comment, $args[ 'avatar_size' ], 'mystery', 'Avatar di ' . $comment->comment_author, array( 'extra_attr' => 'aria-hidden="true"' ) );
						}
						
						echo '<div class="comment-author-name">' . get_comment_author_link( $comment ) . ' <span class="says">ha scritto:</span></div>';
					?>
					</div>

					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( '[M]', ' <span class="edit-link">', '</span>' ); ?>
					</div>
				</header>

				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
					
				<?php
					echo get_comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth' => $depth,
						'max_depth' => $args[ 'max_depth' ],
						'before' => '<div class="reply">',
						'after' => '</div>'
					) ) );
				?>
			</div>
			<?php
			if ( $args[ 'has_children' ] ) {
				echo '<h' . ( ($depth > 4 ) ? 6 : intval( $depth ) + 2 ) . ' class="visually-hidden">Risposte al commento di ' . $comment->comment_author . '</h' . ( ($depth > 4 ) ? 6 : intval( $depth ) + 2 ) . '>';
			}
	}

	public static function get_substr_words( $string = '', $desired_length = 100 ) {
		$parts = preg_split( '/([\s\n\r]+)/u', strip_tags( $string ), -1, PREG_SPLIT_DELIM_CAPTURE );
		$parts_count = count( $parts );
	
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen( $parts[ $last_part ] );
			if ( $length > $desired_length ) {
				break;
			}
		}
	
		return implode( array_slice( $parts, 0, $last_part ) ) . ( ( $parts_count > $last_part) ? '&hellip;' : '' );
	}

	public static function scrub_field( $header, $strip_tags = true ) {
		$headers_to_remove = array(
			'/to\:/i',
			'/from\:/i',
			'/bcc\:/i',
			'/cc\:/i',
			'/content\-transfer\-encoding\:/i',
			'/content\-type\:/i',
			'/mime\-version\:/i' 
		);
	
		$clean_string = stripslashes( urldecode( preg_replace( $headers_to_remove, '', $header ) ) );

		if ( $strip_tags ) {
			return strip_tags( $clean_string );
		}
		
	 	return htmlspecialchars( $clean_string );
	}

	public static function scrub_output( $html = '' ) {
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
		// RewriteCond %{QUERY_STRING} !.+
		// RewriteCond %{DOCUMENT_ROOT}/content/cache/index.html -f
		// RewriteCond %{HTTP_COOKIE} !wordpress_logged_in [NC]
		// RewriteRule .* /content/cache/index.html [L]

		// # All other pages
		// RewriteCond %{QUERY_STRING} !.+
		// RewriteCond %{DOCUMENT_ROOT}/content/cache/$1.html -f
		// RewriteCond %{REQUEST_URI} !^/content/cache/ [NC]
		// RewriteCond %{HTTP_COOKIE} !wordpress_logged_in [NC]
		// RewriteRule (.*) /content/cache/$1.html [L]
		//
		// Then create a 'cache' folder under wp-content, writeable to the web server

		// Cache only individual posts and the homepage, not categories or other archives, or 404s, and don't cache pages with a query string
		if ( ( !is_single() && !is_front_page() && !is_page() ) || 
			substr_count( $_SERVER[ 'REQUEST_URI' ], '/' ) != 1 ||
			!empty( $_REQUEST ) ||
			is_user_logged_in() ) {
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

		// Refresh the homepage, just in case this post is listed there as well
		@unlink( duechiacchiere::get_cache_path( '/' ) );
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
			return filter_var( $_GET[ 'naked' ], FILTER_VALIDATE_BOOLEAN );
		}

		// CSS Naked Day lasts 50 hours, from 10am on 4/8 to noon on 4/10
		$start = date( 'U', mktime( 10, 0, 0, 4, 8, date( 'Y' ) ) );
		$end = date( 'U', mktime( 12, 0, 0, 4, 10, date( 'Y' ) ) );
		$now = time() + 7200; // For some reason our server is returning the wrong value for date('Z'). Italy is GMT+2.

		return ( $now >= $start && $now <= $end );
	}

	private static function _generate_sitemap() {
		$sitemap_file = '';
		if ( !empty( ABSPATH ) ) {
			if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
				$sitemap_file = ABSPATH . 'sitemap.xml';
			} else if ( file_exists( ABSPATH . '../wp-config.php' ) ) {
				$sitemap_file = ABSPATH . '../sitemap.xml';
			}
		}

		if ( empty( $sitemap_file ) ) {
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
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	
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

		// Remove WP's built-in ob_end_flush, since it conflicts with what we do in footer.php, if zlib compression is enabled
		remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
	}
}

// Let's go, baby!
add_action( 'init', array( 'duechiacchiere', 'init' ), 20 );
