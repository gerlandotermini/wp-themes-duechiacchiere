<?php
/*
Template Name: Contact Form
*/

function cleanString($aString){
 $myHeaders = array(
  "/to\:/i",
  "/from\:/i",
  "/bcc\:/i",
  "/cc\:/i",
  "/content\-transfer\-encoding\:/i",
  "/content\-type\:/i",
  "/mime\-version\:/i" 
 ); 
 return stripslashes(strip_tags(urldecode(preg_replace($myHeaders, '', $aString))));
}

$myContactName = cleanString($_POST['myauthor']);
$myContactMail = cleanString($_POST['myemail']);
$myContactMesg = cleanString($_POST['mycomment']);
$myContactStage = cleanString($_POST['mycontact_stage']);

$myErrorMessage = "";

if (!empty($myContactName) && 
	!empty($myContactMail) && 
	!empty($myContactMesg) &&
	$myContactName != 'nome' &&
	strpos($myContactMail, '@') > 0) {

	
	$myRecipient = get_bloginfo('admin_email');
	
	$mySubject = 'Contatto - '.get_bloginfo('nome');
	$myHeaders = "MIME-Version: 1.0\n";
	$myHeaders .= "From: $myContactName <$myContactMail>\n";
	$myContentType = "Content-Type: text/plain; charset=iso-8859-1\"\n";
	$myHeaders .= $myContentType;
	
	$myCopyHeaders = "MIME-Version: 1.0\n";
	$myCopyHeaders .= "From: due chiacchiere <info@duechiacchiere.it>\n";
	$myCopyHeaders .= $myContentType;

	$myCopyMesg = "Grazie per avermi contattato. Il tuo messaggio è stato inviato con successo, e sarà mia cura risponderti nel più breve tempo possibile. Qui di seguito ti allego una copia di quello che hai scritto:\n\n";
	$myCopyMesg .= $myContactMesg . "\n\n";
	$myCopyMesg .= "Il tuo indirizzo IP: " . $_SERVER["REMOTE_ADDR"] . "\n\n";
	
	$myFullMesg = $myContactName . "\n\n";
	$myFullMesg .= $myContactMesg . "\n\n";
	$myFullMesg .= "Indirizzo IP: " . $_SERVER["REMOTE_ADDR"];

	if ( mail($myRecipient, $mySubject, $myFullMesg, $myHeaders) &&
			mail($myContactMail, $mySubject, $myCopyMesg, $myCopyHeaders) ) {
		$myContent = '<p>Grazie per avermi contattato. Il tuo messaggio &egrave; stato inviato con successo, riceverai una mia risposta al pi&ugrave; presto. Nel frattempo, se lo desideri, puoi continuare a dare un\'occhiata alle altre pagine del mio sito, usando la navigazione qui sopra. Ecco una copia di quello che hai scritto.';
		$myContent .= '</p><p><strong>Nome</strong>: ' . strip_tags($myContactName) . "<br />\n";
		$myContent .= '<strong>Email</strong>: ' . strip_tags($myContactMail) . "</p>\n";
		$myContent .= '<p><strong>Messaggio</strong>: <br />' . strip_tags($myContactMesg) . "</p>\n";
	}
	else {
		$myErrorMessage = 'Huston, abbiamo un problema. La signora delle pulizie deve aver staccato qualche filo per sbaglio. Per favore, riprova fra qualche minuto, mentre cerco di capire cosa sia successo.';
	}
}
elseif (!empty($myContactStage)) {
	$myErrorMessage = "Per favore, prova a metterci un po' pi&ugrave; d'impegno...";
}

include('header.php'); ?>
		<div id="content" class="post-single">
			<h1><?php the_title() ?></h1>
			<?php if (is_user_logged_in()) { echo '<p class="post-information">'; edit_post_link('modifica','',''); echo '</p>'; } ?>
			<?php if (!empty($myErrorMessage)) echo "<p>Error: $myErrorMessage</p>"; 
	if (!$myContactStage){ ?>
			<p>Usa il modulo qui di seguito se vuoi metterti in contatto con me. In genere rispondo in tempi brevi, ma se vedi che ci metto troppo, puoi lasciare un commento a qualche articolo. Chiss&agrave;, magari la mail &egrave; stata mangiata dal filtro antispam.</p>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="comment-form"
				onsubmit="if ( (this.comment.value == '')
						(this.author.value == '') ||
						(this.author.value == this.author.defaultValue) ) { alert('Magari prova a metterci un pochino d\'impegno, che ne dici?'); return false; }">
				<fieldset>
					<legend class="hidden">Modulo di contatto: nome, email, messaggio</legend>
					<p><label for="author" class="hidden">Nome</label> <input type="text" class="text" name="myauthor" id="author" size="22" maxlength="50" value="<?php echo isset($_COOKIE['comment_author_'.COOKIEHASH])?$_COOKIE['comment_author_'.COOKIEHASH]:'nome'; ?>" /></p>
					<p><label for="email" class="hidden">Email</label> <input type="text" class="text" name="myemail" id="email" size="22" maxlength="50" value="<?php echo isset($_COOKIE['comment_author_email_'.COOKIEHASH])?$_COOKIE['comment_author_email_'.COOKIEHASH]:'email'; ?>" /></p>
					<p><label for="comment">Messaggio</label> <textarea class="tall" name="mycomment" id="comment" cols="65" rows="7"></textarea></p>
					<p><input class="button" type="submit" name="Submit" value="Invia il messaggio" id="contact_submit" /></p>
					<input type="hidden" name="mycontact_stage" value="process" />
				</fieldset>
			</form>
<?php 
	}
	else
		echo $myContent;
?>
		</div>
		<!-- END: #content -->
<?php include('sidebar.php') ?>	
	</div>
	<!-- END: #container -->
	
<?php get_footer() ?>