<?php
require_once (get_template_directory() . "/functions/shop_functions.php"); // get_shop_items
get_header();
?>
<style>
		.order-overview {
			margin: 20px 0;
		}
</style>

<div class="content section-inner">
<div <?php post_class("post single"); ?>>
<div class="post-container">	
<div class="post-content">
<?php
	if(isset($_POST["order"], $_POST["firstname"], $_POST["lastname"], $_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$fullName = esc_html($_POST["firstname"]) . " " . esc_html($_POST["lastname"]);
		$email = esc_html($_POST["email"]);
		$webmaster = "webmaster@pfadiherisau.ch";

		$items = get_shop_items();
		$purchased =  array();
		$cost = 0;
		foreach($items as $item){
			//var_dump($item);
			$formKey = "item" . $item->id;
			if(isset($_POST[$formKey]) && $_POST[$formKey] !=0 ){
				$purchased[$item->title] = $_POST[$formKey];
				$cost += $_POST[$formKey] * $item->price;
			}
		}
		$order = "";
		foreach($purchased as $name => $count){
			$order .= $count . " " . $name . "\r\n";
		}
		$order .= "Preis total: " . $cost . "CHF\r\n"; 
		if(isset($_POST["comment"])){
			$order .= "Kommentar:\r\n" . $_POST["comment"] . "\r\n";
		}
		$order .= "\r\nKontaktangaben:\r\n";
		$order .= "Name: " . $fullName ."\r\n";
		$order .= "Email: " . $email . "\r\n";
		if(isset($_POST["tel"])){
			$order .= "Telefon: " . $_POST["tel"] . "\r\n";
		}
		//$to      = "bekleidung@pfadiherisau.ch";
		$to      = "jakmeier@ethz.ch";
		$subject = "Bestellung von " . $fullName;
		$message = 
			"Guten Tag,\r\n" .
			"Eine neue Bestellung von " . $fullName . " ist eingegangen. Hier die Bestellungsdetails:\r\n" .
			$order . "\r\n" .
			"Auf diese Nachricht kann direkt geantwortet werden um " . $email . " zu schreiben. Für technische Fragen und Probleme, wende dich bitte an " . $webmaster . ".\r\n" .
			"Freundliche Grüsse\r\nDer Pfadi Urstein Webserver"
			;
		$headers = "From: " . $webmaster . "\r\n" .
			"Reply-To: " . $email . "\r\n" .
			"Content-Type: text/plain; charset=UTF-8\r\n" .
			"Content-Transfer-Encoding: 8bit" .
			"X-Mailer: PHP/" . phpversion() . "\r\n" ;
			

		$succes = mail($to, $subject, $message, $headers);
		if($succes) {
			?>
				<div class="post-header">
					<h1 class="post-title">Erfolgreiche Bestellung</h1>
				</div>
				<div class="post-inner">
					<p>Deine Bestellung wurde erfolgreich der Bekldeiungstelle zugestellt.</p>
					<h3>Bestellungsübersicht:</h3>
					<div class="order-overview">
						<?php echo nl2br($order);?>
					</div>
					<p><a href="<?php echo get_permalink( get_page_by_path( "shop" ) );?>">Hier gehts zurück zum Shop.</a></p>
				</div> <!-- /post-inner -->
			<?php
		} else {
			?>
				<div class="post-header">
					<h1 class="post-title">Fehler bei der Bestellung</h1>
				</div>
				<div class="post-inner">
					<p>Entschuldige, deine Bestellung konnte nicht erfolgreich an übermittelt werden. Bitte bestelle direkt bei <a href="mailto:<?php echo $to?>"><?php echo $to?></a> oder melde den Fehler an den Wembaster unter der Adresse <a href="mailto:<?php echo $webmaster?>"><?php echo $webmaster?></a>.</p>
					<p><a href="#" onClick="history.go(-1);return true;">Hier gehts zurück zum Shop.</a></p>
				</div> <!-- /post-inner -->
			<?php
		}
	}
	else{
		?>
			<div class="post-header">
				<h1 class="post-title">Ungültige Anfrage</h1>
			</div>
			<div class="post-inner">
				<p>Die Anfrage für eine Bestellung konnte leider nicht bearbeitet werden. Falls du das Forumlar normal ausgefüllt hast und deine E-Mailadresse gültig ist, kontaktiere bitte den Webmaster unter <a href="mailto:<?php echo $webmaster?>"><?php echo $webmaster?></a>.</p>
			</div> <!-- /post-inner -->
		<?php
	}
?>
</div> <!-- /post-content -->
</div> <!-- /post-container -->
</div> <!-- /post -->
<div class="clear"></div>	
</div> <!-- /content -->

<?php get_footer(); ?>