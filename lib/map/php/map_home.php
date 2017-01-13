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

	// Décalage à gauche, que le centre ne soit pas caché par les formulaires.
	$longitude += 0.6; 
?>
<script type="text/javascript">
	// Centré sur la géolocalisation IP
	var map = L.map('map').setView([<?php echo $latitude; ?>,<?php echo $longitude; ?>], 10);
	
	//Map en couleur sur un projet créé
	L.tileLayer('http://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {attribution: '&copy; OpenStreetMap', id: 'e-lico.jhmg919b'}).addTo(map);
	
	// Carte en Noir et Blanc
	//id: 'examples.map-20v6611k'
</script>

