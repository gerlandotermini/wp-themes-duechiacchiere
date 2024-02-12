<?php include_once( 'includes/header.php' ) ?>

<div id="main-wrapper">
	<main id="content"><?php
		if ( is_404() || !have_posts() ) {
			include_once( 'includes/404.php' );
		}
		else {
			echo $heading_title;
		}

		while ( have_posts() ):
			the_post();

			if ( !empty( $_GET[ 'day' ] ) && !empty( $_GET[ 'monthnum' ] ) && get_the_time( 'Y' ) == date_i18n( 'Y' ) ) {
				continue;
			}

			if ( !is_single() ) {
				$categories = get_the_category( $GLOBALS[ 'post' ]->ID );

				// These categories might need to be sorted hierarchically
				usort( $categories, function( $category1, $category2 ) {
					foreach ( get_categories( array( 'parent' => $category1->cat_ID ) ) as $sub ) {
						if ( $category2->cat_ID == $sub->cat_ID ) {
							return -1;
						}
					}
					
					return 1;
				});
			}

			$categories_html = array();
			foreach ( $categories as $a_category ) {
				$categories_html[] = '<a href="' . get_category_link( $a_category->term_id ). '">' . $a_category->name . '</a>';
			}
			$categories_html = implode( ', ', $categories_html );

			$comment_count = get_comments_number();

			switch( $comment_count ) {
				case 0:
					$comments_html = '<a class="comments-link" href="' . get_permalink() . '#comment" aria-label="Esprimi la tua opinione su '. $GLOBALS[ 'post' ]->post_title . '">Lascia un commento</a>';
					break;
				case 1:
					$comments_html = '<a class="comments-link" href="' . get_permalink() . '#comments" aria-label="Leggi il commento per '. $GLOBALS[ 'post' ]->post_title . '">1 commento</a><span class="visually-hidden"> &mdash; </span><a class="skip-inline" href="' . get_permalink() . '#comment" aria-label="Esprimi la tua opinione su '. $GLOBALS[ 'post' ]->post_title . '">Lascia un commento</a>';
					break;
				default:
					$comments_html = '<a class="comments-link" href="' . get_permalink() . '#comments" aria-label="Leggi i ' . $comment_count . ' commenti per ' . $GLOBALS[ 'post' ]->post_title . '">' . $comment_count . ' commenti</a><span class="visually-hidden"> &mdash; </span><a class="skip-inline" href="' . get_permalink() . '#comment" aria-label="Esprimi la tua opinione su '. $GLOBALS[ 'post' ]->post_title . '">Lascia un commento</a>';
			}
			?>

			<article <?php if ( $GLOBALS[ 'wp_query' ]->current_post == $GLOBALS[ 'wp_query' ]->post_count - 1 ) { echo ' class="last"'; } ?>>
				<header>
					<<?= $title_tag ?>><?php if ( !is_single() && !is_page() ): ?><a href="<?php the_permalink() ?>"><?php endif; the_title( '', '' ); if ( !is_single() ): ?></a><?php endif ?></<?= $title_tag ?>>
					<?php if ( $GLOBALS[ 'post' ]->post_type == 'post' ): ?>
					<p class="post-meta">
						<span class="visually-hidden">Scritto il giorno </span><time datetime="<?php the_time( 'Y-m-d H:i:s' ) ?>"><?= ucfirst( get_the_time('j F Y') ); ?></time>
						<?php
							if ( !empty( $categories_html ) ) {
								echo '<span class="visually-hidden">, archiviato</span> in ' . $categories_html;
							}
							if ( !empty( $comments_html ) ) {
								echo ' <span class="comment-separator">&mdash;</span> ' . $comments_html;
							}
							if ( !is_single() ) {
								edit_post_link( '[M]', ' ' );
							}
						?>
					</p>
					<?php endif // is post ?>
				</header>
				<div class="entry">
					<?php the_content( 'Leggi il resto <span aria-hidden="true">&raquo;</span> <span class="visually-hidden">: ' . the_title( '', '', false ) . '</span>', false ); ?>
				</div>
			</article>

			<?php if ( is_single() && !post_password_required() && !is_attachment() ) comments_template( '/includes/comments.php' ); ?>
		<?php endwhile; ?>
	</main>

	<?php include_once( 'includes/sidebar.php' ) ?>
</div>

<?php include_once( 'includes/footer.php' ) ?>