<script>
import axios from 'axios'
    export default {
        // data() {
            // map: new google.maps.Map(),
            // endpointMarker : []
        // }
        data() {
            return {
                html: '',
                el : new google.maps.InfoWindow({
                    content: this.html,
                    padding:"20px"
                })
            }  
        },
        methods: {
            
            setInfoWindowODP (id){
                let contentString = `<p align='center'>Loading...</p>`
                this.setHtml(contentString)
                this.open(this.map, this.endpointMarker[id])
                axios.post(route('api.presale.endpoint.detail', {endpoint: id}))
                .then((data) =>  {
                    console.log(data)
                    // if(data === null) {
                    //     ODP.removeById(id)
                    //     return;
                    // }
                    let contentString = `<div id='infoWindow${data.end_point_id}'><table class='table-informasi'>
                                        <tr><td><b>Nama</b></td><td>:</td><td>${data.nama_odp}</td></tr>
                                        <tr><td><b>Lat</b></td><td>:</td><td class='latDetail'>${data.lat}</td></tr>
                                        <tr><td><b>Long</b></td><td>:</td><td class='longDetail'>${data.lon}</td></tr>
                                        <tr><td><b>Provinsi</b></td><td>:</td><td>${data.provinsi}</td></tr>
                                        <tr><td><b>Kabupaten</b></td><td>:</td><td>${data.kabupaten}</td></tr>
                                        <tr><td><b>Kecamatan</b></td><td>:</td><td>${data.kecamatan}</td></tr>
                                        <tr><td><b>Kelurahan</b></td><td>:</td><td>${data.kelurahan}</td></tr>
                                    </table><br>
                                    <div class="btn-edit-marker-odp">`;
                    contentString += `<button type="button" onclick="editLokasiMarker(this)" data-id="${data.end_point_id}" data-type="1"  class="btn-action-marker btn btn-primary btn-sm"><i class='fa fa-arrows-alt'></i></button>`;
                    contentString += `<button type="button" onclick="${this.consoleBro()}" class="btn-action-marker btn btn-warning btn-sm"><i class='fa fa-edit'></i></button>`;
                    
                    contentString += `<button type="button" onclick="hapusDataMarker(${data.end_point_id},1)" class="btn-action-marker btn btn-danger btn-sm"><i class='fa fa-trash'></i></button>`;
                    contentString += `<div style="position:relative;display:inline-block">
                                        <button type="button" onclick="showInterkoneksiPresale(${data.end_point_id},1)" class="btn-action-marker btn btn-primary btn-sm"><i class='fa fa-home'></i></button>
                                            <span class="bg-white text-primary span-jumlah-rumah">${data.jumlah}</span>
                                        </div>`;
                    contentString += `</div>
                                        <div class="btn-simpan-edit-odp" style="display:none;width:100%;text-align:center;">
                                        <span style='margin-bottom:5px;'>Geser ODP ke lokasi yang diinginkan.</span><br>
                                        <button type="button" onclick="actionLokasiMarker(this)" data-action="1" data-id="${data.end_point_id}" data-type="1" class="btn btn-primary btn-sm btn-action-marker"><i class='fa fa-check'></i></button>
                                        <button type="button" onclick="actionLokasiMarker(this)" data-action="0" data-id="${data.end_point_id}" data-type="1" class="btn btn-danger btn-sm btn-action-marker"><i class='fa fa-times'></i></button>
                                        </div>
                                    <div>`;


                    this.setHtml(contentString)
                    this.open(this.map, this.endpointMarker[id])
                })
            }
        }
    }
</script>