<?php
	if(isset($userid)):
		$userdata = get_userdata($userid);
		$display_name = $userdata->display_name;
		$email = $userdata->user_email;
		
		$nickname = get_user_meta($userid, 'nickname', true);
	/*	if($nickname == '' || $nickname == $userdata->first_name) {
			$nickname = 'Nicht verfügbar';
		}*/
		
		$avatar = get_user_meta($userid, 'basic_user_avatar', true);
		if(is_array($avatar) ){
			if(wp_is_mobile() && isset ($avatar[96])){
				$url = $avatar[96];
			} elseif (isset ($avatar['full'])) {
				$url = $avatar['full'];
			} else {
				$url = $avatar[0];
			}
			$img = "<img src='$url'/>";
		} else {
			$img = get_avatar($userid); // Use gravatar's default avatar
		}
?>

<div class="user-avatar">
	<div class="image-and-rank">
	<?php 
		echo $img;
		if(isset($display_rank)){
			echo "<p class='rank'>$display_rank</p>";
		}
	?>
	</div>
	<div class="info-box">
		<h3><?php echo $nickname;?></h3>
		<p><?php echo $display_name;?></p>
		<p><a href="mailto:<?php echo $email;?>">Schreibe eine E-Mail</a></p>
	</div>
</div>

<?php endif; ?>