jQuery(document).ready(function(){
	
	// Add button
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
	 
	// Save button
	jQuery('#save-btn').click(function(e){
		e.preventDefault();
		// Get all data from form
		var nameValueArray = jQuery('#items-form').serializeArray();
		var data = {};
		for(var i = 0; i < nameValueArray.length; i++){
			data[nameValueArray[i]['name']] = nameValueArray[i]['value'];
		}
		
		// Do some AJAX to save everything (write PHP function to handle it server side)
		data['action']  = 'save_shop_items';
		//console.log(data);
		jQuery.post(ajaxurl, data, function(response) {
			// Display message
			if(response.trim() === 'ok') { 
				alert('Erfolgreich gespeichert.');
				// Reload to get correct ID values after insert
				location.reload(true);
			} else {
				alert('Ein Fehler beim Speichern ist aufgetreten. \nServer: ' + response.trim());
			}
		});
	});
	
	// Image loading on already existing rows
	var rows = jQuery('ol.wrap-items').children('li');
	for (var i = 0; i < rows.length; i++){
		add_on_clicks_to_row(jQuery(rows[i]), i);
	}
});
function new_item_row(i) {
	var array_of_nodes = jQuery.parseHTML( // This here is an ugly modified copy of what is written in admin/shop.php (I know no better way to do this)
		'<li>' +
			'<label id="item-img' + i + '">Bild<br><input type="text" name="img' + i +'" class="hidden">' +
			'<span class="item-icon dashicons dashicons-format-image"></span></label>' +
			'<label>Warenbezeichnung<br><input required type="text" name="title' + i + '" value=""></label>' +
			'<label>Beschreibung<br><textarea rows="3" cols="50" name="description' + i + '" ></textarea></label>' +
			'<label>Preis in CHF<br><input type="number" step="0.05" min="0" name="price' + i + '" value=""></label>' +
			'<br><span class="button remove">Entferne Artikel</span>' +
		'</li>'
	);
	// Adding onClick separatly for future compatibility of parseHTML (blocks scripts inside)
	var domNode = jQuery(array_of_nodes);
	add_on_clicks_to_row(domNode, i);
	i++;
	return domNode;
}
function add_on_clicks_to_row(row, index){
	if(row instanceof jQuery) {		
		// Image loading
		row.find('#item-img' + index).first().click(function(e){ 
			e.preventDefault();
			media_uploader(index);
		});
		
		// Remove button
		row.find('.remove').click(function(){ 
			// Sure?
			if(confirm('Artikel wirklich permanent löschen?')){
				var id = jQuery('[name=id' + index + ']').first().val();
				var row = jQuery(this).closest('li');
				if( id === undefined){
					row.remove();
					return;
				}
				// Remove on DB using AJAX
				var data = {
					'action': 'delete_shop_item',
					'id': id
				};
				//console.log(data);
				jQuery.post(ajaxurl, data, function(response) {
					// Display message
					if(response.trim() === 'ok') { 
						row.remove();
					} else {
						alert('Ein Fehler beim Löschen ist aufgetreten. \nServer: ' + response.trim());
					}
				});
			}
		});
		
	}
	else {
		console.log('Not a dome node:');
		console.log(row);
	}
}


function media_uploader(rowIndex) {	
	var mediaUploader;
	// Extend the wp.media object
	mediaUploader = wp.media.frames.file_frame = wp.media({
		title: 'Wähle ein Bild',
		button: { text: 'Wähle Bild' }, 
		multiple: false 
	});

	// When a file is selected, store URL in hidden input and display image
	mediaUploader.on('select', function() {
		var attachment = mediaUploader.state().get('selection').first().toJSON(); 
		var jqLabel = jQuery('#item-img' + rowIndex);
		jqLabel.find('input[type=text]').first().val(attachment.url);
		jqLabel.children().last().remove();
		jqLabel.append('<img class="item" src="' + attachment.url +'">');
	});
	mediaUploader.open();
}