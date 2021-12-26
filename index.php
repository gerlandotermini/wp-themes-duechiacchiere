<?php include_once( 'header.php' ) ?>

<div id="content-wrapper">
	<main id="contenuto">
		<?php /* echo (($wp_query->found_posts==1)?'Trovato ':'Trovati ').$wp_query->found_posts.(($wp_query->found_posts==1)?' risultato':' risultati'); ?> per <strong><?php echo htmlspecialchars(stripslashes($_REQUEST['s'])) ?></strong> */ ?>
		
		<?php
			if ( !is_single() ) {
				echo $intro_title;
			}
		
			if ( !have_posts() ): ?>
				<article>
					<header>
						<h1>Hai trovato la stanza segreta</h1>
					</header>
					
					<p>
						E cos&igrave; ce l'hai fatta: dopo tante fatiche e tante prove, per bravura o per pura fortuna, hai finalmente accesso alla
						stanza segreta della mia casetta virtuale. Un luogo da cui si pu&ograve; accedere ai meandri inesplorati di questo sito, per
						cercare di capire cosa succede dietro le quinte, quando l'autore pensa di non essere osservato da nessuno.
					</p>
					<p>
						<span class="wp-caption aligncenter">
							<img src="<?= get_template_directory_uri() ?>'/img/mappa.jpg'" alt="una mappa planimetrica con tante scritte in inglese">
							<span class="wp-caption-text">L'arcana mappa del sito &egrave; finalmente stata svelata</span>
						</span>
					</p>
					<p>
						Come uno sbarbato Indiana Jones in erba, ora hai il compito di scoprire il significato di quest'illustrazione per
						concludere la tua missione impossibile, e risolvere il mistero che attanaglia tanti esploratori del web da decenni.
						Coloro i quali sapranno trovare la soluzione, avranno accesso ad immense fortune e illimitati poteri sovrumani.
					</p>
					<p>
						&mdash; Buona fortuna.
					</p>

			</article>
			
			<?php
			endif;

			while ( have_posts() ):
				the_post();

				if ( !is_single() ) {
					$categories = get_the_category( $GLOBALS[ 'post' ]->ID );

					// These categories might need to be sorted hierarchically
					usort( $categories, function( $category1, $category2 ) {
						foreach ( get_categories( array( "parent" => $category1->cat_ID ) ) AS $sub ) {
							if ( $category2->cat_ID == $sub->cat_ID ) {
								return -1;
							}
						}
						
						return 1;
					});
				}

				$categories_html = array();
				foreach ( $categories as $a_category ) {
					$categories_html[] = '<a href="' . get_category_link( $a_category->term_id ). '" title="Vai all\'archivio degli articoli per la categoria ' . $a_category->name . '">' . $a_category->name . '</a>';
				}
				$categories_html = implode( ', ', $categories_html );

				$comment_count = get_comments_number();
				switch( $comment_count ) {
					case 0:
						$comments_html = "<a href=\"" . get_permalink() . "#rispondi\">Lascia un commento</a>";
						break;
					case 1:
						$comments_html = "<span class=\"visually-hidden\">c'&egrave; </span><a href=\"" . get_permalink() . "#commenti\">1 commento</a>";
						break;
					default:
						$comments_html = "<span class=\"visually-hidden\">ci sono </span><a href=\"" . get_permalink() . "#commenti\">{$comment_count} commenti</a>";
				}
				$comments_html .= "<span class=\"visually-hidden\"> per {$GLOBALS[ 'post' ]->post_title}</span>";
		?>
		<article>
			<header>
				<<?= $title_tag ?>><a href="<?php the_permalink() ?>"><?php the_title( '', '' ) ?></a></<?= $title_tag ?>>
				<?php if ( $GLOBALS[ 'post' ]->post_type == 'post' ): ?>
				<p class="post-meta">
					<span class="visually-hidden">Scritto il giorno </span><time datetime="<?= the_time( 'Y-m-d H:i:s' ) ?>"><?= strtolower( the_time('j F Y') ); ?></time>
					<?php
						if ( !empty( $categories_html ) ) {
							echo '<span class="visually-hidden">Archiviato </span>in ' . $categories_html;
						}
						if ( !empty( $comments_html ) ) {
							echo ' &mdash; ' . $comments_html;
						}
					?>
				</p>
				<?php endif // is_single ?>
			</header>
			<?php the_content( '<span class="visually-hidden">' . the_title( '', '', false ) . ': </span>Leggi il resto &raquo;', false ); ?>

			<?php /* if ( is_single() ): ?>
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6022102240639727" crossorigin="anonymous"></script>
				<ins class="adsbygoogle"
						style="display:block; text-align:center;"
						data-ad-layout="in-article"
						data-ad-format="fluid"
						data-adtest="<?= ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) ? 'on' : 'off' ?>"
						data-ad-client="ca-pub-6022102240639727"
						data-ad-slot="6629110691"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
			<?php endif */ ?>
			</article>

			<?php if ( !post_password_required() ) comments_template(); ?>
		<?php endwhile; ?>

		<?php if ( is_archive() || is_front_page() ): ?>
		<nav aria-label="Sfoglia le pagine del blog" id="pagination">
			<ul>
				<?php
					$current_page = max( 1, intval( get_query_var( 'paged' ) ) );

					$pages = paginate_links( array(
						'base' => str_replace( 99999, '%#%', esc_url( get_pagenum_link( 99999 ) ) ), 
						'current' => $current_page,
						'format' => '?paged=%#%',
						'prev_text' => 'prev',
						'next_text' => 'next',
						'total' => $GLOBALS[ 'wp_query' ]->max_num_pages,
						'type'  => 'array'
					) );

					if ( is_array( $pages ) ) {

						if ( $current_page <= 2 ) {
							echo '
								<li class="pagination-item previous-page">
									<i></i>
								</li>';
						}
						else {
							echo '
								<li class="pagination-item previous-page">
									<a href="/page/' . ( $current_page - 1 ) . '">
										<span class="visually-hidden">
											Vai alla pagina precedente dell\'archivio
										</span>
									</a>
								</li>';
						}

						foreach ( $pages as $a_page_html ) {
							$loop_page = trim( strip_tags( $a_page_html ) );
							if ( $loop_page != 'prev' && $loop_page != 'next' ) {
								$a_page_html = str_replace( 'href=', "title=\"Vai alla pagina $loop_page dell'archivio\" href=", $a_page_html );
								echo "<li class=\"pagination-item\">$a_page_html</li>";
							}
						}

						if ( $current_page >= $GLOBALS[ 'wp_query' ]->max_num_pages - 1 ) {
							echo '
								<li class="pagination-item next-page">
									<i></i>
								</li>';
						}
						else {
							echo '
								<li class="pagination-item next-page">
									<a href="/page/' . ( $current_page + 1 ) . '">
										<span class="visually-hidden">
											Vai alla pagina successiva dell\'archivio
										</span>
									</a>
								</li>';
						}
					}
				?>
				
			</ul>
		</nav>
		<?php endif ?>
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