<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/event.css" media="screen" />
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="featured-media">		
									<?php the_post_thumbnail('medium'); ?>					
						</div> <!-- /featured-media -->
					<?php endif; ?>
				
			    	<?php the_content(); ?>
					<?php edit_post_link(__('Text bearbeiten','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
					
					<h3>Karte</h3>
					<?php
						set_query_var( 'mapid', 533 ); //hard coded Pfadheim mapid (local: 458, online: 533)
						get_template_part( 'template-parts/map' );
					?>
					
					<h3>Verantwortlich</h3>
					<?php 
							$responsible_person = get_post_meta(get_the_ID(), 'responsible_person', true);
							if($responsible_person){
								$users = array(get_user_by('ID', $responsible_person));
							}
							else {
								$users = get_users(
									array(
										'role' => 'parents_council_club_house',
										'fields' => array( 'ID' )
									));
							}
							if(is_array($users)){
								foreach($users as $user){
									set_query_var( 'userid', $user->ID );
								//	set_query_var( 'display_rank', 'Heimverwaltung' );
									get_template_part( 'template-parts/user_avatar' );
								}
							}
						?>
					
			    </div> <!-- /post-content -->
			    <div class="clear"></div>
			    
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; else: ?>
		<p><?php _e("Leider konnte die gesuchte Seite nicht gefunden werden.", "urstein"); ?></p>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- /content -->
<?php get_footer(); ?>