	<?php
	if ( $GLOBALS[ 'wp_query' ]->max_num_pages > 1 ): ?>
		<nav id="pagination" aria-labelledby="pagination-title">
			<h2 class="visually-hidden" id="pagination-title">Sfoglia le pagine del blog</h2>
			<?php
				$current_page = max( 1, intval( get_query_var( 'paged' ) ) );

				$pages = paginate_links( array(
					'base' => str_replace( 99999, '%#%', esc_url( get_pagenum_link( 99999 ) ) ), 
					'current' => $current_page,
					'format' => '?paged=%#%',
					'mid_size' => 1,
					'end_size' => ( $current_page < 3 || $current_page > $GLOBALS[ 'wp_query' ]->max_num_pages - 2 ) ? 4 : 2,
					'prev_text' => '<span class="visually-hidden">Pagina precedente</span>',
					'next_text' => '<span class="visually-hidden">Pagina successiva</span>',
					'before_page_number' => '<span class="visually-hidden">Pagina </span>',
					'total' => $GLOBALS[ 'wp_query' ]->max_num_pages,
					'type'  => 'array'
				) );

				if ( is_array( $pages ) ) {
					// Remove first and last from the array
					$prev_page = array_shift( $pages );
					$next_page = array_pop( $pages );

					// No "previous" link on page 2 and no "next" link on previous to last page (to make WAVE happy about adjacent identical links)
					if ( $current_page == 2 ) {
						$prev_page = array_shift( $pages );

						if ( $GLOBALS[ 'wp_query' ]->max_num_pages == 3 ) {
							$next_page = array_pop( $pages );
						}
					}
					else if ( $current_page == $GLOBALS[ 'wp_query' ]->max_num_pages - 1 ) {
						$next_page = array_pop( $pages );
					}

					if ( stripos( $prev_page, 'precedente' ) !== false ) {
						$prev_page = str_replace( 'page-numbers', 'svg page-numbers', $prev_page );
					}
					if ( stripos( $next_page, 'successiva' ) !== false ) {
						$next_page = str_replace( 'page-numbers', 'svg page-numbers', $next_page );
					}

					echo '<ul><li class="pagination-item prev' . ( ( $current_page == 1 ) ? ' current-item' : '' ) . '">' . $prev_page . '</li>';

					foreach ( $pages as $a_page_html ) {
						echo '<li class="pagination-item' . ( stripos( $a_page_html, 'current' ) !== false ? ' current-item' : '' ) . '">' . $a_page_html . '</li>';
					}

					echo '<li class="pagination-item next' . ( ( $current_page == $GLOBALS[ 'wp_query' ]->max_num_pages ) ? ' current-item' : '' ) . '">' . $next_page . '</li></ul>';
				}
			?>
		</nav>
	<?php
	elseif ( is_single() ): 
		$prev_post_link = get_previous_post_link( '%link' );
		$next_post_link = get_next_post_link( '%link' );
	?>
		<nav id="pagination" aria-labelledby="pagination-title">
			<h2 class="visually-hidden" id="pagination-title">Navigazione cronologica</h2>
			<ul class="pagination-flex">
				<?php if ( !empty( $prev_post_link ) ): ?><li class="svg prev"><?= $prev_post_link ?></li><?php endif; ?>
				<?php if ( !empty( $next_post_link ) ): ?><li class="svg next"><?= $next_post_link ?></li><?php endif; ?>
			</ul>
		</nav>
	<?php
	endif;

	// Random post
	$random_post = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '1' ) );
	$random_post_url = '/';
	if ( !empty( $random_post->posts ) ) {
		$random_post_url = get_permalink( $random_post->posts[ 0 ]->ID );
	}
	wp_reset_postdata();
	?>
	<footer>
		<div class="about-me">
			<h2>Due Chiacchiere</h2>
			<p>
				Tra vent'anni sarai pi&ugrave; dispiaciuto per le cose che non hai fatto che per quelle che hai fatto. Quindi sciogli gli ormeggi, naviga lontano dal porto sicuro. Cattura i venti delle opportunità nelle tue vele.
			</p>
		</div>

		<nav aria-labelledby="brick-mortar">
			<h2 id="brick-mortar">Calce e mattoni</h2>
			<ul>
				<li><a href="https://supporthost.com/it/a340" title="Il fornitore di hosting che ospita queste pagine">Il servizio che mi ospita</a></li>
				<li><a href="https://it.wordpress.org/" title="Sito italiano del sistema WordPress per la gestione dei contenuti">Il sistema di gestione</a></li>
				<li><a href="https://github.com/gerlandotermini/wp-themes-duechiacchiere" title="L'archivio dove scaricare una copia del mio tema" hreflang="en">Il vestito del blog</a></li>
				<li><a href="https://www.rxstrip.it/" title="Sito del disegnatore che ha donato un corpo virtuale a camu">Il disegnatore di camu</a></li>
				<li><a href="https://akismet.com/" title="Sito del plugin per WordPress per filtrare lo spam nei commenti" hreflang="en">Il guardiano dei commenti</a></li>
			</ul>
		</nav>

		<nav aria-labelledby="useful-links">
			<h2 id="useful-links">Collegamenti utili</h2>
			<ul>
				<li><a href="/chi-sono">Breve storia del tenutario</a></li>
				<li><a href="/licenza" rel="nofollow" title="Pagina in italiano che descrive la licenza per i contenuti">La licenza di attribuzione</a></li>
				<li><a href="/accessibile" title="Leggi le informazioni sul livello di accessibilit&agrave; di queste pagine">Un sito accessibile a tutti</a></li>
				<li><a href="/moderazione" title="Domande e risposte sulle mie regole di moderazione dei commenti">Le regole per i commenti</a></li>
				<li><a href="/privacy" title="Tutte le regole per il trattamento dei dati applicate su queste pagine">La tua <span lang="en">privacy</span> al sicuro</a></li>
				
			</ul>
		</nav>

		<nav aria-labelledby="misc-links">
			<h2 id="misc-links">Varie ed eventuali</h2>
			<ul>
				<li><a href="/feed">Il <span lang="en">feed</span> degli articoli</a></li>
				<li><a href="/comments/feed">Il <span lang="en">feed</span> dei commenti</a></li>
				<li><a href="/feed/scrissi-oggi">Il <span lang="en">feed</span> di oggi nel passato</a></li>
				<li><a href="<?= $random_post_url ?>">Leggi un post a caso</a></li>
				<li><a href="/contatto">Lascia un messaggio</a></li>
			</ul>
		</nav>
	</footer>

	<nav aria-labelledby="mobile-toolbar-label" id="mobile-toolbar-container">
		<h2 id="mobile-toolbar-label" class="visually-hidden">Navigazione mobile</h2>
		<ul id="mobile-toolbar">
			<li>
				<a href="/" class="svg icon-home"><span class="visually-hidden">Torna alla homepage</span></a>
			</li>
			<li>
				<a href="<?= $random_post_url ?>" class="svg icon-random"><span class="visually-hidden">Leggi un articolo a caso dal blog</span></a>
			</li>
			<li>
				<a href="#search-field" id="mobile-search-button" class="svg icon-search"><span class="visually-hidden">Vai al modulo per cercare tra gli articoli</span></a>
			</li>
			<li>
				<a href="#primary-menu" id="mobile-nav-button" aria-haspopup="true" class="svg icon-mobile-menu"><span class="visually-hidden">Apri il menu di navigazione</span></a>
			</li>
		</ul>
		<div id="menu-overlay"></div>
	</nav>

	<a id="backtotop" href="#body" class="svg">
		<span class="visually-hidden">Torna in cima alla pagina</span>
	</a>

	<!-- BEGIN: WP_Footer -->
	<?php wp_footer(); ?>

	<!-- END: WP_Footer -->
</body>
</html><?php

// Minify the output
$html = ob_get_contents();
ob_end_clean();

if ( !defined( 'WP_DEBUG' ) || !WP_DEBUG ) {
	// Remove line breaks and multiple spaces everywhere except inside <pre> tags
	$html = duechiacchiere::scrub_output( $html );

	// Add this page to the cache
	duechiacchiere::add_to_cache( $html );
}

echo $html;