<?php

if ( post_password_required() ) {
	return;
}

$comment_count = get_comments_number();
?>

<section id="comments" class="comments-area default-max-width">
	<div id="like-section">
		<button id="like-comment-reply">Mi piace</button><?php
	
	$post_likes = get_comments( [ 'type' => 'like', 'post_id' => $GLOBALS[ 'post' ]->ID ] );
	if ( !empty( $post_likes ) ) {
		echo '<h2 class="visually-hidden">Piace a</h2><ol>';
		$like_authors = [];

		foreach( $post_likes as $a_post_like ) {
			if ( '0' == $a_post_like->comment_approved || in_array( $a_post_like->comment_author, $like_authors ) ) {
				continue;
			}
	
			$avatar = get_avatar( $a_post_like->comment_author_email, 40, 'mystery', 'Avatar di ' . $a_post_like->comment_author, array( 'extra_attr' => 'aria-hidden="true" title="' . $a_post_like->comment_author . '"' ) );
			if ( !empty ( $a_post_like->comment_author_url ) ) {
				$avatar = '<a href="' . $a_post_like->comment_author_url . '">' . $avatar . '</a>';
			}

			echo '<li id="comment-' . get_comment_ID() .'" class="like-item">' . $avatar . '</li>';

			$like_authors[] = $a_post_like->comment_author;
		}

		echo '</ol><!-- .like-list -->';
	}

	echo '</div><div id="comment-section">';

	// $post_comments is initialized in index.php
	if ( !empty( $post_comments ) ) {
		echo '<h2>Commenti</h2><ol>';

		wp_list_comments( [
			'avatar_size' => 35,
			'callback' => array( 'duechiacchiere', 'comment_callback' ),
			'format' => 'html5',
			'short_ping' => true,
			'style' => 'ol',
			'type' => 'comment'
		] );
		
		echo '</ol><!-- .comment-list -->';

		if ( !comments_open() ) {
			echo '<p>I commenti a quest\'articolo sono chiusi.</p>';
		}
	}

	echo '</div>';

	comment_form( array(
		// This action makes the assumption that WP is installed in the 'wp' subdirectory
		'action' => get_home_url() . '/wp/wp-comments-post.php',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'comment_field' => '<p class="comment-form-comment"><label for="comment" class="visually-hidden">Commento</label><textarea id="comment" name="comment" cols="60" rows="8" maxlength="65525" required="required"></textarea></p>',
		'fields' => array(
			'author' => '<p class="comment-form-author"><label for="author">Nome <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="25" maxlength="245" required="required"></p>',
			'email' => '<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label> <input id="email" name="email" type="text" value="" size="25" maxlength="100" required="required"></p>',
			'url' => '<p class="comment-form-url"><label for="url">Sito Web</label> <input id="url" name="url" type="text" value="" size="25" maxlength="200"></p>',
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
		'submit_field' => '<p class="form-submit">%1$s <button id="cancel-comment-reply" style="display:none">Annulla</button> %2$s</p>'
	) ); ?>
</section><!-- #comments -->
