<?php get_header(); ?>

	<div class="content section-inner">

	<?php if ( have_posts() ) : ?>
	
		<div class="page-title">
		
			<p>Suchresultate</p>
			
			<h4><?php echo ' "' . get_search_query() . '"'; ?></h4>
			
		</div>
				
		<div class="posts" id="posts">
				
	    	<?php while (have_posts()) : the_post(); ?>
	    	
	    		<?php get_template_part( 'content', get_post_format() ); ?>
	    		
	        <?php endwhile; ?>
            
            <div class="clear"></div>
				
		</div> <!-- /posts -->
	
		<div class="clear"></div>
		
		<?php urstein_archive_navigation(); ?>

	<?php else : ?>
					
		<div class="page-title">
	
			<p>Suchresultate</p>
			<h4><?php echo ' "' . get_search_query() . '"'; ?></h4>
			
		</div> <!-- /page-title -->
					
		<div class="post single">
			
			<div class="post-container">
		
				<div class="post-inner">
			
					<div class="post-content">
					
						<p>Leider wurde nichts gefunden.</p>
						
						<?php get_search_form(); ?>
					
					</div> <!-- /post-content -->
				
				</div> <!-- /post-inner -->
			
			</div> <!-- /post-container -->
		
		</div> <!-- /post -->
	
	<?php endif; ?>	
		
</div> <!-- /content -->
		
<?php get_footer(); ?>