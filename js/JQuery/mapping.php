<?php foreach($this->finds as $find) : ?>
jQuery(document).ready(function(){
	jQuery('#map').jmap('init', {'mapType':'hybrid','mapCenter':[55.958639, -3.162516],'AddFeed', {
			'feedUrl':'http://finds.org.uk/kml/<?php echo $find->county;?>.kml'});
	});
<?php endforeach; ?>