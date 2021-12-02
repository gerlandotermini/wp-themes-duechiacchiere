<?php 
get_header();
if (is_home()) echo '<p class="hidden">Elenco degli ultimi cinque articoli pubblicati</p>';
echo '<ul id="content">';
if (is_category()) echo '<li id="breadcrumbs">Ti trovi in '.(!empty($parent_category_link)?"$parent_category_link &raquo; ":'').$current_category_name.'</li>';
if (!empty($specific_date)) echo '<li id="breadcrumbs">Articoli '.$specific_date.'</li>';
if ( have_posts() ): 
	while ( have_posts() ): 
		the_post(); 
		$current_post_categories = wp_get_post_terms($post->ID, 'category', 'orderby=id');
		if (count($current_post_categories) > 1){
			$parent_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
			$current_category_name = $current_post_categories[1]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug.'/'.$current_post_categories[1]->slug, $current_post_categories[1]->name);
		}
		else{
			$parent_category_link = '';
			$current_category_name = $current_post_categories[0]->slug;
			$current_category_link = duechiacchiere_get_category_link($current_post_categories[0]->slug, $current_post_categories[0]->name);
		}
		echo '<li class="post-item"><h2>';
		$post_title = the_title('', '', false);
		echo '<a href="'.get_permalink().'">'.$post_title.'</a></h2>';
		$subtitle = get_post_meta($post->ID, 'my_subtitle', true);
		if (!empty($subtitle)) echo "<p class='subtitle'>$subtitle</p>";
?>
			<ul class="post-information">
				<li class="date"><span class='hidden'>scritto in data </span><?php the_time('j F Y') ?></li>
<?php
		echo '<li class="categories"><span class="hidden">archiviato in </span>'.(!empty($parent_category_link)?"$parent_category_link, ":'').$current_category_link.'</li>';
		switch(get_comments_number()){
			case 0:
				echo "<li class='comments'><a href='".get_permalink()."#respond'>scrivi un commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			case 1:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>c'&egrave; </span>1 commento<span class='hidden'> per $post_title</span></a></li>";
				break;
			default:
				echo "<li class='comments'><a href='".get_permalink()."#comments'><span class='hidden'>ci sono </span>".get_comments_number()." commenti<span class='hidden'> per $post_title</span></a></li>";
		}
		echo '</ul>';
		the_content('<span class="hidden">'.the_title('', '', false).': </span>Leggi il resto &raquo;</span>', FALSE);
		echo '</li>';
	endwhile; 
endif;
?></ul><!-- END: #content -->
		
<?php get_sidebar(); duechiacchiere_paginate(); ?>
		
	</div>
	<!-- END: #container -->

<?php get_footer() ?>