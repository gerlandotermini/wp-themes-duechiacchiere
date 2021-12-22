<?php include_once( 'header.php' ) ?>

<div id="content-wrapper">
	<main id="content">
		<?php /* echo (($wp_query->found_posts==1)?'Trovato ':'Trovati ').$wp_query->found_posts.(($wp_query->found_posts==1)?' risultato':' risultati'); ?> per <strong><?php echo htmlspecialchars(stripslashes($_REQUEST['s'])) ?></strong> */ ?>
		
		<?php
			if ( !is_single() ) {
				echo $intro_title;
			}
			while ( have_posts() ):
				the_post();

				if ( !is_single() ) {
					$categories = get_the_category( $GLOBALS[ 'post' ]->ID );
				}

				$categories_html = array();
				foreach ( $categories as $a_category ) {
					$categories_html[] = '<a href="' . get_category_link( $a_category->term_id ). '">' . $a_category->name . '</a>';
				}
				$categories_html = implode( ', ', $categories_html );

				$comment_count = get_comments_number();
				switch( $comment_count ) {
					case 0:
						$comments_html = "<a href=\"" . get_permalink() . "#rispondi\">Lascia un commento</a>";
						break;
					case 1:
						$comments_html = "<span class=\"visually-hidden\">c'&egrave; </span><a href=\"" . get_permalink() . "#commenti\">1 commento</a>";
						break;
					default:
						$comments_html = "<span class=\"visually-hidden\">ci sono </span><a href=\"" . get_permalink() . "#commenti\">{$comment_count} commenti";
				}
				$comments_html .= "<span class=\"visually-hidden\"> per {$GLOBALS[ 'post' ]->post_title}</span></a>";
		?>
		<article>
			<header>
				<<?= $title_tag ?>><a href="<?php the_permalink() ?>"><?php the_title( '', '' ) ?></a></<?= $title_tag ?>>
				<?php if ( $GLOBALS[ 'post' ]->post_type == 'post' ): ?>
				<p class="post-meta">
					<span class="visually-hidden">Scritto il giorno </span><time datetime="<?= the_time( 'Y-m-d H:i:s' ) ?>"><?= the_time('j F Y'); ?></time>
					<?php
						if ( !empty( $categories_html ) ) {
							echo '<span class="visually-hidden">Archiviato </span>in ' . $categories_html;
						}
					?>
				</ul>
				<?php endif // is_single ?>
			</header>
			<?php the_content( '<span class="visually-hidden">' . the_title( '', '', false ) . ': </span>Leggi il resto &raquo;', false ); ?>

			<?php if ( is_single() ): ?>
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6022102240639727" crossorigin="anonymous"></script>
				<ins class="adsbygoogle"
						style="display:block; text-align:center;"
						data-ad-layout="in-article"
						data-ad-format="fluid"
						data-ad-client="ca-pub-6022102240639727"
						data-ad-slot="6629110691"></ins>
				<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

				<?php if ( !post_password_required() ) comments_template(); ?>

				<form action="/wp-comments-post.php" method="post" id="comment-form" onsubmit="if (this.author.value=='nome') return false;
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
				<p><?php global $wp_subscribe_reloaded; if (isset($wp_subscribe_reloaded)){ echo $wp_subscribe_reloaded->stcr->subscribe_reloaded_show(); } ?></p>
				<p><label for="comment-text" class="hidden">Commento</label> <textarea name="comment" id="comment-text" cols="60" rows="10"></textarea></p>
				<p><input name="submit" type="submit" class="button" id="submit" value="Invia il tuo commento" /></p> 
				<?php comment_id_fields(); ?>
			</fieldset>
			</form>
			<?php endif ?>
		</article>
		<?php endwhile; ?>
	</main>

	<?php get_sidebar() ?>
</div>

<?php get_footer() ?>

<?php 

/*

get_header();
if (is_home()) echo '<p class="hidden">Elenco degli ultimi cinque articoli pubblicati</p>';
echo '<ul id="content">';
if (is_category()) echo '<li id="breadcrumbs">Ti trovi in '.(!empty($parent_category_link)?"$parent_category_link &raquo; ":'').$current_category_name.'</li>';
if (!empty($specific_date)) echo '<li id="breadcrumbs">Articoli '.$specific_date.'</li>';
if ( have_posts() ): 
	while ( have_posts() ): 
		the_post(); 
		$current_post_categories = wp_get_post_terms($post->ID, 'category', 'orderby=id');
		if (count($current_post_categories) > 1){
			$parent_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
			$current_category_name = $current_post_categories[1]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug.'/'.$current_post_categories[1]->slug, $current_post_categories[1]->name);
		}
		else{
			$parent_category_link = '';
			$current_category_name = $current_post_categories[0]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
		}
		echo '<li class="post-item"><h2>';
		$post_title = the_title('', '', false);
		echo '<a href="'.get_permalink().'">'.$post_title.'</a></h2>';
		$subtitle = get_post_meta($post->ID, 'my_subtitle', true);
		if (!empty($subtitle)) echo "<p class='subtitle'>$subtitle</p>";
?>
			<ul class="post-information">
				<li class="date"><span class='hidden'>scritto in data </span><?php the_time('j F Y') ?></li>
<?php
		echo '<li class="categories"><span class="hidden">archiviato in </span>'.(!empty($parent_category_link)?"$parent_category_link, ":'').$current_category_link.'</li>';
		switch(get_comments_number()){
			case 0:
				echo "<li class='comments'><a href='".get_permalink()."#respond'>scrivi un commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			case 1:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>c'&egrave; </span>1 commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			default:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>ci sono </span>".get_comments_number()." commenti<span class='hidden'> per $post_title</span></a></li>";
		}
		echo '</ul>';
		the_content('<span class="hidden">'.the_title('', '', false).': </span>Leggi il resto &raquo;</span>', FALSE);
		echo '</li>';
	endwhile; 
endif;
?></ul><!-- END: #content -->
		
<?php get_sidebar(); duechiacchiere_paginate(); ?>
		
	</div>
	<!-- END: #container -->

<?php get_footer() ?> */