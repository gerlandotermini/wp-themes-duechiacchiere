<?php include('header.php') ?>
		<ul id="content">
			<li id="breadcrumbs"><?php echo (($wp_query->found_posts==1)?'Trovato ':'Trovati ').$wp_query->found_posts.(($wp_query->found_posts==1)?' risultato':' risultati'); ?> per <strong><?php echo htmlspecialchars(stripslashes($_REQUEST['s'])) ?></strong></li>
<?php 
if ( have_posts() ): 
	while ( have_posts() ): 
		the_post(); 
		$current_post_categories = get_the_category();
		if (count($current_post_categories) > 1){
			ksort($current_post_categories);
			$current_post_categories = array_values($current_post_categories);
			$parent_category_link = duechiacchiere_get_category_link($current_post_categories[0]->category_nicename, $current_post_categories[0]->cat_name);
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug.'/'.$current_post_categories[1]->slug, $current_post_categories[1]->name);
		}
		else{
			$parent_category_link = '';
			$current_post_categories = array_values($current_post_categories);
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->category_nicename, $current_post_categories[0]->cat_name);
		}
?>
			<li><h2><?php $post_title = the_title('','',false) ?><a href="<?php the_permalink() ?>" title="<?php echo $post_title ?>, leggi il resto"><?php echo $post_title ?></a></h2>
			<ul class="post-information">
				<li class="date"><?php the_time('j F Y') ?></li>
				<li class="categories"><span class="hidden">Archiviato in </span><?php echo !empty($parent_category_link)?"$parent_category_link, ":''; echo $current_category_link; ?></li>
				<li class="comments"><?php comments_popup_link("Lascia un commento<span class='hidden'> per $post_title</span>",
						"<span class='hidden'> C'&egrave; </span>1 commento<span class='hidden'> per $post_title</span>", 
						"<span class='hidden'> Leggi i </span>% commenti<span class='hidden'> per $post_title</span>",
						'', '') ?></li><?php if (is_user_logged_in()) { echo "\n".'				<li class="post-edit">'; edit_post_link('modifica','',''); echo '</li>'; } ?>
			</ul>
			
			<?php the_content('<span class="hidden">'.the_title('', '', false).': </span> <span title="'.the_title('', '', false).', continua la lettura">Leggi il resto &raquo;</span>', FALSE) ?></li>
<?php endwhile; else: ?>
			<li><h2>La ricerca non ha prodotto alcun risultato</h2>
			<p>La stringa che hai inserito sembra esistere in nessuno dei tanti articoli presenti in questo blog.
			Complimenti per averla trovata! No, non hai vinto nulla di speciale, se non l'onore di poterti vantare
			con amici e parenti di questa cosa. Che si fa adesso? Beh, personalmente ti consiglierei di dare
			un'occhiata alla navigazione in alto, chiss&agrave; che non ci possa trovare qualche informazione
			utile. Se proprio non riesci a trovare quello che stavi cercando, ti consiglio di
			contattarmi, tramite l'<a href="/contatto" title="scrivimi un messaggio">apposito modulo da compilare</a>.</p>
			</li>
<?php endif ?>
		</ul>
		<!-- END: #content -->
		
<?php include('sidebar.php'); duechiacchiere_paginate(); ?>
	</div>
	<!-- END: #container -->
	
<?php get_footer() ?>