	<?php get_header() ?>
	
	<div id="content-wrapper">
		<main id="content">
			<article>
				<header>
					<h1>Article title</h1>
					<p>Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="#">Writer</a> - <a href="#comments">6 comments</a></p>
				</header>
				<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
			</article>
		</main>

		<?php get_sidebar() ?>
	</div>

	<?php get_footer() ?>



<?php /* get_header() ?>
		<div id="content" class="post-single"><?php the_post() ?>
			<h1><?php echo $post_title = the_title('','',false) ?></h1>
			<h2 class="hidden">Informazioni su questo articolo</h2>
<?php $subtitle = get_post_meta($post->ID, 'my_subtitle', true); if (!empty($subtitle)) echo "			<p class='subtitle'>$subtitle</p>"; ?>
				<ul class="post-information">
				<li class="date"><span class='hidden'>scritto in data </span><?php the_time('j F Y') ?></li>
<?php
		if (!empty($current_category_link)) echo '<li class="categories"><span class="hidden">Archiviato in </span>'.(!empty($parent_category_link)?"$parent_category_link, ":'').$current_category_link.'</li>';
		switch(get_comments_number()){
			case 0:
				break;
			case 1:
				echo "<li class='comments'><span class='hidden'>c'&egrave; </span>1 commento<span class='hidden'> per $post_title</span></li>";
				break;
			default:
				echo "<li class='comments'><span class='hidden'>ci sono </span>".get_comments_number()." commenti<span class='hidden'> per $post_title</span></li>";
		}
		echo '</ul>';
		$is_expired = get_post_meta($post->ID, 'is_expired', true); if (!empty($is_expired)): ?>
	<p class="expired-content"><span></span> Attenzione. Le informazioni contenute in quest'articolo sono obsolete o non pi&ugrave; valide. La pagina rimane comunque accessibile per evitarti la spiacevole avventura di un codice 404. Se hai bisogno di chiarimenti, non esitare a lasciare un commento.</p>
<?php endif; the_content(); $previous_post = get_previous_post();  $next_post = get_next_post(); if (!empty($previous_post) || !empty($next_post)): ?>
			<h4 class="hidden">navigazione interna</h4>
			<ul id="prev-next-navigation">
<?php if(!empty($previous_post)){
	$previous_post_link = get_permalink($previous_post->ID);
	echo "<li id='previous-article'><span class='hidden'>articolo precedente: </span><a href='$previous_post_link'>$previous_post->post_title</a></li>"; 
} 
if(!empty($next_post)){
	$next_post_link = get_permalink($next_post->ID);
	echo "<li id='next-article'><span class='hidden'>articolo successivo: </span><a href='$next_post_link'>$next_post->post_title</a></li>"; 
} endif; ?>
			</ul>
<?php //if (get_the_author() != 'sponsored'){ ?>
			<!-- <p class="advertising">
				<script type="text/javascript">
				google_ad_client = "pub-3395131461926192";
				google_ad_width = 468;
				google_ad_height = 60;
				google_ad_format = "468x60_as";
				google_ad_type = "text_image";
				google_ad_channel ="";
				google_color_border = "FFFFFF";
				google_color_bg = "FFFFFF";
				google_color_link = "444133";
				google_color_text = "444133";
				google_color_url = "6F685A";
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</p> -->
<?php
// }
if (!post_password_required()) comments_template(); ?>
			
			<form action="/wp-comments-post.php" method="post" id="comment-form"
				onsubmit="if (this.author.value=='nome') return false;
					if (this.name.value=='nome') this.name.value = '';
					if (this.email.value=='email') this.email.value = '';
					if (this.url.value=='sito web') this.url.value = '';
					if (this.name.value=='' || this.comment.value=='') {
						alert('Beh, prova a metterci un pochino pi&ugrave; d\'impegno, no?');
						return false;
					}"> 
			<fieldset>
				<legend>Modulo per l'invio di un commento</legend>
				<h2 id="respond" class="comments-header">Lascia un commento</h2>
<?php if (!is_user_logged_in()){ ?>
				<p><label for="author" class="hidden">Nome</label> <input type="text" class="text" name="author" id="author" value="<?php echo isset($_COOKIE['comment_author_'.COOKIEHASH])?$_COOKIE['comment_author_'.COOKIEHASH]:'nome'; ?>" size="22" /></p>
				<p><label for="email" class="hidden">Indirizzo Email</label> <input type="text" class="text" name="email" id="email" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:'email'; ?>" size="22"/></p>
				<p><label for="url" class="hidden">Sito Web</label> <input type="text" class="text" name="url" id="url" value="<?php echo isset($_COOKIE['comment_author_url_'.COOKIEHASH])?$_COOKIE['comment_author_url_'.COOKIEHASH]:'sito web'; ?>" size="22" /></p>
<?php } else { ?>
				<input type="hidden" name="author" value="camu"/>
				<input type="hidden" name="email" value="info@duechiacchiere.it"/>
				<input type="hidden" name="url" value=""/>				
<?php } ?>
				<p><?php if (function_exists('subscribe_reloaded_show')) subscribe_reloaded_show(); ?></p>
				<p><label for="comment-text" class="hidden">Commento</label> <textarea name="comment" id="comment-text" cols="60" rows="10"></textarea></p>
				<p><input name="submit" type="submit" class="button" id="submit" value="Invia il tuo commento" /></p> 
				<?php comment_id_fields(); ?>
			</fieldset>
			</form> 
		</div>
		<!-- END: #content -->
		
<?php get_sidebar() ?>
		
	</div>
	<!-- END: #container -->

<?php get_footer() ?> */