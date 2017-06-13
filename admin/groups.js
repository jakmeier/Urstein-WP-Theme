jQuery(document).ready(function(){
	// Images
	jQuery('#group-pics-list label').click(function(e){
		e.preventDefault();
		var mediaUploader;
		// Extend the wp.media object
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Wähle ein Bild',
			button: { text: 'Wähle Bild' }, 
			multiple: false 
		});

		// When a file is selected, store URL in hidden input and send form
		var jqLabel = jQuery(this);
		mediaUploader.on('select', function() {
			var attachment = mediaUploader.state().get('selection').first().toJSON(); 
			jqLabel.find('input[type=text]').first().val(attachment.id);
			 document.getElementById('group-pic-form').submit();
		});
		mediaUploader.open();
	});
});

// Toggle
function toggle_has_event(id){
	jQuery('#toggle-form').find('input[type=text]').first().val(id);
	document.getElementById('toggle-form').submit();
}