<?php
// Custom layout for comments
function duechiacchiere_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
				<li id="comment-<?php comment_ID() ?>" class="comment-block-<?php echo $depth ?>"><?php 
	if (get_comment_author() == 'camu'){
		echo '<img class="avatar" src="https://www.duechiacchiere.it/content/themes/duechiacchiere/img/camu-avatar.png" alt="camu" width="40" height="40" />'; ?>
				<ul class="comment-information">
					<li class="comment-author"><span class="hidden">Scritto da </span>camu</li>		
<?php
	}
	else {
		if ( get_comment_author_email() != '' )
			echo get_avatar($comment,'40','https://www.duechiacchiere.it/content/themes/duechiacchiere/img/anonimo.gif','Avatar di '.get_comment_author() ); 
		else
			echo '<img class="avatar" src="https://www.duechiacchiere.it/content/themes/duechiacchiere/img/anonimo.gif" alt="anonimo" width="40" height="40" />';
?>
				<ul class="comment-information">
					<li class="comment-author"><span class="hidden">Scritto da </span><?php echo str_replace(' nofollow','', get_comment_author_link()) ?></li>
<?php } if ( $comment->comment_approved == '0' ) echo '					<li class="comment-approve">in attesa di moderazione</li>'; ?>
					<li class="comment-date"><span class="hidden"> in data </span><a href="#comment-<?php comment_ID() ?>" title="collegamento diretto al commento di <?php comment_author() ?>"><?php comment_date('j F Y \a\l\l\e H:i') ?></a></li>
					<li class="comment-reply"><a href="#comment-text" onclick="comment_reply('<?php comment_author() ?>', '<?php comment_ID() ?>'); return(false)" title="al commento di <?php comment_author() ?>">rispondi</a></li>
					<?php if (is_user_logged_in()) { echo '<li class="comment-edit">'; edit_comment_link('modifica','',''); echo '</li>'; } ?>
				</ul>
				<?php comment_text();
}

// Exclude sponsored articles from RSS feed
function duechiacchiere_exclude_author($query){
	if ($query->is_feed || !empty($_COOKIES['comment_author'.COOKIEHASH]))
		$query->set('author','-6');
	return $query;
}
add_filter('pre_get_posts', 'duechiacchiere_exclude_author');

/**
 * Retrieve or display pagination code.
 *
 * The defaults for overwriting are:
 * 'page' - Default is null (int). The current page. This function will
 *      automatically determine the value.
 * 'pages' - Default is null (int). The total number of pages. This function will
 *      automatically determine the value.
 * 'range' - Default is 3 (int). The number of page links to show before and after
 *      the current page.
 * 'gap' - Default is 3 (int). The minimum number of pages before a gap is 
 *      replaced with ellipses (...).
 * 'anchor' - Default is 1 (int). The number of links to always show at begining
 *      and end of pagination
 * 'before' - Default is '<div class="emm-paginate">' (string). The html or text 
 *      to add before the pagination links.
 * 'after' - Default is '</div>' (string). The html or text to add after the
 *      pagination links.
 * 'title' - Default is '__('Pages:')' (string). The text to display before the
 *      pagination links.
 * 'next_page' - Default is '__('&raquo;')' (string). The text to use for the 
 *      next page link.
 * 'previous_page' - Default is '__('&laquo;')' (string). The text to use for the 
 *      previous page link.
 * 'echo' - Default is 1 (int). To return the code instead of echo'ing, set this
 *      to 0 (zero).
 *
 * @author Eric Martin <eric@ericmmartin.com>
 * @copyright Copyright (c) 2009, Eric Martin
 * @version 1.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function duechiacchiere_paginate($args = null) {
	$defaults = array(
		'page' => null, 'pages' => null, 
		'range' => 2, 'gap' => 3, 'anchor' => 1,
		'before' => '<ul class="pagination">', 'after' => '</ul>',
		'title' => 'Pagine:',
		'nextpage' => '&raquo;',
		'previouspage' => '&laquo;',
		'echo' => 1
	);

	$search = !empty($_POST['s'])?'?s='.htmlspecialchars($_REQUEST['s']):'';

	$r = wp_parse_args($args, $defaults);
	extract($r, EXTR_SKIP);

	if (!$page && !$pages) {
		global $wp_query;

		$page = get_query_var('paged');
		$page = !empty($page) ? intval($page) : 1;

		$posts_per_page = intval(get_query_var('posts_per_page'));
		$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
	}
	
	$output = "";
	if ($pages > 1) {	
		$output .= "$before<li class='pagination-title'>$title</li>";
		$ellipsis = "<li class='pagination-separator'>...</li>";

		if ($page > 1 && !empty($previouspage)) {
			$output .= "<li><a href='" . get_pagenum_link($page - 1) . "$search' title='vai alla pagina precedente'>$previouspage</a></li>";
		}
		
		$min_links = $range * 2 + 1;
		$block_min = min($page - $range, $pages - $min_links);
		$block_high = max($page + $range, $min_links);
		$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
		$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

		if ($left_gap && !$right_gap) {
			$output .= sprintf('%s%s%s', 
				duechiacchiere_paginate_loop(1, $anchor, 0, $search), 
				$ellipsis, 
				duechiacchiere_paginate_loop($block_min, $pages, $page, $search)
			);
		}
		else if ($left_gap && $right_gap) {
			$output .= sprintf('%s%s%s%s%s', 
				duechiacchiere_paginate_loop(1, $anchor, 0, $search), 
				$ellipsis, 
				duechiacchiere_paginate_loop($block_min, $block_high, $page, $search), 
				$ellipsis, 
				duechiacchiere_paginate_loop(($pages - $anchor + 1), $pages, 0, $search)
			);
		}
		else if ($right_gap && !$left_gap) {
			$output .= sprintf('%s%s%s', 
				duechiacchiere_paginate_loop(1, $block_high, $page, $search),
				$ellipsis,
				duechiacchiere_paginate_loop(($pages - $anchor + 1), $pages, 0, $search)
			);
		}
		else {
			$output .= duechiacchiere_paginate_loop(1, $pages, $page, $search);
		}

		if ($page < $pages && !empty($nextpage)) {
			$output .= "<li><a href='" . get_pagenum_link($page + 1) . "$search' title='vai alla pagina successiva'>$nextpage</a></li>";
		}

		$output .= $after;
	}

	if ($echo) {
		echo $output;
	}

	return $output;
}

function duechiacchiere_get_category_link($_slug, $_name){
	return "<a href='/corridoio/$_slug'><span class='hidden'>categoria: </span>$_name</a>";
}

function duechiacchiere_paginate_loop($start, $max, $page = 0, $search = '') {
	$output = "";
	for ($i = $start; $i <= $max; $i++) {
		$output .= ($page === intval($i)) 
			? "<li class='current'>$i</li>" 
			: "<li><a href='" . get_pagenum_link($i) . "$search' title='vai alla pagina $i'>$i</a></li>";
	}
	return $output;
}

function duechiacchiere_mce_encoding( $init ){
	$init['theme_advanced_styles'] = 'external=external;recipe=recipe;ingredients=ingredients;cookingtime=cookingtime';
	$init['entity_encoding'] = 'numeric';
	$init['remove_linebreaks'] = false;
	$init['theme_advanced_blockformats'] = 'h3,p,code';
	$init['wpautop'] = true;
	$init['apply_source_formatting'] = true;

	return $init;
}
add_filter('tiny_mce_before_init', 'duechiacchiere_mce_encoding');

function duechiacchiere_mce_buttons($buttons){
	return array('formatselect','styleselect','bold','italic','strikethrough','|','bullist','numlist','blockquote','|','link','unlink','|','wp_more','|','wp_adv','fullscreen');
}
add_filter('mce_buttons', 'duechiacchiere_mce_buttons');

/* Make images with captions fluid */
add_shortcode('caption', 'duechiacchiere_img_caption_shortcode');
function duechiacchiere_img_caption_shortcode($attr, $content = null) {

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));
	
	// New approach implemented in WP 3.4: caption is not an attribute anymore
	if (empty($caption)){
		if (substr($content, 0, 2) == '<i'){
			list($image, $caption) = explode('/>', $content);
			$separator = "/>";
		}
		else{
			list($image, $caption) = explode('/a>', $content);
			$separator = 'span></span></a>';
		}
	}
	else{
		$image = $content;
		$separator = '';
	}

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return "<span $id class='wp-caption $align' style='max-width:{$width}px'>$image$separator <span class='wp-caption-text'>$caption</span></span>";
}