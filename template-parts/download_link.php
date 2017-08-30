<?php
	// Generates a buttom with a download link for a file.
	// The file to be downloaded should be stored in $file.
	if (isset($file)):
?>
<div class="download">
	<a href="<?php echo esc_html($file['url']); ?>">
		<span class="dashicons dashicons-media-default"></span> 
		Herunterladen 
	</a>
</div> <!-- /download -->
<?php endif; ?>