	<h3 class="hidden">Pi&egrave; di pagina</h3>
	<ul id="footer-navigation">
		<li class="footer-block"><h4>L'inquilino</h4>
			<span class="footer-text">Sono un <a href="/ciao-america" title="il racconto del giorno del grande viaggio">cervello in fuga</a>. Dal 2008 risiedo in un ridente paesino del New Jersey, negli Stati Uniti.
				In questo blog raccolgo un po' di tutto, ed a volte racconto anche <a rel="author" href="/correva-lanno">di me stesso</a>. &Egrave; il mio piccolo laboratorio creativo, dove a volte faccio scoppiare qualche alambicco virtuale.</span></li>
		
		<li class="footer-block"><h4>Calce e mattoni</h4>
			<ul class="footer-list">
				<li><a title="sito italiano del sistema di gestione dei contenuti" href="http://www.wpitaly.it/" lang="en">wordpress</a></li>
				<li><a title="il fornitore italiano che ospita fisicamente i miei articoli" href="http://guadagnare.tophost.it/idevaffiliate.php?id=2458_4">tophost</a></li>
				<li><a title="il mio plugin per analizzare le statistiche di accesso al blog" href="http://wordpress.org/extend/plugins/wp-slimstat/" hreflang="en">slimstat</a></li>
				<li><a title="la licenza con cui sono rilasciati i contenuti" href="http://creativecommons.org/licenses/by-sa/2.5/it/" lang="en">creative commons</a></li>
				<li><a title="verifica che il codice sorgente di questa pagina sia sintatticamente valido" href="http://validator.w3.org/check/referer?lang=it"><acronym title="the extensible hyperText markup language" lang="en">xhtml</acronym> 1.0 strict</a></li>
				<li><a title="verifica che il foglio di stile di questa pagina sia sintatticamente valido" href="http://jigsaw.w3.org/css-validator/validator?uri=http://www.duechiacchiere.it&amp;profile=css3&amp;usermedium=all&amp;warning=1&amp;lang=it"><acronym title="cascading style sheet" lang="en">css</acronym> 3</a></li>
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
<?php /* if (!empty($do_halloween)): ?>
<p class="hidden"><object type="application/x-shockwave-flash" data="/wp-content/themes/duechiacchiere/swf/player.swf" width="290" height="24" id="audioplayer1">
<param name="movie" value="/wp-content/themes/duechiacchiere/swf/player.swf" />
<param name="FlashVars" value="loop=yes&amp;initialvolume=100&amp;autostart=yes&amp;playerID=1&amp;soundFile=%2Fwp-content%2Fthemes%2Fduechiacchiere%2Fswf%2Fhalloween.mp3" />
<param name="quality" value="high" /><param name="menu" value="false" />
<param name="bgcolor" value="#FFFFFF" /></object></p>
<?php endif */ ?>

<?php wp_footer(); ?>
<!-- BEGIN: Javascript -->
	<script type="text/javascript" src="http://static.duechiacchiere.it/javascript/duechiacchiere.js"></script>
<!-- END: Javascript -->
<!-- Page generated in <?php timer_stop(true, 2) ?> seconds. Memory usage: <?php echo number_format(memory_get_peak_usage(true), 0) ?> bytes -->
</body>
</html>