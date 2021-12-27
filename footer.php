	<footer aria-label="Collegamenti utili">		
			<div>
				<h2>L'inquilino</h2>
				<p>Sono un <a href="/ciao-america" title="il racconto del giorno del grande viaggio">cervello in fuga</a>: dal 2008 risiedo in un ridente paesino del New Jersey, negli Stati Uniti.
				Quest'angolino sperduto della rete, dove condivido quello che mi passa per la testa, &egrave; da sempre il mio laboratorio creativo aperto al confronto di opinioni.</p>
			</div>

			<nav>
				<h2>Calce e mattoni</h2>
				<ul>
					<li><a href="https://www.tophost.it" title="il fornitore di hosting italiano che ospita dal 2005 queste pagine">Il servizio che mi ospita</a></li>
					<li><a href="https://it.wordpress.org/" title="sito italiano del sistema WordPress per la gestione dei contenuti">Il sistema di gestione</a></li>
					<li><a href="https://github.com/gerlandotermini/duechiacchiere.it-themes-duechiacchiere" hreflang="en" title="parte del software che sviluppo e rendo disponibile al pubblico">Il vestito digitale su <span lang="en">Github</span></a></li>
					<li><a href="/accessibile" title="leggi le informazioni sul livello di accessibilit&agrave; di queste pagine">Un sito accessibile a tutti</a></li>
					<li><a href="https://validator.w3.org/nu/?doc=https%3A%2F%2Fwww.duechiacchiere.it%2F" hreflang="en" title="pagina in inglese per verificare la correttezza semantica di un sito">Un codice impeccabile</a></li>
					<li><a href="https://creativecommons.org/licenses/by-sa/4.0/deed.it" title="pagina in italiano che descrive la licenza per i contenuti">Licenza di attribuzione</a></li>
				</ul>
			</nav>

			<nav>
				<h2>Varie ed eventuali</h2>
				<ul>
					<li><a href="https://www.linkedin.com/in/gerlando/" hreflang="en">Il mio profilo su LinkedIn</a></li>
					<li><a href="/privacy">La tua <span lang="en">privacy</span> al sicuro</a></li>
					<li><a href="/contatto" title="lasciami un messaggio tramite il modulo di contatto">La buca delle lettere</a></li>
					<li><a href="/feed">Il <span lang="en">feed</span> degli articoli in <abbr title="really simple syndication" lang="en">RSS</abbr></a></li>
					<li><a href="/diretta" title="quello che vedo dalla finestra del mio ufficio">In diretta dalla casa</a></li>
					<li><a href="/moderazione" title="domande e risposte sulle mie regole di moderazione dei commenti">Moderazione dei commenti</a></li>
				</ul>
			</nav>

			<nav>
				<h2>Indietro nel tempo</h2>
				<ul>
					<?php wp_get_archives('type=monthly&limit=6'); ?>
				</ul>
			</nav>
	</footer>

	<button id="backtotop" onclick="window.scrollTo(0, 0);"></button>

	<!-- BEGIN: WP_Footer -->
	<?php ob_start();
		wp_footer();
		$footer = ob_get_contents();
		ob_end_clean();
		$footer = str_replace( " type='text/javascript'", '', $footer );
		echo str_replace( ' type="text/javascript"', '', $footer ); 
	?>

	<!-- END: WP_Footer -->
</body>
</html>







<?php /*

<h3 class="hidden">Pi&egrave; di pagina</h3>
	<ul id="footer-navigation">
		<li class="footer-block"><h4>L'inquilino</h4>
			<span class="footer-text">Sono un <a href="/ciao-america" title="il racconto del giorno del grande viaggio">cervello in fuga</a>. Dal 2008 risiedo in un ridente paesino del New Jersey, negli Stati Uniti.
				In questo blog raccolgo un po' di tutto, ed a volte racconto anche <a rel="author" href="/correva-lanno">di me stesso</a>. &Egrave; il mio piccolo laboratorio creativo, dove a volte faccio scoppiare qualche alambicco virtuale.</span></li>
		
		<li class="footer-block"><h4>Calce e mattoni</h4>
			<ul class="footer-list">
				<li><a title="sito italiano del sistema di gestione dei contenuti" href="https://www.wpitaly.it/" lang="en">wordpress</a></li>
				<li><a title="il fornitore italiano che ospita fisicamente i miei articoli" href="https://guadagnare.tophost.it/idevaffiliate.php?id=2458_4">tophost</a></li>
				<li><a title="il mio plugin per analizzare le statistiche di accesso al blog" href="https://wordpress.org/extend/plugins/wp-slimstat/" hreflang="en">slimstat</a></li>
				<li><a title="la licenza con cui sono rilasciati i contenuti" href="https://creativecommons.org/licenses/by-sa/2.5/it/" lang="en">creative commons</a></li>
				<li><a title="verifica che il codice sorgente di questa pagina sia sintatticamente valido" href="https://validator.w3.org/check/referer?lang=it"><abbr title="hyperText markup language" lang="en">html</abbr> 5</a></li>
				<li><a title="verifica che il foglio di stile di questa pagina sia sintatticamente valido" href="https://jigsaw.w3.org/css-validator/validator?uri=https://www.duechiacchiere.it&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;lang=it"><abbr title="cascading style sheet" lang="en">css</abbr> 3</a></li>
			</ul></li>
		<li class="footer-block"><h4>Varie ed eventuali</h4>
			<ul class="footer-list">
				<li><a href="https://www.facebook.com/gerlando.nyc" hreflang="en">cercami su facebook</a></li>
				<li><a href="/scambio">scambio link, no grazie</a></li>
				<li><a href="/moderazione">moderazione dei commenti</a></li>
				<li><a href="/accessibile">accessibilit&agrave; dei contenuti</a></li>
				<li><a href="/questo-tema-non-sha-da-rilasciare">dove posso scaricare il tema?</a></li>
				<li><a href="/privacy">informativa sulla <span lang="en">privacy</span></a></li>
			</ul></li>
		<li class="footer-block"><h4>Indietro nel tempo</h4>
			<ul class="footer-list">
<?php wp_get_archives('type=monthly&limit=5'); ?>
				<li><a href="/archivio-completo">archivio completo</a></li>
			</ul></li>
	</ul>
	<!-- END: #footer-navigation -->	
</div>
<!-- END: #page-wrapper -->

<div id="back-to-top"><a href="#header" onclick="if(typeof ss_track == 'function'){ss_track(event, 5, 'backtotop');}smooth_top(); return false;"><span class="hidden">torna in cima</span></a></div>



</body>
</html> */