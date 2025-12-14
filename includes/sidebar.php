<aside>
	<div class="widget" id="search-form">
		<h2 class="visually-hidden">Cerca nel sito</h2>
		<form role="search" action="<?= esc_url( home_url( '/' ) ); ?>" method="get">
			<label for="search-field" class="visually-hidden">Digita le parole da cercare e premi invio</label>
			<input type="search" id="search-field" aria-controls="live-results" aria-expanded="false" aria-autocomplete="list" autocomplete="off" name="s" required placeholder="Cerca nell'archivio...">
			<button type='submit' id="search-button" class="svg" aria-label="Avvia la ricerca"></button>
		</form>
		<ul id="live-results" role="listbox" aria-live="polite" aria-label="Risultati della ricerca"></ul>

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
						echo '<li class="current-post">' . esc_html( $a_post->post_title ) . '</li>';
					}
					else {
						echo '<li><a href="' . esc_url( get_permalink( $a_post->ID ) ) . '">' . esc_html( $a_post->post_title ) . '</a>';
						edit_post_link( '[M]', ' ', '', $a_post->ID );
						echo '</li>';
					}
					
				}
				
				echo '</ul></div>';
			}
		}
	}

	// Single posts don't have this list because of the cache
	if ( is_front_page() ) {
		$comments_list = get_comments( array(
			'status' => 'approve',
			'orderby' => 'comment_date',
			'number' => 5,
			'type' => 'comment',
			'author__not_in' => array( 1 ) 
		) );

		if ( !empty( $comments_list ) ) {
			echo '<div class="widget"><h2>Commenti recenti</h2><ul>';

			foreach ( $comments_list as $a_comment ) {
				$comment_post_title = get_the_title( $a_comment->comment_post_ID );
				$comment_permalink = get_comment_link( $a_comment->comment_ID, [ 'cpage' => 0 ] );
				$aria_label = 'Vai al commento che ' . $a_comment->comment_author . ' ha lasciato per l\'articolo intitolato ' . $comment_post_title;
				$comment_excerpt = duechiacchiere::get_substr_words( $a_comment->comment_content, 150, $comment_permalink, $aria_label );
				$comment_author_link = !empty( $a_comment->comment_author_url ) ? '<a href="' . $a_comment->comment_author_url . '" aria-label="Visita il sito di ' . $a_comment->comment_author . ', apre una nuova finestra">' . $a_comment->comment_author . '</a>' : $a_comment->comment_author;
				echo '<li>';
				echo get_avatar( $a_comment, 40, 'mystery', 'Avatar di ' . $a_comment->comment_author, array( 'extra_attr' => 'aria-hidden="true"' ) );

				echo '<h3>' . $comment_author_link . ' su <a aria-label="' . $aria_label . '" href="' . $comment_permalink .'">' . $comment_post_title . '</a></h3>' . apply_filters( 'comment_text', $comment_excerpt ) . '</li>';
			}

			echo '</ul></div>';
		}
	}

	if ( !is_404() ) {
		$how_many = ( ( !is_single() && !is_page() ) || strlen( $GLOBALS[ 'post' ]->post_content ) > 4000 || get_comments_number( $post->ID ) > 4 ) ? 4 : 3;
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
				$post_permalink = get_permalink( $a_post->ID );
				$aria_label = 'Continua a leggere: ' . $a_post->post_title;

				echo '<li><h3><a href="' . $post_permalink . '">' . $a_post->post_title . '</a>';
				edit_post_link( '[M]', ' ', '', $a_post->ID );
				echo '</h3><p>' . duechiacchiere::get_substr_words( $a_post->post_content, 150 ) . '</p></li>';
			}

			echo '</ul></div>';
		}
	}

	if ( !is_front_page() ) {
		echo '<nav aria-labelledby="back-in-time" class="widget" id="widget-back-in-time"><h2 id="back-in-time">Indietro nel tempo</h2><ul class="plain-list">';

		$month_links = explode( '</li>', str_replace( array( '<li>', "\n" ), '', wp_get_archives( 'type=monthly&limit=120&echo=0' ) ) ); 
		$count_links = 0;

		foreach ( $month_links as $a_month_link ) {
			if ( $count_links > 4 ) {
				break;
			}
			if ( strpos( $a_month_link, date_i18n( 'F Y' ) ) !== false ) {
				continue;
			}
			
			echo '<li>' . trim( $a_month_link ) . "</li>";
			$count_links++;
		}

		echo '</ul></nav>';
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
