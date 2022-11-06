<?php

if ( post_password_required() ) {
	return;
}

$comment_count = get_comments_number();
?>

<section id="comments" class="comments-area default-max-width">
	<?php if ( have_comments() ): ?>
		<h2>Commenti</h2>
		<ol>
		<?php
			wp_list_comments(
				array(
					'avatar_size' => 45,
					'callback' => array( 'duechiacchiere', 'comment_callback' ),
					'format' => 'html5',
					'short_ping' => true,
					'style' => 'ol'
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
		// This action makes the assumption that WP is installed in the 'wp' subdirectory
		'action' => get_home_url() . '/wp/wp-comments-post.php',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'comment_field' => '<p class="comment-form-comment"><label for="comment" class="visually-hidden">Commento</label><textarea id="comment" name="comment" cols="10" rows="8" maxlength="65525" required="required"></textarea></p>',
		'fields' => array(
			'author' => '<p class="comment-form-author"><label for="author">Nome <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="5" maxlength="245" required="required"></p>',
			'email' => '<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label> <input id="email" name="email" type="text" value="" size="5" maxlength="100" required="required"></p>',
			'url' => '<p class="comment-form-url"><label for="url">Sito Web</label> <input id="url" name="url" type="text" value="" size="5" maxlength="200"></p>',
			'cookies' => '<input type="hidden" value="1" name="wp-comment-cookies-consent">'
		),
		'format' => 'html5',
		'id_form' => 'comment-form', // let's make sure this matches what the JS code is looking for (when attaching the form around)
		'logged_in_as' => null,
		'title_reply' => 'Lascia un commento',
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>',
		'id_submit' => 'comment-submit',
		'name_submit' => 'comment-submit',
		'submit_field' => '<p class="form-submit">%1$s <button rel="nofollow" id="cancel-comment-reply" style="display:none">Annulla risposta</button> %2$s</p>'
	) );
	?>
</section><!-- #comments -->
