<div id="map" class="map"></div>

<?php	
	include_once 'lib/map/config/config.php';
	
	// Initialisation de la latitude et de la longitude avec les coordonnées du pays, issu du fichier de config.
	$latitude = floatval(MAP_INIT_LATITUDE);
	$longitude = floatval(MAP_INIT_LONGITUDE);

	//Géolocalisation de l'adresse IP du visiteur
	$localisation = IP_geolocalisation( $_SERVER['REMOTE_ADDR'] );
		
	// On prends la première ligne de la liste retournée.
	$latitude = $localisation['latitude'];
	$longitude = $localisation['longitude'];
?>
<script type="text/javascript">
	// Centré sur la géolocalisation IP
	var map = L.map('map').setView([<?php echo $latitude; ?>,<?php echo $longitude; ?>], 12);
	
	//Map en couleur sur un projet créé
	L.tileLayer('http://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {attribution: '&copy; OpenStreetMap', id: 'e-lico.jhmg919b'}).addTo(map);

	var circle = L.circle([<?php echo $latitude; ?>,<?php echo $longitude; ?>], 1500, {
    	color: '#f80',
    	fillColor: '#f83',
    	fillOpacity: 0.5
	}).addTo(map);
	
</script>

