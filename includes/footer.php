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
				<li><a href="https://validator.w3.org/nu/?doc=https%3A%2F%2Fwww.duechiacchiere.it%2F" rel="nofollow" hreflang="en" title="pagina in inglese per verificare la correttezza semantica di un sito">Un codice impeccabile</a></li>
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

	<button id="backtotop" onclick="window.scrollTo(0, 0);">
		<span class="visually-hidden">Usa questo pulsante per tornare in cima alla pagina</span>
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

	<ul id="mobile-toolbar">
		<li>
			<a href="/">
				<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-70 -120 740 700"><path d="M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L512 185V64c0-17.7-14.3-32-32-32H448c-17.7 0-32 14.3-32 32v36.7L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32v69.7c-.1 .9-.1 1.8-.1 2.8V472c0 22.1 17.9 40 40 40h16c1.2 0 2.4-.1 3.6-.2c1.5 .1 3 .2 4.5 .2H160h24c22.1 0 40-17.9 40-40V448 384c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v64 24c0 22.1 17.9 40 40 40h24 32.5c1.4 0 2.8 0 4.2-.1c1.1 .1 2.2 .1 3.3 .1h16c22.1 0 40-17.9 40-40V455.8c.3-2.6 .5-5.3 .5-8.1l-.7-160.2h32z"/></svg>
			</a>
		</li>
		<li>
			<a href="/contatto">
				<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 128C0 92.65 28.65 64 64 64H448C483.3 64 512 92.65 512 128V384C512 419.3 483.3 448 448 448H64C28.65 448 0 419.3 0 384V128zM48 128V150.1L220.5 291.7C241.1 308.7 270.9 308.7 291.5 291.7L464 150.1V127.1C464 119.2 456.8 111.1 448 111.1H64C55.16 111.1 48 119.2 48 127.1L48 128zM48 212.2V384C48 392.8 55.16 400 64 400H448C456.8 400 464 392.8 464 384V212.2L322 328.8C283.6 360.3 228.4 360.3 189.1 328.8L48 212.2z"/></svg>
			</a>
		</li>
		<li>
			<a href="<?= $random_post_url ?>">
				<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-30 -70 640 640"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M403.8 34.4c12-5 25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V160H352c-10.1 0-19.6 4.7-25.6 12.8L284 229.3 244 176l31.2-41.6C293.3 110.2 321.8 96 352 96h32V64c0-12.9 7.8-24.6 19.8-29.6zM164 282.7L204 336l-31.2 41.6C154.7 401.8 126.2 416 96 416H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c10.1 0 19.6-4.7 25.6-12.8L164 282.7zm274.6 188c-9.2 9.2-22.9 11.9-34.9 6.9s-19.8-16.6-19.8-29.6V416H352c-30.2 0-58.7-14.2-76.8-38.4L121.6 172.8c-6-8.1-15.5-12.8-25.6-12.8H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96c30.2 0 58.7 14.2 76.8 38.4L326.4 339.2c6 8.1 15.5 12.8 25.6 12.8h32V320c0-12.9 7.8-24.6 19.8-29.6s25.7-2.2 34.9 6.9l64 64c6 6 9.4 14.1 9.4 22.6s-3.4 16.6-9.4 22.6l-64 64z"/></svg>
			</a>
		</li>
		<li>
			<a href="#" id="mobile-nav-button">
				<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M120 256c0 30.9-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56s56 25.1 56 56zm160 0c0 30.9-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56s56 25.1 56 56zm104 56c-30.9 0-56-25.1-56-56s25.1-56 56-56s56 25.1 56 56s-25.1 56-56 56z"/></svg>
			</a>
		</li>
	</ul>

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

// No need for HTML comments
$page_output = preg_replace( '/<!--(.*?)-->/', '', $page_output );

// No need for multiple spaces
$page_output = preg_replace( '/  +/', ' ', $page_output );

// Finally, remove all the EOL characters and tabbing
$page_output = preg_replace( "/[\n\t]*/", "", $page_output );

echo $page_output;