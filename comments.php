<?php

if ( post_password_required() ) {
	return;
}

$comment_count = get_comments_number();
?>

<div id="comments" class="comments-area default-max-width">
	<?php if ( have_comments() ): ?>
		<h2>Commenti</h2>

		<ol>
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 45,
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
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'comment_field' => '<p class="comment-form-comment"><label for="comment" class="visually-hidden">Commento</label><textarea id="comment" name="comment" cols="10" rows="8" maxlength="65525" required="required"></textarea></p>',
		'fields' => array(
			'author' => '<p class="comment-form-author"><label for="author">Nome <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="5" maxlength="245" required="required"></p>',
			'email' => '<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label> <input id="email" name="email" type="text" value="" size="5" maxlength="100" required="required"></p>',
			'url' => '<p class="comment-form-url"><label for="url">Sito Web</label> <input id="url" name="url" type="text" value="" size="5" maxlength="200"></p>',
			'cookies' => '<input type="hidden" value="1" name="wp-comment-cookies-consent">'
		),
		'logged_in_as' => null,
		'title_reply' => 'Lascia un commento',
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>'
	) );
	?>
</div><!-- #comments -->