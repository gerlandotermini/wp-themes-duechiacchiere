<?php
/*
Template Name: Modulo di contatto
*/

$data = array(
	'author' => '',
	'email' => '',
	'message' => ''
);
$content = '';
$error_message = '';

if ( !empty( $_POST[ 'control' ] ) ) {
	foreach( $data as $a_key => $a_value ) {
		if ( empty( $_POST[ $a_key ] ) ) {
			$error_message = "Per favore, prova a metterci un po' pi&ugrave; d'impegno...";
			break;
		}

		$data[ $a_key ] = duechiacchiere::scrub_field( $_POST[ $a_key ], true );
	}

	if ( empty( $error_message ) && filter_var( $data [ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
		if ( class_exists( 'Akismet' ) ) {
			$akismet_request = array(
				'blog'                  => get_site_url(),
				'user_ip'               => $_SERVER[ 'REMOTE_ADDR' ],
				'user_agent'            => $_SERVER[ 'HTTP_USER_AGENT' ],
				'referrer'              => $_SERVER[ 'HTTP_REFERER' ],
				'comment_type'          => 'contact-form',
				'comment_author'        => $data[ 'author' ],
				'comment_author_email'  => $data[ 'email' ],
				'comment_content'       => $data[ 'message' ],
				'is_test'               => false,
			);
			$query_string = http_build_query( $akismet_request );
			$response = Akismet::http_post( $query_string, 'comment-check' );

			if ( 'true' != $response[ 1 ] ) {
				// Not spam, let's send the message
				$headers = "MIME-Version: 1.0\n";
				$headers .= "From: {$data[ 'author' ]} <{$data[ 'email' ]}>\n";
				$headers .= "Content-Type: text/plain; charset=iso-8859-1\"\n";
				
				$message = 'From: ' . $data[ 'author' ] . ' <' . $data[ 'email' ] . ">\n";
				$message .= 'IP: ' . $_SERVER[ 'REMOTE_ADDR' ] . "\n\n";
				$message .= $data[ 'message' ] . "\n\n";

				if ( mail( get_bloginfo( 'admin_email' ), 'Contatto - ' . get_bloginfo( 'name' ), $message, $headers ) ) {
					$content = '<p>Grazie per avermi contattato. Il tuo messaggio &egrave; stato inviato con successo, riceverai una mia risposta al pi&ugrave; presto.';
					$content .= "</p><p><strong>Nome</strong>: {$data[ 'author' ]}<br>\n";
					$content .= "<strong>Email</strong>: {$data[ 'email' ]}</p>\n";
					$content .= "<p><strong>Messaggio</strong>:<br> {$data[ 'message' ]}</p>\n";
				}
				else {
					$error_message = 'Huston, abbiamo un problema. La signora delle pulizie deve aver staccato qualche filo per sbaglio. Per favore, riprova fra qualche minuto, mentre cerchiamo di capire cosa sia successo.';
				}
			}
		}
	}
}

include_once( 'header.php' ) ?>

<div id="main-wrapper">
	<main id="content">
		<article>
			<header>
				<h1><?php the_title( '', '' ) ?></h1>
			</header>
			<?php if ( !empty( $error_message ) ) echo "<p>Error: $error_message</p>"; ?>

			<?php if ( empty( $content ) ): ?>
				<p>Usa il modulo qui di seguito se vuoi metterti in contatto con me. In genere rispondo in tempi brevi, ma se vedi che ci metto troppo, puoi lasciare un commento a qualche articolo. Chiss&agrave;, magari la mail &egrave; stata mangiata dal filtro antispam.</p>
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="comment-form">
					<legend class="visually-hidden">Modulo di contatto: nome, email, messaggio</legend>
					<p class="comment-form-author"><label for="author">Nome</label><input type="text" name="author" id="author" size="5" maxlength="250" required="required" value="<?php echo isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) ? $_COOKIE[ 'comment_author_' . COOKIEHASH ] : ''; ?>"></p>
					<p class="comment-form-email"><label for="email">Email</label> <input type="text" name="email" id="email" size="5" maxlength="250" required="required" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:''; ?>"></p>
					<p class="comment-form-comment"><label for="message">Messaggio</label> <textarea name="message" id="message" cols="15" rows="7" required="required"></textarea></p>
					<p class="form-submit"><input class="comment-submit" type="submit" name="comment-submit" value="Invia il messaggio" id="contact-submit"></p>
					<input type="hidden" name="control" value="<?= md5( date( 'His' ) ) ?>" />
				</form>
			<?php else: echo $content; endif; ?>
		</article>
	</main>

	<?php include_once( 'sidebar.php' ) ?>
</div>

<?php include_once( 'footer.php' ) ?>