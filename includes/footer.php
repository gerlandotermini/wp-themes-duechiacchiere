	<footer aria-label="Collegamenti utili">
		<div class="about-me">
			<h2>Due Chiacchiere</h2>
			<p>
				L'arte di scrivere sta nel saper tirar fuori da quel nulla che si &egrave; capito della vita tutto il resto. Poi, finita la pagina, ti accorgi che quel che avevi capito &egrave; proprio un nulla.
			</p>
		</div>

		<nav>
			<h2>Calce e mattoni</h2>
			<ul>
				<li><a href="https://supporthost.com/it/a340" title="Il fornitore di hosting italiano che ospita dal 2005 queste pagine">Il servizio che mi ospita</a></li>
				<li><a href="https://it.wordpress.org/" title="Sito italiano del sistema WordPress per la gestione dei contenuti">Il sistema di gestione</a></li>
				<li><a href="https://github.com/gerlandotermini/wp-themes-duechiacchiere" title="L'archivio dove scaricare una copia del mio tema" hreflang="en">Il vestito del blog</a></li>
				<li><a href="https://www.rxstrip.it/">L'artista del ragazzo</a></li>
			</ul>
		</nav>

		<nav>
			<h2>Collegamenti utili</h2>
			<ul>
				<li><a href="https://creativecommons.org/licenses/by-sa/4.0/deed.it" rel="nofollow" title="pagina in italiano che descrive la licenza per i contenuti">La licenza di attribuzione</a></li>
				<li><a href="/accessibile" title="leggi le informazioni sul livello di accessibilit&agrave; di queste pagine">Un sito accessibile a tutti</a></li>
				<li><a href="/moderazione" title="domande e risposte sulle mie regole di moderazione dei commenti">Le regole per i commenti</a></li>
				<li><a href="/privacy">La tua <span lang="en">privacy</span> al sicuro</a></li>
				
			</ul>
		</nav>

		<nav>
			<h2>Varie ed eventuali</h2>
			<ul>
				<li><a href="/chi-sono" hreflang="en">Ti presento l'inquilino</a></li>
				<li><a href="/contatto" title="lasciami un messaggio tramite il modulo di contatto">Lascia un messaggio</a></li>
				<li><a href="/feed">Il <span lang="en">feed</span> <abbr title="really simple syndication" lang="en">RSS</abbr> degli articoli</a></li>
				<li><a href="/feed/scrissi-oggi">Il <span lang="en">feed</span> <abbr title="really simple syndication" lang="en">RSS</abbr> di oggi</a></li>
				
				
			</ul>
		</nav>
	</footer>

	<?php
	// Random post
	$random_post = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '1' ) );
	$random_post_url = '/';
	if ( !empty( $random_post->posts ) ) {
		$random_post_url = get_permalink( $random_post->posts[ 0 ]->ID );
	}
	wp_reset_postdata();
	?>

	<nav>
		<h2 class="visually-hidden">Collegamenti utili</h2>
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

	<button id="backtotop" onclick="window.scrollTo(0, 0);document.getElementById('page-top').focus();return false;">
		<span class="visually-hidden">Torna in cima alla pagina</span>
	</button>

	<!-- BEGIN: WP_Footer -->
	<?php wp_footer(); ?>

	<!-- END: WP_Footer -->
</body>
</html><?php
// Minify the output
$html = ob_get_contents();

ob_end_clean();

// Remove line breaks and multiple spaces everywhere except inside <pre> tags
$html = duechiacchiere::minify_output( $html );
echo $html;

if ( !defined( 'WP_DEBUG' || !WP_DEBUG ) ) {
	duechiacchiere::add_to_cache( $html );	
}