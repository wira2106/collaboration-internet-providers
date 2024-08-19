<template>
    <div style="width:100%;height:100%">
        <div v-bind:id="idMap" style="width:100%;height:100%">

        </div>
    </div>
</template>


<script>
    import {gmapApi} from 'vue2-google-maps';

    export default {
        props:{
            latitude : {default: null},
            longitude : {default: null},
            id: {required:true},
            idAutoComplete: {required:true}
        },
        data() {
            return {    
                map : null,
                marker: null,
                idMap : '#' + this.id,
                location: undefined,
                autocomplete: null
            }
        },
        compute:{           
          google: gmapApi,
        },    
        methods: {
            initializeMap() {

                this.map = new google.maps.Map(document.getElementById(this.idMap), {
                    center: { lat: parseFloat(this.latitude), lng: parseFloat(this.longitude) },
                    zoom: 8,
                    streetViewControl: false,
                })
                let addressLatLng = new google.maps.LatLng(parseFloat(this.latitude), parseFloat(this.longitude));
                let map = this.map;
                this.marker = new google.maps.Marker({
                    position: addressLatLng,
                    map,
                    draggable:true
                });
                
                this.autocomplete = new google.maps.places.Autocomplete(document.getElementById(this.idAutoComplete));
                
                this.autocomplete.addListener("place_changed", () => {
                    const place = this.autocomplete.getPlace();
                    let addressLatLng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng())
                    this.marker.setPosition(addressLatLng)
                    this.map.setCenter(addressLatLng)
                    this.map.setZoom(16);
                    this.location = place.geometry.location;
                    this.emitEventChange();

                });
                this.marker.addListener('dragend', e => {
                    this.location = e.latLng;
                    this.emitEventChange();
                })
            },
            emitEventChange() {
                this.fillAddress(this.location.lat(), this.location.lng())
                this.$emit("change", this.location)
            },
            changePositionCenterMap(lat,lng, zoom=16) {
                this.map.setZoom(zoom);
                let addressLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng))
                this.map.setCenter(addressLatLng)
            },
            changeMarkerPosition(lat,lng) {
                let addressLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng))
                this.marker.setPosition(addressLatLng)
            },
            fillAddress(latitude,longitude) {
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latlng }, (results, status) => {
                    if (status == google.maps.GeocoderStatus.OK) {                   
                        if (results[0]) {
                            this.location.address = results[0].formatted_address
                            this.location.postal_code = results[0].address_components.find(i => i.types[0] == "postal_code")
                            
                            this.$emit("change", this.location)                     
                        }
                    }
                });
            }
        },
        mounted() {
            let self = this;
            this.$gmapApiPromiseLazy().then(() => {
                self.initializeMap();                
            });
        },
        watch: {
            latitude: function (val) {
                if (this.map != null) {
                    this.changePositionCenterMap(this.latitude, this.longitude)
                    this.changeMarkerPosition(this.latitude, this.longitude)                    
                }
            }
        }
    }
</script>