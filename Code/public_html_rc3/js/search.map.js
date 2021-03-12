

var myCenter = new google.maps.LatLng(36.9753149,-122.0258553);  // this is the starting location
var marker;
var heatmapData = [];  // this is only used by Google Maps but don't remove from THIS file!
/*
var heatmapData = [
	new google.maps.LatLng(38.90392,-119.995024)
];
*/

function loadGoogleMap(){
	console.log("KICKED: initGoogleMap()");
        var mapProp = {
                center:myCenter,
                zoom:4,
                mapTypeId:google.maps.MapTypeId.ROADMAP,
        };
	var map = new google.maps.Map(window.document.getElementById('googleMap'),mapProp); 

        marker=new google.maps.Marker({
                position:myCenter,
//              animation:google.maps.Animation.BOUNCE
        });

        //marker.setMap(map);
	
	// add Heatmap component
	var heatmap = new google.maps.visualization.HeatmapLayer({
		opacity: .9,
		radius: 12,
		data: heatmapData
	});
	heatmap.setMap(map);
}

