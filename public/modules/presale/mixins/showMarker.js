export default {
    methods: {
        showMarkerEndpoint(data, map) {
            let {latitude, longitude} = data;
            let latlng = new google.maps.LatLng(latitude, longitude);
            let icon = {
                url : window.location.origin + '/modules/presale/img/odp-marker.ico',
                scaledSize: new google.maps.Size(50, 60)
            }
            let newMarker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            icon: icon,
                            });

            google.maps.event.addListener(newMarker, 'click',  (event) => {
                // batalTambahMarker();
                this.map.setCenter(latlng);
                // setFocusOut();
                this.setInfoWindowODP(data.end_point_id)
            });
            return newMarker;
        },
        setMapOnAllEndpoint(marker, map) {
            Object.entries(marker).forEach(([key, marker]) => {
                marker.setMap(map)
            })
        }
    }
}