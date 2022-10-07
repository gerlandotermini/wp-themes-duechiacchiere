	<footer aria-label="Collegamenti utili">
		<div class="about-me">
			<h2>L'inquilino</h2>
			<p>Sono un <a href="/ciao-america" title="il racconto del giorno del grande viaggio">cervello in fuga</a>: nel 2008 mi sono trasferito negli Stati Uniti, alla ricerca del mio equilibrio interno.
			In questa casetta virtuale sperduta nei meandri della rete continuo ad accumulare i miei pensieri, chiss&agrave; che non tornino utili a qualche navigante smarrito.</p>
		</div>

		<nav>
			<h2>Indietro nel tempo</h2>
			<ul>
				<?php 
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
				?>
				<li><a href="/?day=<?= date_i18n( 'd' ) ?>&monthnum=<?= date_i18n( 'm' ) ?>&year=0" rel="nofollow">Oggi nel passato</a> [<a href="/feed/scrissi-oggi"><abbr title="really simple syndication" lang="en">RSS</abbr></a>]
			</ul>
		</nav>

		<nav>
			<h2>Calce e mattoni</h2>
			<ul>
				<li><a href="https://supporthost.com/it/a340" title="il fornitore di hosting italiano che ospita dal 2005 queste pagine">Il servizio che mi ospita</a></li>
				<li><a href="https://it.wordpress.org/" title="sito italiano del sistema WordPress per la gestione dei contenuti">Il sistema di gestione</a></li>
				<li><a href="/accessibile" title="leggi le informazioni sul livello di accessibilit&agrave; di queste pagine">Un sito accessibile a tutti</a></li>
				<li><a href="https://validator.w3.org/nu/?doc=https%3A%2F%2Fduechiacchiere.it%2F" rel="nofollow" hreflang="en" title="pagina in inglese per verificare la correttezza semantica di un sito">Un codice impeccabile</a></li>
				<li><a href="http://www.rxstrip.it/">L'artista del ragazzo</a></li>
				<li><a href="https://creativecommons.org/licenses/by-sa/4.0/deed.it" rel="nofollow" title="pagina in italiano che descrive la licenza per i contenuti">La licenza di attribuzione</a></li>
			</ul>
		</nav>

		<nav>
			<h2>Varie ed eventuali</h2>
			<ul>
				<li><a href="https://www.linkedin.com/in/gerlando/" hreflang="en">Il mio profilo LinkedIn</a></li>
				<li><a href="https://github.com/gerlandotermini" hreflang="en">Il codice che scrivo</a></li>
				<li><a href="/contatto" title="lasciami un messaggio tramite il modulo di contatto">La buca delle lettere</a></li>
				<li><a href="/feed">Il <span lang="en">feed</span> <abbr title="really simple syndication" lang="en">RSS</abbr> degli articoli</a></li>
				<li><a href="/privacy">La tua <span lang="en">privacy</span> al sicuro</a></li>
				<li><a href="/moderazione" title="domande e risposte sulle mie regole di moderazione dei commenti">Le regole per i commenti</a></li>
			</ul>
		</nav>
	</footer>

	<button id="backtotop" onclick="window.scrollTo(0, 0);document.getElementById('page-top').focus();return false;">
		<span class="visually-hidden">Torna in cima alla pagina</span>
	</button>

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
				<a href="/" class="svg toolbar-home"><span class="visually-hidden">Torna alla homepage</span></a>
			</li>
			<li>
				<a href="<?= $random_post_url ?>" class="svg toolbar-random"><span class="visually-hidden">Leggi un articolo a caso dal blog</span></a>
			</li>
			<li>
				<a href="#search-field" id="mobile-search-button" class="svg toolbar-search"><span class="visually-hidden">Vai al modulo per cercare tra gli articoli</span></a>
			</li>
			<li>
				<a href="#primary-menu" id="mobile-nav-button" aria-haspopup="true" class="svg toolbar-menu"><span class="visually-hidden">Apri il menu di navigazione</span></a>
			</li>
		</ul>
		<div id="menu-overlay"></div>
	</nav>

	<!-- BEGIN: WP_Footer -->
	<?php wp_footer(); ?>

	<!-- END: WP_Footer -->
</body>
</html>

<?php
// Minify the output
$page_output = ob_get_contents();

ob_end_clean();

// No need to have type defined in the script tag anymore
$page_output = str_replace( " type='text/javascript'", '', $page_output );
$page_output = str_replace( ' type="text/javascript"', '', $page_output );

// No need for HTML comments...
$page_output = preg_replace( '/<!--(.*?)-->/', '', $page_output );

// ... or multiple spaces
$page_output = preg_replace( '/  +/', ' ', $page_output );

// ... or trailing slashes in tags
$page_output = preg_replace( '/ ?\/>/', '>', $page_output );

// Finally, remove all the EOL characters and tabbing
$page_output = preg_replace( "/[\r\n\t]*/", "", $page_output );

echo $page_output;