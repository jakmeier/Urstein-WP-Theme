<?php
	$map_displayed = false;
	if(isset($mapid) && $map = get_post($mapid)):
		$mapname =esc_html($map->post_title);
		$mapurl = esc_html($map->post_content);
		$x = get_post_meta($mapid, 'x', true);
		$y = get_post_meta($mapid, 'y', true);
		//parse data
		$components = parse_url($mapurl);
		//parse_str($components['query'], $query);
		$public_token = $components['path'];
		$public_token = substr($public_token, 3); //remove /d/

		// generate unique ID for container such that multiple maps can be displayed on a page
		$mapcontainerid = "mapcontainer" . get_auto_increment();
		
		if(!isset($zoom) || !is_numeric($zoom)){
			$zoom = 5;
		}
		 /* if(is_numeric($x) && is_numeric($y)) {
			echo "<div class=coordinate><p>$x|$y</p></div>"; 
			} */ ?>
		
		<script type="text/javascript">
		var zoomArg = <?php echo $zoom;?>;
		var container = "<?php echo $mapcontainerid;?>";
		
<?php
		// If we have a coordinate, display that
		if(is_numeric($x) && is_numeric($y)):
			$map_displayed = true;
?>
			var token = "<?php echo is_string($public_token)? $public_token : '-' ;?>";
			var x = <?php echo $x;?>;
			var y = <?php echo $y;?>;
			new SearchChMap({container: container, center:[x,y],zoom:zoomArg,poigroups:"default", drawing: token});

	<?php elseif($public_token)://display map drawing without coordinate 
			$map_displayed = true;
	?>
			var token = "<?php echo $public_token;?>";
			new SearchChMap({container: container, center:[739022,249957],zoom:zoomArg,poigroups:"default", drawing: token, marker:false});
	<?php endif; ?>
	</script>
	<?php if($map_displayed) :?>
	<div id="<?php echo $mapcontainerid;?>" class="map-container"> <noscript>
		<div> <a target="_top" href="$mapurl" alt="Karte: $mapname"></a> </div>
	</noscript></div>

<?php endif; endif; if(! $map_displayed) {
		echo '<p>Keine Karte zum Anzeigen gefunden.</p>';
	}
?>