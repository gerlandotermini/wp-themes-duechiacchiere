	<?php
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
				<li><a href="https://akismet.com/" title="Sito del plugin per WordPress per filtrare lo spam nei commenti" hreflang="en">Il guardiano dei commenti</a></li>
				<li><a href="https://www.rxstrip.it/" title="Sito del disegnatore che ha donato un corpo virtuale a camu">Il disegnatore di camu</a></li>
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
				<li><a href="/contatto">Lascia un messaggio</a></li>
				<li><a href="/feed">Il <span lang="en">feed</span> degli articoli</a></li>
				<li><a href="/comments/feed">Il <span lang="en">feed</span> dei commenti</a></li>
				<li><a href="/feed/scrissi-oggi">Il <span lang="en">feed</span> di oggi nel passato</a></li>
				<li><a href="<?= $random_post_url ?>">Leggi un post a caso</a></li>
			</ul>
		</nav>
	</footer>

	<nav aria-labelledby="mobile-toolbar-label">
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
				<a href="#primary-menu" id="mobile-nav-button" aria-haspopup="true" class="svg icon-menu"><span class="visually-hidden">Apri il menu di navigazione</span></a>
			</li>
		</ul>
		<div id="menu-overlay"></div>
	</nav>

	<a id="backtotop" href="#body" class="svg icon-chevron-up">
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