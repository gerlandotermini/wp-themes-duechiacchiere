<?php
if ( post_password_required() ) {
    return;
}

$comment_count = get_comments_number();
?>

<section id="comments" class="comments-area default-max-width">
    <div id="post-like-section">
        <button id="like-comment-reply">Mi piace</button>
        <?php
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
        ?>
    </div>

    <div id="comment-section">
        <?php
        if ( !empty( $GLOBALS[ 'duechiacchiere_comment_count' ] ) ) {
            echo '<h2>Commenti</h2><ol>';

            $comment_like_button = function( $comment_text, $comment ) {
                if ( !$comment instanceof WP_Comment || 'comment' !== $comment->comment_type ) {
                    return $comment_text;
                }

                $like_count = get_comments( [
                    'count' => true,
                    'parent' => $comment->comment_ID,
                    'status' => 'approve',
                    'type' => 'like'
                ] );

                return $comment_text . sprintf(
                    '<span class="comment-likes"><a class="like-comment" href="#comment-form" data-comment-id="%1$d" aria-label="Mi piace questo commento">
                    <svg viewBox="0 0 26 26" fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                    <path d="M7 10v12H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h3Z"/>
                    <path d="M7 10c3-2 4-5 4-8 2 0 3 1 3 3v3h5a2 2 0 0 1 2 2l-1.5 9a2 2 0 0 1-2 2H7"/>
                    </svg> <span class="comment-like-count" aria-label="Numero di apprezzamenti">%2$s</span></a></span>',
                    absint( $comment->comment_ID ),
                    esc_html( number_format_i18n( (int) $like_count ) )
                );
            };
            add_filter( 'comment_text', $comment_like_button, 20, 2 );

            wp_list_comments( [
                'avatar_size' => 35,
                'callback' => array( 'duechiacchiere', 'comment_callback' ),
                'format' => 'html5',
                'short_ping' => true,
                'style' => 'ol',
                'type' => 'comment'
            ] );

            remove_filter( 'comment_text', $comment_like_button, 20 );

            echo '</ol><!-- .comment-list -->';

            if ( !comments_open() ) {
                echo '<p>I commenti a quest\'articolo sono chiusi.</p>';
            }
        }
        ?>
    </div>

    <style>
        
        #comments .comment-likes {
            display: inline-flex;
            font-size: var(--font-size-sm);
        }

        #comments .comment-likes a {
            align-items: center;
            background: none;
            border: 0;
            display: inline-flex;
            gap: var(--gap-xs);
            line-height: 1;
            text-decoration: none;
        }

        #comments .comment-likes a:hover,
        #comments .comment-likes a:focus {
            color: var(--color-link);
        }

        #comments .comment-likes svg {
            height: 1em;
            width: 1em;
        }

        #comments .comment-likes span {

        }
    </style>

    <script>
        document.querySelectorAll('.comment-likes').forEach((likeLink) => {
            const replyActions = likeLink.closest('li')?.querySelector('.reply');
            if (replyActions) replyActions.appendChild(likeLink);

            likeLink.querySelector('.like-comment')?.addEventListener('click', (event) => {
                event.preventDefault();
                const parentField = document.getElementById('comment_parent');
                const postLikeButton = document.getElementById('like-comment-reply');
                if (!parentField || !postLikeButton) return;
                parentField.value = event.currentTarget.dataset.commentId;
                postLikeButton.click();
            });
        });
    </script>

    <!-- Respond wrapper -->
    <div id="respond" class="comment-respond">
        <h2 id="reply-title" class="comment-reply-title" data-original-title="Dimmi la tua ">Dimmi la tua</h2>
        <form action="<?php echo esc_url( site_url( 'wp-comments-post.php' ) ); ?>" method="post" id="comment-form" class="comment-form">
            
            <p class="comment-form-comment">
                <label for="comment-editor" class="visually-hidden">Commento</label>
				<div id="comment-editor-toolbar"></div>
                <div id="comment-editor" contenteditable="true" class="comment-editor" style="border:1px solid #000"></div>
                <textarea id="comment" name="comment" style="display:none;"></textarea>
            </p>

            <?php if ( ! is_user_logged_in() ) : ?>
            <p class="comment-form-author">
                <label for="author">Nome <span class="required">*</span></label>
                <input id="author" name="author" type="text" value="" size="25" maxlength="245" required="required">
            </p>

            <p class="comment-form-email">
                <label for="email">Email <span class="required">*</span></label>
                <input id="email" name="email" type="email" value="" size="25" maxlength="100" required="required">
            </p>

            <p class="comment-form-url">
                <label for="url">Sito Web</label>
                <input id="url" name="url" type="text" value="" size="25" maxlength="200">
            </p>
            <?php endif; ?>

            <input type="hidden" value="1" name="wp-comment-cookies-consent">
            <input type="hidden" name="comment_post_ID" value="<?php the_ID(); ?>" id="comment_post_ID">
            <input type="hidden" name="comment_parent" id="comment_parent" value="0">

            <p class="form-submit">
                <input name="comment-submit" type="submit" id="comment-submit" class="submit" value="Invia commento">
                <button id="cancel-comment-reply" style="display:none">Annulla</button>
            </p>

        </form>
    </div>
</section>
