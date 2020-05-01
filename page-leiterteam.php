<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="../../wp-content/themes/urstein/leiterteam.css" media="screen" />
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					<h2>Abteilungsleitung</h2>
					<section class="leaders">	
						<?php
							$presidents = get_users(
								array(
									'role' => 'president',
									'fields' => array( 'ID' )
								));
							if(is_array($presidents)){
								foreach($presidents as $president) {
									set_query_var( 'userid', $president->ID );
									get_template_part( 'template-parts/user_avatar');
								}
							}

						?>
					</section>
					<?php 
						$divisions = array('Biberstufe', 'Wolfsstufe', 'Pfadistufe');
						for($i=0; $i<3; $i++):
					?>
					<h2> <?php echo $divisions[$i];?> </h2>
					<section class="leaders">
						<?php 
							$divlead = get_division_leader($i);
							if($divlead) {
								set_query_var( 'userid',  $divlead[0]);
								set_query_var( 'display_rank',  'Stufenleitung');
								get_template_part( 'template-parts/user_avatar' );
							}
							$groups = groups_by_stufe($i);
							if(is_array($groups)){
								foreach($groups as $group) {
									$leaders = get_leaders($group);
									if(is_array($leaders)) {
										foreach($leaders as $leader) {
											set_query_var( 'userid', $leader );
											set_query_var( 'display_rank', null );
											get_template_part( 'template-parts/user_avatar' );
										}
									}
								}
							}
						?>
					</section>
					<?php endfor; ?>
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