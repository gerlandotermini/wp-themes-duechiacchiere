<?php get_header() ?>
		<div id="content" class="post-single">
<?php the_post() ?>
			<h1><?php echo $post_title = the_title('','',false) ?></h1>
			<?php if (is_user_logged_in()) { echo '<p class="post-information">'; edit_post_link('modifica','',''); echo '</p>'; } ?>
			<?php the_content() ?>
		</div>
		<!-- END: #content -->
		
<?php get_sidebar() ?>
		
	</div>
	<!-- END: #container -->

<?php get_footer() ?>