<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="../../wp-content/themes/urstein/elternrat.css" media="screen" />
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					<section class="description">
						<?php echo nl2br(get_the_content()); ?>
						<?php edit_post_link(__('Text bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>
					</section>
					<section class="council">
						<?php 
							$displayed_ranks = array('Präsident', 'Vizepräsident', 'Kassier', 'Aktuar', 'Heim', 'Revisor', 'Bekleidung');
							$ranks = array('parents_council_president', 'parents_council_vice_president', 'parents_council_cashier', 'parents_council_actuary', 'parents_council_club_house', 'parents_council_auditor', 'shop_admin');
							for($i=0; $i<count($ranks); $i++) {
								$users = get_users(
									array(
										'role' => $ranks[$i],
										'meta_key' => 'group', 
										'meta_value' => 102,
										'fields' => array( 'ID' )
									));
								if(is_array($users)){
									foreach($users as $user){
										set_query_var( 'userid', $user->ID );
										set_query_var( 'display_rank', $displayed_ranks[$i] );
										get_template_part( 'template-parts/user_avatar' );
									}
								}
							} 
						?>
						
						<?php 
							$others = get_users(
								array(
									'role' => 'parents_council',
									'meta_key' => 'group', 
									'meta_value' => 102,
									'fields' => array( 'ID' )
								));
							if(is_array($others)){
								foreach($others as $user){
									set_query_var( 'userid', $user->ID );
									set_query_var( 'display_rank', 'Beisitz' );
									get_template_part( 'template-parts/user_avatar' );  
								}
							}
						?>
						
					</section>
		
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