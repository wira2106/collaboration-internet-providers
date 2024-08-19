export default {
    methods: {
        getHtmlInfoWindow(endpoint, index) {
            let html = '';
            html += `
                <div >
                    <table class='table-informasi'>
                        <tr><td><b>Nama</b></td><td>:</td><td>${endpoint.nama_end_point}</td></tr>
                        <tr><td><b>Lat</b></td><td>:</td><td>${endpoint.position.lat}</td></tr>
                        <tr><td><b>Long</b></td><td>:</td><td>${endpoint.position.lng}</td></tr>
                    </table>
                    <br>
            `
            html  += "<div class='btn-edit-marker-odp d-flex justify-content-center'>";
            html  += `<button type='button' onclick='editLokasiMarker(${index})'  class='btn-action-marker btn btn-primary btn-sm'><i class='fa fa-arrows-alt'></i></button>`;

            html  += `<button type='button' onclick='editDataMarker(${index})' class='btn-action-marker btn btn-warning btn-sm'><i class='fa fa-edit'></i></button>`;

            html  += `<button type='button' onclick='hapusDataMarker(${index})' class='btn-action-marker btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>`;

            html  += "</div>";
            // End Button
            html  += "</div>";

            return html;
        }
    }
}