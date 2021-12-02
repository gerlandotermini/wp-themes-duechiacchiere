<?php
/*
Template Name: Diretta dalla casa
*/

$dir = '/home/mhd-01/www.duechiacchiere.it/htdocs/webcam/';
$handle = opendir($dir);
$latest_mtime = 0;
$latest_filename = '';
while (($file = readdir($handle)) !== false) {
	if (is_file($dir.$file) && filemtime($dir.$file) > $latest_mtime) {
		$latest_mtime = filemtime($dir.$file);
		$latest_filename = $file;
	}
}
closedir($handle);
$image = "<span class='wp-caption aligncenter' style='max-width:615px'><img class='size-full' alt='In diretta dalla casa di camu' src='/webcam/$latest_filename' height='460' width='615' /> <span class='wp-caption-text'> Ultimo aggiornamento: ".date_i18n('d F Y, H:i', $latest_mtime-18000)." (ora di New York)</span></span>";

include('header.php'); the_post(); ?>
		
		<div id="content" class="post-single">
			<h1><?php the_title() ?></h1>
			<?php if (is_user_logged_in()) { echo '<p class="post-information">'; edit_post_link('modifica','',''); echo '</p>'; } ?>
			<?php echo str_replace('[img-placeholder]', $image, apply_filters('the_content', get_the_content())) ?>
		</div>
		<!-- END: #content -->
<?php include('sidebar.php') ?>	
	</div>
	<!-- END: #container -->
	
<?php get_footer() ?>