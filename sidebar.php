		<aside>
			<h2>About section</h2>
			<p>Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
		</aside>
<?php /*
<div id="sidebar">
			<h3 class="hidden">cerca tra i miei articoli</h3>
			<form action="/" method="get" id="search-form" onsubmit="if(this.s.value=='') return false">
				<fieldset id="search-form-fieldset">
					<label class="hidden" for="s">parole da cercare</label>
					<input type="text" name="s" value="" size="13" class="text" id="s" />
					<label class="hidden" for="search-button">avvia la ricerca</label>
					<input type="submit" value="CERCA" id="search-button" class="button" />
					<a class="icon rss" href="https://feeds.feedburner.com/duechiacchiere" title="tieniti aggiornato sulle ultime notizie dalla casa">RSS</a>
					<a class="icon contact" href="/contatto" title="mandami un messaggio tramite il blog">Contattami</a>
				</fieldset>
			</form>
			<!-- END: #search-form -->

<?php
if (is_single()){
	$multi_chapter = get_post_meta($post->ID, "multi_chapter", true);
	if (!empty($multi_chapter)){
		$position_literals = array('prima', 'seconda', 'terza', 'quarta', 'quinta', 'sesta', 'settima', 'ottava', 'nona', 'decima', 'undicesima', 'dodicesima', 'tredicesima', 'quattordicesima', 'quindicesima', 'sedicesima', 'diciassettesima', 'diciottesima', 'diciannovesima', 'ventesima');
		$my_chapters_list = $wpdb->get_col('SELECT p.ID FROM wp_posts p INNER JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE pm.meta_key = "multi_chapter" AND p.post_status = "publish" AND pm.meta_value LIKE "'.substr($multi_chapter, 0, strpos($multi_chapter, '-')).'%" ORDER BY 0+SUBSTRING(meta_value, INSTR(meta_value, "-")+1) ASC');
		if (count($my_chapters_list) > 1){
			echo "			<h3>Le altre puntate</h3>\n			<ul class='side-list'>";
			foreach($my_chapters_list as $page_num => $page_id){
				if ($page_id != $post->ID)
					echo '				<li><a title="'.$position_literals[$page_num].' puntata" href="'.get_permalink($page_id).'">'.get_the_title($page_id)."</a></li>\n";
				//else
				//	echo '				<li class="block">'.get_the_title($page_id)."</li>\n";
			}
			echo "			</ul>\n";
		}
	}
}
?>			
			<h3>Commenti recenti</h3>
			<ul class="plain-list">
<?php 
$comments_list = get_comments('status=approve&orderby=comment_date&number=5&type=comment');
foreach($comments_list as $a_comment){
	$comment_post_title = get_the_title($a_comment->comment_post_ID);
	$top_comments_for_this_post = get_comments("status=approve&parent=0&post_id=$a_comment->comment_post_ID"); // excludes replies
	$comment_permalink = (count($top_comments_for_this_post) <= 35)?str_replace('/comment-page-1','',get_comment_link($a_comment->comment_ID)):get_comment_link($a_comment->comment_ID); // 35 is set in Wordpress
	$comment_excerpt = mb_substr(strip_tags($a_comment->comment_content), 0, 90);
	$comment_excerpt .= (strlen($a_comment->comment_content) > 90)?'...':'';
	echo "				<li><a title='commento a $comment_post_title' href='$comment_permalink'>$a_comment->comment_author</a>: $comment_excerpt</li>\n";
}
?>
			</ul>
		
			<h3>A caso dal mio archivio</h3>
			<ul class="plain-list">
<?php
$current_category_sidebar = (!isset($current_category_name) || ($current_category_name == 'ingresso'))?'':$current_category_name;
$numberposts = ((!is_single() && !is_page()) || strlen($post->post_content) > 4000 || get_comments_number( $post->ID ) > 5)?5:3;
$rand_posts = get_posts("numberposts=$numberposts&orderby=rand&category_name=$category_boy&exclude=$post->ID");
foreach( $rand_posts as $post ): setup_postdata($post);
?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php the_excerpt() ?></li>
<?php endforeach ?>

			</ul>
		</div>
		<!-- END: #sidebar -->

		*/