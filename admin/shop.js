var form_original_data = '';
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
				window.onbeforeunload = null;
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
	
	// Store serialized form to check for changes later
	form_original_data = jQuery('#items-form').serialize();
	
	// Check for changes when user leaves 
	window.onbeforeunload = function(e) {
		if(!item_form_has_changed()) {
			e=null;
		} else { 
			return true;
		}
	}
});
function item_form_has_changed(){
	return jQuery('#items-form').serialize() != form_original_data;
}
function new_item_row(i) {
	// find largest position
	var max_pos = Number.parseInt(jQuery('ol.wrap-items li').last().children(':input[type="number"]').first().val());
	var array_of_nodes = jQuery.parseHTML( // This here is an ugly modified copy of what is written in admin/shop.php (I don't know a better way to do this)
		'<li>' +
			'<label id="item-img' + i + '">Bild<br><input type="text" name="img' + i +'" class="hidden">' +
			'<span class="item-icon dashicons dashicons-format-image"></span></label>' +
			'<input type="number" name="position' + i + '" class="hidden" value="' + (max_pos + 1) +'">' +
			'<label>Warenbezeichnung<br><input required type="text" name="title' + i + '" value=""></label>' +
			'<label>Beschreibung<br><textarea rows="3" cols="50" name="description' + i + '" ></textarea></label>' +
			'<label>Preis in CHF<br><input type="number" step="0.05" min="0" name="price' + i + '" value=""></label>' +
			'<br><span class="button remove">Entferne Artikel</span>' +
			'<span class="sort up dashicons dashicons-arrow-up-alt2"></span>' +
			'<span class="sort down dashicons dashicons-arrow-down-alt2"></span>' +
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
		
		// Sorting
		row.find('.up').click(function(e){
			e.preventDefault();
			shift_item_position(row,'up');
		});
		row.find('.down').click(function(e){
			e.preventDefault();
			shift_item_position(row,'down');
		});
		
	}
	else {
		console.log('Not a dome node:');
		console.log(row);
	}
}

// direction 1: up, 2: down
function shift_item_position(li, direction){
	if(!((li instanceof jQuery) && (direction === 'up' || direction === 'down'))){
		console.log('Invalid arguments in shift_item_position');
		return false;
	}
	var pos = li.children(':input[type="number"]').first();
	var posValue = pos.val(); 
	if(direction === 'up' && li.prev().is('li')){
		var otherPos = li.prev().children(':input[type="number"]').first();
		var otherPosValue = otherPos.val(); 
		pos.val(otherPosValue);
		otherPos.val(posValue);
		li.insertBefore(li.prev());
	} else if (direction === 'down' && li.next().is('li')){
		var otherPos = li.next().children(':input[type="number"]').first();
		var otherPosValue = otherPos.val(); 
		pos.val(otherPosValue);
		otherPos.val(posValue);
		li.next().insertBefore(li);
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