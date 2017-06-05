jQuery(document).ready(function(){
	
	// Populate add button with click-functionality
	jQuery('#add-item-btn').click(function(e){
		e.preventDefault();
		var i = jQuery('ol.wrap-items li').size();
		var newRow = new_item_row(i);
		if(newRow instanceof jQuery){
			jQuery('ol.wrap-items').append(newRow);
		} else {
			console.log('Not a dome node:');
			console.log(newRow);
		}
	 });
	
	// Populate save all button with click-functionality
	jQuery('#save-btn').click(function(e){
		e.preventDefault();
		alert('Todo');
		// Compute order numbers, read sizes in a way we can send it easily
		// Do some AJAX to save everything (write PHP function to handle it server side)
	});
});
function new_item_row(i) {
	var array_of_nodes = jQuery.parseHTML(// TODO: sizes
		'<li>' +
			'<label>Bild URL<br><span class="item-img dashicons dashicons-format-image"></span></label>' +
			'<label>Warenbezeichnung<br><input type="text" name="title' + i + '" value=""></label>' +
			'<label>Beschreibung<br><textarea rows="3" cols="50" name="description' + i + '" ></textarea></label>' +
			'<label>Preis in CHF<br><input type="number" name="price' + i + '" value=""></label>' +
			/*'<label>Position<br><input type="number" name="position' + i + '" value=""></label>' +*/
			'<br><span class="button remove">Entferne Artikel</span>' +
		'</li>'
	);
	// Adding onClick separatly for future compatibility of parseHTML (blocks scripts inside)
	var domNode = jQuery(array_of_nodes);
	add_on_clicks_to_rows(domNode);
	i++;
	return domNode;
}
function add_on_clicks_to_rows(row){
	if(row instanceof jQuery) {
		// Remove button
		row.find('.remove').click(function(){ jQuery(this).closest('li').remove(); });
		
		// Image loading
		var mediaUploader;
		row.find('.item-img').click(function(e) {
			e.preventDefault();
			if (!mediaUploader) {	
				// Extend the wp.media object
				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: 'Wähle ein Bild',
					button: { text: 'Wähle Bild' }, 
					multiple: false 
				});

				// When a file is selected, grab the URL and set it as the text field's value
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					jQuery(this).find('input[type=text]').val(attachment.url);
					// TODO: Display image here (Either from mediaUploader directly or using AJAX)
				});
			}
			mediaUploader.open();
		});
	}
	else {
		console.log('Not a dome node:');
		console.log(row);
	}
}