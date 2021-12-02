<?php 
/*
Template Name: Full Archive
*/

$sql = "SELECT DATE_FORMAT(`post_date`, '%d') d, DATE_FORMAT(`post_date`, '%m') m, DATE_FORMAT(`post_date`, '%Y') y, COUNT(*) c
		FROM $wpdb->posts
		WHERE `post_status` = 'publish' AND `post_type` = 'post'
		GROUP BY y,m,d";

$count_posts_by_date = $wpdb->get_results($sql, ARRAY_A);
$flat_posts_by_date = array();
foreach($count_posts_by_date as $a_day){
	$flat_posts_by_date[$a_day['y'].$a_day['m'].$a_day['d']] = $a_day['c'];
}
$custom_style = ' style="padding-right:0"';
include('header.php'); ?>
		<div id="content" style="padding:0 3%;width:94%">
			<h2><?php the_title() ?></h2>
			<p>Tutto il mio blog, giorno per giorno, condensato in un'unica pagina. Ecco quello che troverai nelle tabelle qui di seguito: un modo rapido ed intuitivo per accedere all'intero archivio, dal 2005 ad oggi. Con statistiche sul numero di articoli scritti all'anno, al mese ed al giorno (ferma il mouse su un giorno del calendario per vedere il numerino corrispodente). Buona consultazione!</p>
<h3><a title='8 articoli' href='/2005'>2005</a></h3>
<ul class='months-in-year'><li><a title='dicembre 2005: 8 articoli' class='month' href='/2005/12'>12</a><ul class='days-in-month'><li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li><li>8</li><li>9</li><li>10</li><li>11</li><li>12</li><li>13</li><li>14</li><li>15</li><li>16</li><li>17</li><li>18</li><li>19</li><li>20</li><li>21</li><li>22</li><li>23</li><li>24</li><li>25</li><li>26</li><li>27</li><li>28</li><li><a title='29 dicembre 2005: 3 articoli' href='/2005/12/29'>29</a></li><li><a title='30 dicembre 2005: 4 articoli' href='/2005/12/30'>30</a></li><li><a title='31 dicembre 2005: 1 articolo' href='/2005/12/31'>31</a></li></ul></li></ul>
<?php 
$anno = 2006;
$nome_mese = array('Segnaposto', 'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre');
$anno_corrente = date('Y');
$mese_corrente = date('m');
$giorno_corrente = date('d');
while ($anno <= $anno_corrente){
	$articoli_anno = 0;
	$html_anno = '';
	for ($mese=1;$mese<=12;$mese++){
		if (($anno == $anno_corrente) && ($mese > $mese_corrente)) break;
		$mese_padded = str_pad($mese, 2, "0", STR_PAD_LEFT);
		$giorni_del_mese = cal_days_in_month(CAL_GREGORIAN, $mese, $anno);
		$html_mese = '';
		$articoli_mese = 0;
		for ($giorno=1;$giorno<=$giorni_del_mese;$giorno++){
			if (($anno == $anno_corrente) && ($mese == $mese_corrente) && ($giorno > $giorno_corrente)) break;
			$giorno_padded = str_pad($giorno, 2, "0", STR_PAD_LEFT);
			
			if (empty($flat_posts_by_date[$anno.$mese_padded.$giorno_padded])){
				$html_mese .= "<li>$giorno</li>";
			}
			else{
				$articolo = ($flat_posts_by_date[$anno.$mese_padded.$giorno_padded] > 1)?'articoli':'articolo';
				$html_mese .= "<li><a title='$giorno {$nome_mese[$mese]} $anno: {$flat_posts_by_date[$anno.$mese_padded.$giorno_padded]} $articolo' href='/$anno/$mese/$giorno'>$giorno</a></li>";
				$articoli_mese += $flat_posts_by_date[$anno.$mese_padded.$giorno_padded];
			}			
		}
		$html_anno .= "<li><a title='{$nome_mese[$mese]} $anno: $articoli_mese articoli' class='month' href='/$anno/$mese'>$mese_padded</a> <ul class='days-in-month'>";
		$html_anno .= $html_mese;
		$html_anno .= '</ul></li>';
		$articoli_anno += $articoli_mese;
	}
	echo "<h3><a title='$articoli_anno articoli' href='/$anno'>$anno</a></h3><ul class='months-in-year'>";
	echo $html_anno;
	echo '</ul>';
	$anno++;
}
?>
		</div>
		<!-- END: #content -->		
	</div>
	<!-- END: #container -->
	
<?php get_footer() ?>