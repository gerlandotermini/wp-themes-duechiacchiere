<?php 
/*
     Template Name: Subscribe To Comments
*/
if (isset($wp_subscribe_reloaded)) $wp_subscribe_reloaded->subscribe_reloaded_manage();
the_post();
include 'header.php' ?>
		<div id="content" class="post-single">
			<h1><?php echo $post_title = the_title('','',false) ?></h1>
			<?php the_content(); ?>
		</div>
		<!-- END: #content -->
		
<?php include('sidebar.php') ?>
		
	</div>
	<!-- END: #container -->

<?php include 'footer.php' ?>