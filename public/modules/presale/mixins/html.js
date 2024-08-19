export default {
    methods: {
        getHtmlAddPresale(lat,lng) {
            return `
            <table class='table-informasi'>
                <tr><td><b>Lat</b></td><td>:</td><td>${lat}</td></tr>
                <tr><td><b>Lng</b></td><td>:</td><td>${lng}</td></tr>
            </table>
            <div class='d-flex justify-content-center mt-2'>
                <button type='button' class='btn-action-marker btn btn-danger btn-sm' onclick="batalTambahPresale()"><i class='fa fa-times' ></i></button>
            </div>
            `;
        },
        getHtmlEditPresale(lat, lng) {
            return `
            <table class='table-informasi'>
                <tr><td><b>Lat</b></td><td>:</td><td>${lat}</td></tr>
                <tr><td><b>Lng</b></td><td>:</td><td>${lng}</td></tr>
            </table>
            <div class='d-flex justify-content-center mt-2'>
                <button type='button' class='btn-action-marker btn btn-primary btn-sm' onclick="saveLocationPresale()"><i class='fa fa-save'></i></button>
                <button type='button' class='btn-action-marker btn btn-danger btn-sm' onclick="batalEditPresale()"><i class='fa fa-times' ></i></button>
            </div>
            `;
        }
    }
}