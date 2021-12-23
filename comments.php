<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$comment_count = get_comments_number();
?>

<div id="commenti" class="comments-area default-max-width">
	<?php if ( have_comments() ): ?>
		<h2>Commenti</h2>

		<ol>
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php if ( !comments_open() ) : ?>
			<p>I commenti a quest'articolo sono chiusi.</p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'logged_in_as' => null,
		'title_reply' => 'Lascia un commento',
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>'
	) );
	?>
</div><!-- #comments -->











<?php /* if ( have_comments() ) : ?>
			<h2 class="comments-header"><a class="icon rss" href="<?php echo get_post_comments_feed_link() ?>" title="segui i commenti a questo articolo"><span class="browser-hidden">[RSS]</span></a> Commenti</h2>
<?php $pagine = paginate_comments_links(array('echo'=>false,'prev_next'=>false)); if (!empty($pagine)) echo "<p>Pagine di commenti: $pagine</p>"; ?>
			<ul id="comments">
<?php wp_list_comments('callback=duechiacchiere_comment'); ?>
			</ul>
<?php endif; ?>

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

			*/