<?php include_once( 'header.php' ) ?>

<div id="content-wrapper">
	<main id="content">
		<?php /* echo (($wp_query->found_posts==1)?'Trovato ':'Trovati ').$wp_query->found_posts.(($wp_query->found_posts==1)?' risultato':' risultati'); ?> per <strong><?php echo htmlspecialchars(stripslashes($_REQUEST['s'])) ?></strong> */ ?>
		
		<?php
			if ( !is_single() ) {
				echo $intro_title;
			}
			while ( have_posts() ):
				the_post();

				if ( !is_single() ) {
					$categories = get_the_category( $GLOBALS[ 'post' ]->ID );
				}

				$categories_html = array();
				foreach ( $categories as $a_category ) {
					$categories_html[] = '<a href="' . get_category_link( $a_category->term_id ). '">' . $a_category->name . '</a>';
				}
				$categories_html = implode( ', ', $categories_html );

				$comment_count = get_comments_number();
				$comments_html = "<a href=\"" . get_permalink();
				switch( $comment_count ) {
					case 0:
						$comments_html .= "#rispondi\">Lascia un commento";
						break;
					case 1:
						$comments_html .= "#commenti\"><span class=\"visually-hidden\">c'&egrave; </span>1 commento";
						break;
					default:
						$comments_html .= "#commenti\"><span class=\"visually-hidden\">ci sono </span>{$comment_count} commenti";
				}
				$comments_html .= "<span class=\"visually-hidden\"> per {$GLOBALS[ 'post' ]->post_title}</span></a>";
		?>
		<article>
			<header>
				<<?= $title_tag ?>><a href="<?php the_permalink() ?>"><?php the_title( '', '' ) ?></a></<?= $title_tag ?>>
				<?php if ( $GLOBALS[ 'post' ]->post_type == 'post' ): ?>
				<ul class="post-information">
					<li>	
						<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 400 400"><path id="path0" d="M110.130 0.664 C 101.067 2.305,94.128 8.982,93.136 17.012 C 93.014 18.001,92.941 24.519,92.941 34.343 L 92.941 50.089 72.885 50.166 C 55.685 50.233,52.469 50.288,50.304 50.554 C 24.159 53.762,4.775 70.583,0.811 93.502 C 0.328 96.299,0.170 350.514,0.649 354.721 C 3.268 377.707,23.300 395.888,49.743 399.281 C 53.218 399.726,347.893 399.647,350.912 399.200 C 377.754 395.221,397.170 377.133,399.433 353.997 C 399.845 349.790,399.606 95.918,399.189 93.502 C 395.618 72.856,379.216 56.764,356.615 51.734 C 350.109 50.286,349.753 50.267,327.209 50.172 L 307.059 50.088 307.059 34.342 C 307.059 12.881,306.881 11.974,301.629 6.735 C 290.175 -4.692,269.294 -0.287,264.659 14.534 L 264.145 16.176 264.090 33.146 L 264.035 50.117 200.000 50.117 L 135.965 50.117 135.910 33.146 L 135.855 16.176 135.341 14.534 C 132.246 4.636,121.337 -1.365,110.130 0.664 M356.615 250.732 C 356.615 340.353,356.584 351.420,356.329 352.398 C 355.309 356.315,351.762 359.815,347.265 361.342 L 345.489 361.945 200.655 361.996 C 69.161 362.042,55.683 362.019,54.324 361.750 C 49.557 360.807,45.827 357.796,43.973 353.395 L 43.478 352.221 43.431 251.192 L 43.383 150.164 199.999 150.164 L 356.615 150.164 356.615 250.732"></path></svg>
						<span class="visually-hidden">Scritto il giorno </span><time datetime="<?= the_time( 'Y-m-d H:i:s' ) ?>"><?= the_time('j F Y'); ?></time>
					</li>
					<?php
						if ( !empty( $categories_html ) ) {
							echo '<li>
								<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 400 400"><path id="path0" d="M42.406 1.736 C 22.502 6.496,6.697 21.040,2.154 38.776 C 0.848 43.876,0.870 43.535,0.796 60.449 C 0.738 73.895,0.696 75.736,0.449 75.736 C 0.036 75.736,0.256 351.054,0.672 354.823 C 3.187 377.621,23.358 395.896,49.743 399.281 C 53.218 399.726,347.893 399.647,350.912 399.200 C 377.355 395.280,396.718 377.571,399.328 354.920 C 399.779 351.012,399.808 120.786,399.359 117.914 C 397.513 106.116,390.990 95.071,381.300 87.335 C 373.045 80.745,363.369 76.624,351.753 74.750 L 349.229 74.343 269.996 74.290 L 190.763 74.236 190.697 60.447 C 190.633 47.302,190.611 46.552,190.226 44.413 C 186.241 22.246,168.048 6.088,142.496 2.021 L 139.785 1.590 91.401 1.590 C 64.789 1.590,42.742 1.655,42.406 1.736 M136.848 39.567 C 141.927 40.611,146.064 44.280,147.268 48.808 C 147.494 49.660,147.542 51.933,147.543 62.038 L 147.546 74.240 102.431 74.241 C 77.618 74.241,54.224 74.296,50.444 74.364 L 43.572 74.488 43.572 56.974 L 43.572 39.460 61.290 39.411 C 124.280 39.238,135.358 39.261,136.848 39.567 M345.974 112.511 C 346.118 112.629,346.615 112.826,347.078 112.948 C 350.625 113.883,354.140 116.675,355.662 119.767 C 356.826 122.131,356.802 121.777,356.802 137.019 C 356.802 149.243,356.836 150.912,357.083 150.912 C 357.329 150.912,357.363 152.376,357.363 162.973 C 357.363 174.910,357.359 175.035,356.989 175.035 C 356.616 175.035,356.615 175.160,356.615 263.168 C 356.615 341.632,356.584 351.421,356.329 352.398 C 355.309 356.315,351.762 359.815,347.265 361.342 L 345.489 361.945 200.655 361.996 C 69.161 362.042,55.683 362.019,54.324 361.750 C 49.557 360.807,45.827 357.796,43.973 353.395 L 43.478 352.221 43.385 251.613 C 43.333 196.279,43.354 150.977,43.432 150.943 C 43.509 150.909,43.572 144.636,43.572 137.004 C 43.572 121.779,43.548 122.130,44.712 119.767 C 46.197 116.751,49.024 114.416,52.735 113.142 C 53.403 112.912,54.119 112.648,54.324 112.554 C 55.105 112.199,345.542 112.156,345.974 112.511 " stroke="none" fill="#000000" fill-rule="evenodd"></path></svg>
								<span class="visually-hidden">Archiviato in </span>' . $categories_html . 
							'</li>';
						}
						if ( !empty( $comments_html ) ) {
							echo '<li>
								<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 400 400"><path id="path0" d="M125.853 1.496 L 50.771 1.596 48.153 2.024 C 22.741 6.178,4.787 22.289,0.707 44.600 C 0.244 47.131,-0.166 204.311,0.159 254.605 L 0.334 281.720 0.810 284.338 C 4.823 306.410,22.958 322.492,48.424 326.561 L 50.958 326.966 124.965 327.019 C 190.915 327.067,198.971 327.043,198.971 326.791 C 198.971 326.539,203.532 326.508,240.252 326.512 L 281.533 326.517 282.360 328.522 C 282.815 329.626,283.346 330.865,283.541 331.276 C 283.735 331.688,284.175 332.698,284.518 333.520 C 285.844 336.699,287.622 340.828,287.799 341.138 C 287.902 341.317,287.985 341.598,287.985 341.764 C 287.985 342.046,289.372 345.465,290.330 347.546 C 290.567 348.060,290.970 348.986,291.225 349.603 C 291.910 351.259,294.125 356.406,294.524 357.270 C 294.715 357.681,295.309 359.070,295.845 360.355 C 296.381 361.641,297.062 363.215,297.358 363.854 C 297.654 364.492,297.896 365.058,297.896 365.111 C 297.896 365.165,298.137 365.736,298.432 366.381 C 298.726 367.027,299.410 368.607,299.951 369.892 C 300.491 371.178,301.176 372.752,301.472 373.391 C 301.768 374.030,302.010 374.595,302.010 374.648 C 302.010 374.700,302.211 375.188,302.457 375.731 C 302.703 376.274,303.173 377.367,303.501 378.161 C 303.829 378.954,304.259 379.809,304.456 380.060 C 304.654 380.312,304.815 380.601,304.815 380.703 C 304.815 380.805,305.110 381.326,305.470 381.861 C 305.830 382.396,306.125 382.888,306.125 382.955 C 306.126 383.022,306.378 383.345,306.686 383.674 C 306.994 384.003,307.247 384.339,307.248 384.422 C 307.249 384.505,307.624 384.951,308.081 385.414 C 308.538 385.877,308.916 386.365,308.921 386.500 C 308.926 386.635,309.014 386.693,309.116 386.629 C 309.219 386.566,309.303 386.650,309.303 386.816 C 309.303 386.983,309.388 387.067,309.490 387.003 C 309.593 386.940,309.677 386.974,309.677 387.080 C 309.677 387.295,313.036 390.278,313.212 390.220 C 313.274 390.200,313.863 390.562,314.521 391.025 C 315.179 391.487,315.768 391.869,315.830 391.873 C 315.892 391.878,316.237 392.042,316.597 392.239 C 316.957 392.436,317.314 392.579,317.391 392.558 C 317.468 392.537,317.532 392.618,317.532 392.738 C 317.532 392.858,317.587 392.901,317.654 392.834 C 317.721 392.767,318.058 392.886,318.402 393.099 C 318.746 393.311,319.028 393.434,319.028 393.372 C 319.028 393.309,319.280 393.389,319.589 393.548 C 319.897 393.708,320.150 393.791,320.150 393.734 C 320.150 393.676,320.328 393.724,320.547 393.841 C 320.930 394.046,321.288 394.127,322.487 394.277 C 322.796 394.315,323.343 394.410,323.703 394.486 C 324.063 394.563,324.757 394.636,325.245 394.648 C 325.734 394.660,326.134 394.738,326.134 394.822 C 326.134 394.905,326.225 394.917,326.336 394.848 C 326.448 394.779,327.100 394.693,327.786 394.656 C 328.471 394.620,329.327 394.538,329.687 394.475 C 330.047 394.412,330.552 394.336,330.809 394.307 C 331.471 394.232,336.030 392.375,336.359 392.046 C 336.509 391.895,336.698 391.772,336.778 391.772 C 336.857 391.772,337.096 391.625,337.308 391.445 C 337.520 391.265,337.886 390.991,338.122 390.837 C 338.961 390.288,341.822 387.276,342.219 386.524 C 342.351 386.273,342.525 386.024,342.607 385.971 C 342.750 385.876,344.273 382.719,344.273 382.517 C 344.273 382.462,344.437 382.061,344.638 381.625 C 346.126 378.393,346.493 371.749,345.423 367.424 C 345.329 367.043,345.291 366.693,345.339 366.644 C 345.388 366.596,345.306 366.276,345.158 365.934 C 345.010 365.591,344.660 364.596,344.380 363.721 C 344.100 362.847,343.591 361.501,343.249 360.729 C 342.908 359.958,342.203 358.317,341.682 357.083 C 341.162 355.849,340.305 353.871,339.779 352.688 C 338.746 350.368,337.541 347.339,337.541 347.062 C 337.541 346.968,337.213 346.155,336.812 345.255 C 336.411 344.355,335.658 342.609,335.137 341.374 C 334.617 340.140,333.761 338.163,333.236 336.980 C 332.710 335.797,332.040 334.240,331.747 333.520 C 331.453 332.800,330.823 331.328,330.345 330.248 C 329.868 329.168,329.315 327.885,329.117 327.396 L 328.757 326.508 335.253 326.504 C 347.646 326.496,352.880 326.010,359.514 324.255 C 362.855 323.370,368.201 321.441,368.896 320.869 C 369.017 320.770,369.774 320.369,370.579 319.978 C 385.694 312.638,398.133 296.875,399.322 283.554 C 399.375 282.957,399.518 281.501,399.639 280.318 C 400.148 275.357,399.699 47.607,399.174 44.507 C 395.429 22.364,377.179 6.040,351.473 1.837 L 348.855 1.409 274.895 1.403 C 234.217 1.400,167.148 1.442,125.853 1.496 M346.424 39.506 C 350.734 40.767,354.242 43.746,355.728 47.405 L 356.335 48.901 356.448 133.988 C 356.510 180.785,356.629 223.471,356.712 228.845 C 356.824 236.094,356.808 238.616,356.651 238.616 C 356.494 238.616,356.459 243.694,356.515 258.258 C 356.599 280.002,356.661 278.757,355.369 281.440 C 353.815 284.668,350.563 287.123,346.459 288.165 C 344.657 288.623,293.795 288.787,145.395 288.811 L 54.511 288.827 53.117 288.389 C 48.455 286.925,45.204 283.950,43.886 279.944 L 43.424 278.541 43.294 252.408 C 43.222 238.034,43.234 226.274,43.321 226.274 C 43.407 226.274,43.448 186.655,43.411 138.232 C 43.350 57.446,43.369 50.091,43.648 48.985 C 44.735 44.672,48.345 41.167,53.202 39.707 C 55.240 39.095,344.341 38.896,346.424 39.506 " stroke="none" fill="#000000" fill-rule="evenodd"></path></svg>' .
								$comments_html . 
							'</li>';
						}
					?>
				</ul>
				<?php endif // is_single ?>
			</header>
			<?php the_content( '<span class="visually-hidden">' . the_title( '', '', false ) . ': </span>Leggi il resto &raquo;', false ); ?>

			<?php if ( is_single() ): ?>
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5077645332807469" crossorigin="anonymous"></script>
				<ins class="adsbygoogle"
					style="display:block; text-align:center;"
					data-ad-layout="in-article"
					data-ad-format="fluid"
					data-ad-client="ca-pub-5077645332807469"
					data-ad-slot="1002746477"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

				<form action="/wp-comments-post.php" method="post" id="comment-form" onsubmit="if (this.author.value=='nome') return false;
					if (this.name.value=='nome') this.name.value = '';
					if (this.email.value=='email') this.email.value = '';
					if (this.url.value=='sito web') this.url.value = '';
					if (this.name.value=='' || this.comment.value=='') {
						alert('Beh, prova a metterci un pochino pi&ugrave; d\'impegno, no?');
						return false;
					}"> 
			<fieldset>
				<legend>Modulo per l'invio di un commento</legend>
				<h2 id="respond" class="comments-header">Lascia un commento</h2>
<?php if (!is_user_logged_in()){ ?>
				<p><label for="author" class="hidden">Nome</label> <input type="text" class="text" name="author" id="author" value="<?php echo isset($_COOKIE['comment_author_'.COOKIEHASH])?$_COOKIE['comment_author_'.COOKIEHASH]:'nome'; ?>" size="22" /></p>
				<p><label for="email" class="hidden">Indirizzo Email</label> <input type="text" class="text" name="email" id="email" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:'email'; ?>" size="22"/></p>
				<p><label for="url" class="hidden">Sito Web</label> <input type="text" class="text" name="url" id="url" value="<?php echo isset($_COOKIE['comment_author_url_'.COOKIEHASH])?$_COOKIE['comment_author_url_'.COOKIEHASH]:'sito web'; ?>" size="22" /></p>
<?php } else { ?>
				<input type="hidden" name="author" value="camu"/>
				<input type="hidden" name="email" value="info@duechiacchiere.it"/>
				<input type="hidden" name="url" value=""/>				
<?php } ?>
				<p><?php global $wp_subscribe_reloaded; if (isset($wp_subscribe_reloaded)){ echo $wp_subscribe_reloaded->stcr->subscribe_reloaded_show(); } ?></p>
				<p><label for="comment-text" class="hidden">Commento</label> <textarea name="comment" id="comment-text" cols="60" rows="10"></textarea></p>
				<p><input name="submit" type="submit" class="button" id="submit" value="Invia il tuo commento" /></p> 
				<?php comment_id_fields(); ?>
			</fieldset>
			</form>
			<?php endif ?>
		</article>
		<?php endwhile; ?>
	</main>

	<?php get_sidebar() ?>
</div>

<?php get_footer() ?>

<?php 

/*

get_header();
if (is_home()) echo '<p class="hidden">Elenco degli ultimi cinque articoli pubblicati</p>';
echo '<ul id="content">';
if (is_category()) echo '<li id="breadcrumbs">Ti trovi in '.(!empty($parent_category_link)?"$parent_category_link &raquo; ":'').$current_category_name.'</li>';
if (!empty($specific_date)) echo '<li id="breadcrumbs">Articoli '.$specific_date.'</li>';
if ( have_posts() ): 
	while ( have_posts() ): 
		the_post(); 
		$current_post_categories = wp_get_post_terms($post->ID, 'category', 'orderby=id');
		if (count($current_post_categories) > 1){
			$parent_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
			$current_category_name = $current_post_categories[1]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug.'/'.$current_post_categories[1]->slug, $current_post_categories[1]->name);
		}
		else{
			$parent_category_link = '';
			$current_category_name = $current_post_categories[0]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
		}
		echo '<li class="post-item"><h2>';
		$post_title = the_title('', '', false);
		echo '<a href="'.get_permalink().'">'.$post_title.'</a></h2>';
		$subtitle = get_post_meta($post->ID, 'my_subtitle', true);
		if (!empty($subtitle)) echo "<p class='subtitle'>$subtitle</p>";
?>
			<ul class="post-information">
				<li class="date"><span class='hidden'>scritto in data </span><?php the_time('j F Y') ?></li>
<?php
		echo '<li class="categories"><span class="hidden">archiviato in </span>'.(!empty($parent_category_link)?"$parent_category_link, ":'').$current_category_link.'</li>';
		switch(get_comments_number()){
			case 0:
				echo "<li class='comments'><a href='".get_permalink()."#respond'>scrivi un commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			case 1:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>c'&egrave; </span>1 commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			default:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>ci sono </span>".get_comments_number()." commenti<span class='hidden'> per $post_title</span></a></li>";
		}
		echo '</ul>';
		the_content('<span class="hidden">'.the_title('', '', false).': </span>Leggi il resto &raquo;</span>', FALSE);
		echo '</li>';
	endwhile; 
endif;
?></ul><!-- END: #content -->
		
<?php get_sidebar(); duechiacchiere_paginate(); ?>
		
	</div>
	<!-- END: #container -->

<?php get_footer() ?> */