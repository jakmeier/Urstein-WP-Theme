<?php
require_once (get_template_directory() . "/functions/shop_functions.php"); // get_shop_items
get_header(); ?>
<script>
	function validate_form(){
		var itemCount = jQuery('input[type=number]').filter(function() {
			return this.value != 0;
		}).length;
		if(itemCount <= 0){
			alert('Es wurden keine Artikel gewählt. Bitte bestelle mindesten ein Artikel.');
			return false;
		}
		return true;
	}
</script>

<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="../wp-content/themes/urstein/shop.css" media="screen" />
	<?php while (have_posts()) : the_post(); ?>	
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
				
			</div>
			<div class="post-inner">
			<section class="general-shop-text">
				<?php echo nl2br(get_the_content()); ?>
				<?php edit_post_link(__('Text bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>	
			</section>
			<form id="shop-form" onsubmit="return validate_form()" action="<?php echo get_permalink( get_page_by_path( 'new_order_action' ))?>" method="post" accept-charset="UTF-8">			<section class="item-table">
						 <?php
				$items = get_shop_items();
				foreach($items as $item):
			 ?>					<div class="item-row">
					<img class="item" src="<?php echo esc_url($item->image);?>">
					<div class="item-description">
						<h3><?php echo esc_html($item->title);?></h3>						<p class="item-description"> <?php echo nl2br(esc_html($item->description));?> </p>
						<p class="item-price">Preis: <?php echo esc_html($item->price);?> CHF </p>
						<label>Anzahl bestellen: <input autocomplete="off" type="number" min="0" max="99" name="item<?php echo intval($item->id); ?>" value="0"></label>
					</div>				</div> 
			<?php endforeach;?>
							</section>
			
			<section class="order-info">
				<h2>Bestellung</h2>
				<label>Vorname<br><input required name="firstname" type="text"></label>
				<label>Nachname<br><input required name="lastname" type="text"></label>
				<label>E-Mail<br><input required name="email" type="email"></label>
				<label>Telefon (optional)<br><input name="tel" type="tel"></label>
				<label>Kommentar (Eventuell Grösse angeben)<br><textarea name="comment" rows="8"></textarea></label>
				<input type="submit" name="order" value="Bestellen">
			</section>
			</form>			</div> <!-- /post-inner -->			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; ?>
	<div class="clear"></div>	
</div> <!-- /content -->
<?php get_footer(); ?>