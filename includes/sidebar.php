<aside>
	<div class="widget">
		<h2 class="visually-hidden">Cerca nel sito</h2>
		<form id="search-form" role="search" action="/" method="get">
			<label for="search-field">
				<span class="visually-hidden">Digita le parole da cercare e premi enter</span>
			</label>

			<input type="text" id="search-field" name="s" placeholder="Spulcia nell'archivio...">

			<button type='submit' id="search-button" aria-label="Avvia la ricerca">
				<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 400 400"><path id="path0" d="M114.667 6.253 C -5.638 42.144,-40.098 197.362,53.130 283.428 C 104.533 330.882,185.952 339.126,246.000 302.958 L 261.333 293.722 261.333 302.224 C 261.333 315.475,346.446 400.000,359.789 400.000 C 373.078 400.000,400.000 373.040,400.000 359.731 C 400.000 346.396,315.424 261.333,302.166 261.333 L 293.722 261.333 302.958 246.000 C 378.996 119.759,256.595 -36.090,114.667 6.253 M207.177 75.993 C 253.957 99.112,273.813 155.954,251.568 203.070 C 206.652 298.202,65.229 267.621,65.362 162.805 C 65.458 88.145,139.749 42.667,207.177 75.993"></path></svg>
			</button>
		</form>
	</div>

	<?php
	// We use tags to group series together (tag = ID of first post in the series)
	if ( is_single() ) {
		$tags = get_the_tags( $GLOBALS[ 'post' ]->ID );
		if ( !empty( $tags ) ) {

			// Get all the posts in the series
			$args = array( 
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'post_tag',
						'field' => 'slug',
						'terms' => sanitize_title( $tags[ 0 ]->slug )
					)
				),
				'orderby' => 'date',
				'order' => 'ASC'
			);

			$posts_in_series = get_posts( $args );
			if ( !empty( $posts_in_series ) && count( $posts_in_series ) > 1 ) {
				echo '<div class="widget"><h2>Tutte le puntate</h2><ul>';
				
				foreach ( $posts_in_series as $a_post ) {
					if ( $a_post->ID == $GLOBALS[ 'post' ]->ID ) {
						echo '<li class="current-post">' . $a_post->post_title . '</li>';
					}
					else {
						echo '<li><a href="' . get_permalink( $a_post->ID ) . '">' . $a_post->post_title . '</a>';
						edit_post_link( '[M]', ' ', '', $a_post->ID );
						echo '</li>';
					}
					
				}
				
				echo '</ul></div>';
			}
		}
	}

	$how_many = ( ( !is_single() && !is_page() ) || strlen( $GLOBALS[ 'post' ]->post_content ) > 4000 || get_comments_number( $post->ID ) > 5 ) ? 5 : 3;
	$comments_list = get_comments( array(
		'status' => 'approve',
		'orderby' => 'comment_date',
		'number' => $how_many,
		'type' => 'comment',
		'author__not_in' => array( 1 ) 
	) );

	if ( !empty( $comments_list ) ) {
		echo '<div class="widget"><h2>Commenti recenti</h2><ul>';

		foreach ( $comments_list as $a_comment ) {
			$comment_post_title = get_the_title( $a_comment->comment_post_ID );
			$comment_permalink = get_comment_link( $a_comment->comment_ID );
			$comment_excerpt = duechiacchiere::get_substr_words( $a_comment->comment_content, 150 );
			echo '<li><h3><a title="Vai al commento che ' . $a_comment->comment_author . ' ha lasciato per l\'articolo intitolato ' . $comment_post_title . '" href="' . $comment_permalink .'">' . $a_comment->comment_author . ' su ' . $comment_post_title . '</a></h3>' . apply_filters( 'comment_text', $comment_excerpt ) . '</li>';
		}

		echo '</ul></div>';
	}

	if ( !is_404() ) {
		if ( !is_single() ) {
			$heading = 'Articoli a casaccio';
			$list_posts = get_posts( array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'numberposts' => $how_many,
				'orderby' => 'rand'
			) );
		}
		else {
			$heading = 'Nella stessa stanza';
			$terms = get_the_terms( get_the_ID(), 'category' );

			$list_posts = array();
			if ( !empty( $terms ) ) {
				$term_list = wp_list_pluck( $terms, 'slug' );

				$list_posts = get_posts( array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'numberposts' => $how_many,
					'orderby' => 'rand',
					'post__not_in' => array( $GLOBALS[ 'post' ]->ID ),
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field' => 'slug',
							'terms' => $term_list,
							'operator' => 'IN'
						)
					)
				) );
			}
		}

		if ( !empty( $list_posts ) ) {
			echo '<div class="widget"><h2>' . $heading . '</h2><ul class="plain-list">';
	
			foreach( $list_posts as $a_post ) {
				echo '<li><h3><a href="' . get_permalink( $a_post->ID ). '">' . $a_post->post_title . '</a>';
				edit_post_link( '[M]', ' ', '', $a_post->ID );
				echo '</h3><p>' . duechiacchiere::get_substr_words( $a_post->post_content, 150 ) . '</p></li>';
			}

			echo '</ul></div>';
		}
	}

	if ( is_front_page() && !is_paged() ) {
		$external = wp_nav_menu( array(
			'theme_location' => 'sidebar',
			'container' => '',
			'menu_class' => 'plain-list',
			'depth' => 1,
			'echo' => false,
			'fallback_cb' => '__return_false'
		) );

		if ( ! empty ( $external ) ) {
			echo '<div class="widget"><h2>Faccio cose, vedo siti</h2>';
			echo str_replace( array( '<a', '</a>' ), array( '<h3><a', '</a></h3>' ), $external );
			echo '</div>';
		}
	} ?>
</aside>