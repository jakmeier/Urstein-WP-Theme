<?php
function custom_roles() {
	if ( is_admin() && isset($_GET['reload_caps']) && '1' == $_GET['reload_caps'] ) {
		
		// Remove unused roles
		if( get_role('editor') ){
			remove_role( 'editor' );
		}
		if( get_role('author') ){
			remove_role( 'author' );
		}		
		if( get_role('contributor') ){
			remove_role( 'contributor' );
		}
		if( get_role('subscriber') ){
			remove_role( 'subscriber' );
		}
		
		// Add some custom capabilities to admin
		$administrator     = get_role('administrator');
		$administrator->add_cap( 'edit_events' );
		$administrator->add_cap( 'edit_news' );
		$administrator->add_cap( 'edit_places' );
		$administrator->add_cap( 'edit_shop' );
		$administrator->add_cap( 'edit_quicklinks' );
		$administrator->add_cap( 'edit_groups' );
		$administrator->add_cap( 'edit_gallery' );
		$administrator->add_cap( 'read_attendees' );
		
		// Create custom roles
		// Leiter
		if(get_role('leader')){
			remove_role('leader');
		}
		add_role(
			'leader',
			__( 'Leiter' ),
			array(
				'read' => true,  
				'edit_pages' => true,
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false, //otherwise they could delete home or something...
				'upload_files' => true,
				'list_users' => true,
				'create_users' => false,
				'edit_users' => false,
				'promote_users' => false,
				'remove_users' => false,
				'delete_users' => false,
				'edit_events' => true,
				'edit_news' => true,
				'edit_places' => true,
				'edit_quicklinks' => true,
				'edit_groups' => true,
				'edit_shop' => false,
				'edit_gallery' => true, // editing albums (not used yet since albums are not implemented yet)
				'read_attendees' => true
			)
		);
		
		// Stufenleiter
		if(get_role('division_leader')){
			remove_role('division_leader');
		}
		add_role(
			'division_leader',
			__( 'Stufenleiter' ),
			array(
				'read' => true,  
				'edit_pages' => true,
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false, //otherwise they could delete home or something...
				'upload_files' => true,
				'list_users' => true,
				'create_users' => true,
				'edit_users' => true,
				'promote_users' => false,
				'remove_users' => false,
				'delete_users' => false,
				'edit_events' => true,
				'edit_news' => true,
				'edit_places' => true,
				'edit_quicklinks' => true,
				'edit_groups' => true,
				'edit_shop' => false,
				'edit_gallery' => true, // editing albums (not used yet since albums are not implemented yet)
				'read_attendees' => true
			)
		);
		
		// Abteilungsleiter
		if(get_role('president')){
			remove_role('president');
		}
		add_role(
			'president',
			__( 'AL' ),
			array(
				'read' => true,  
				'edit_pages' => true,
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false, //otherwise they could delete home or something...
				'upload_files' => true,
				'list_users' => true,
				'create_users' => true,
				'edit_users' => true,
				'promote_users' => true,
				'remove_users' => true,
				'delete_users' => false,
				'edit_events' => true,
				'edit_news' => true,
				'edit_places' => true,
				'edit_quicklinks' => true,
				'edit_groups' => true,
				'edit_shop' => true,
				'edit_gallery' => true, // editing albums (not used yet since albums are not implemented yet)
				'read_attendees' => true
			)
		);
		
		// Bekleidungsstelle
		if(get_role('shop_admin')){
			remove_role('shop_admin');
		}
		add_role(
			'shop_admin',
			__( 'Bekleidungsstelle' ),
			array(
				'read' => true,  
				'edit_pages' => true, // The webshop page should be editable
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false,
				'upload_files' => true,
				'list_users' => true,
				'create_users' => false,
				'edit_users' => false,
				'promote_users' => false,
				'remove_users' => false,
				'delete_users' => false,
				'edit_events' => false,
				'edit_news' => true, // News for the shop might be a thing
				'edit_places' => false,
				'edit_quicklinks' => false,
				'edit_groups' => false,
				'edit_shop' => true,
				'edit_gallery' => false,
				'read_attendees' => true
			)
		);

		// Elternratspräsident
		if(get_role('parents_council_president')){
			remove_role('parents_council_president');
		}
		add_role(
			'parents_council_president',
			__( 'Elternratspräsident' ),
			array(
				'read' => true,  
				'edit_pages' => true,
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false,
				'upload_files' => true, 
				'list_users' => true,
				'create_users' => true,
				'edit_users' => true,
				'promote_users' => true,
				'remove_users' => true,
				'delete_users' => false,
				'edit_events' => false,
				'edit_news' => true,
				'edit_places' => false,
				'edit_quicklinks' => true,
				'edit_groups' => false,
				'edit_shop' => true,
				'edit_gallery' => true,
				'read_attendees' => true
			)
		);
		
		// Elternrat
		$parents_council_caps = 
			array(
				'read' => true,  
				'edit_pages' => true, // The elternrat page should be editable
				'publish_pages' => true,
				'edit_others_pages' => true,
				'edit_published_pages' => true,
				'delete_pages' => false,
				'upload_files' => true, // to change the Elternrat page image
				'list_users' => true,
				'create_users' => false,
				'edit_users' => false,
				'promote_users' => false,
				'remove_users' => false,
				'delete_users' => false,
				'edit_events' => false,
				'edit_news' => true, // News from the Elternrat might be a thing
				'edit_places' => false,
				'edit_quicklinks' => false,
				'edit_groups' => false,
				'edit_shop' => false,
				'edit_gallery' => false,
				'read_attendees' => true
			);
		
		// Elternrat (Beisitz)
		if(get_role('parents_council')){
			remove_role('parents_council');
		}
		add_role(
			'parents_council',
			__( 'Elternrat' ),
			$parents_council_caps
		);

		// Elternrat: Vizepräsident
		if(get_role('parents_council_vice_president')){
			remove_role('parents_council_vice_president');
		}
		add_role(
			'parents_council_vice_president',
			__( 'Vizepräsident (Elternrat)' ),
			$parents_council_caps
		);
	
		// Elternrat: Revisor
		if(get_role('parents_council_auditor')){
			remove_role('parents_council_auditor');
		}
		add_role(
			'parents_council_auditor',
			__( 'Revisor (Elternrat)' ),
			$parents_council_caps
		);
		
		// Elternrat: Aktuar
		if(get_role('parents_council_actuary')){
			remove_role('parents_council_actuary');
		}
		add_role(
			'parents_council_actuary',
			__( 'Aktuar (Elternrat)' ),
			$parents_council_caps
		);
		
		// Elternrat: Kassier
		if(get_role('parents_council_cashier')){
			remove_role('parents_council_cashier');
		}
		add_role(
			'parents_council_cashier',
			__( 'Kassier (Elternrat)' ),
			$parents_council_caps
		);

		// Elternrat: Heim
		if(get_role('parents_council_club_house')){
			remove_role('parents_council_club_house');
		}
		add_role(
			'parents_council_club_house',
			__( 'Verwaltung Heim (Elternrat)' ),
			$parents_council_caps
		);
	}
}
add_action('admin_init', 'custom_roles');

?>