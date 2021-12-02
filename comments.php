<?php if ( have_comments() ) : ?>
			<h2 class="comments-header"><a class="icon rss" href="<?php echo get_post_comments_feed_link() ?>" title="segui i commenti a questo articolo"><span class="browser-hidden">[RSS]</span></a> Commenti</h2>
<?php $pagine = paginate_comments_links(array('echo'=>false,'prev_next'=>false)); if (!empty($pagine)) echo "<p>Pagine di commenti: $pagine</p>"; ?>
			<ul id="comments">
<?php wp_list_comments('callback=duechiacchiere_comment'); ?>
			</ul>
<?php endif; ?>