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
        },
        getHtmlDetailPresale(presale, selected_confirmation = false) {

            let classBtnStatusRumah = presale.status_id != 2 ? 'btn-default' : 'btn-primary';
            let functionBtnStatusRumah = '';
            if (this.user_roles.name !== "Admin ISP" && presale.hasAccessPresale) {
                functionBtnStatusRumah = presale.status_id != 2 ? `onclick='setStatusRumah(${presale.presale_id})'` : '';
            }

            let html = `
            <div id='infoWindowRumah${presale.presale_id}'>
                <table class='table-informasi'>
                    <tr>
                        <td colspan='3' style='padding-bottom:5px;'>
                            <button type='button' class='btn ${classBtnStatusRumah} btn-sm' style='width:100%' ${functionBtnStatusRumah}> Status - ${presale.status_name}
                            </button>
                        </td>
                    </tr>  
            `

            let keterangan = presale.keterangan ? `
                <tr>
                    <td valign='top'>
                        <b>Keterangan</b>
                    </td>
                    <td valign='top'>:</td>
                    <td valign='top'>${presale.keterangan}</td>
                </tr>
            ` : '';

            if(presale.hasAccessPresale || this.user_roles.name == 'Admin ISP') {
                html += `
                    <tr>
                        <td valign='top'>
                            <b>Site ID</b>
                        </td>
                        <td valign='top'>:</td>
                        <td valign='top'>
                            <b>${presale.site_id}</b> 
                            <a class='text-primary' href='#!' onclick='copyToClipboard("${presale.site_id}")'>copy</a>
                        </td>
                    </tr>
                    <tr>
                        <td valign='top'>
                            <b>Tipe</b>
                        </td>
                        <td valign='top'>:</td>
                        <td valign='top'>
                            ${presale.class_name} <b>${presale.nama_gang ? `(${presale.nama_gang})` : ''}</b>
                        </td>
                    </tr>
                `
            }

            html += `
                <tr>
                    <td valign='top'>
                        <b>Alamat</b>
                    </td>
                    <td valign='top'>:</td>
                    <td valign='top'>
                        ${presale.address}
                    </td>
                </tr>
                <tr>
                    <td valign='top' width="150px">
                        <b>Jarak ke endpoint</b>
                    </td>
                    <td valign='top'>:</td>
                    <td valign='top'>${presale.panjang_kabel} M</td>
                </tr>
                ${keterangan}
                                    
            </table>
            `

            html += `<br>`
            html += `<div class='btn-edit-marker-odp d-flex justify-content-center'>`
            let userCanEdit = this.hasAccess('presale.presales.edit');

            if(!presale.hasAccessPresale && (this.user_roles.name === "Admin OSP" || this.user_roles.name === "Super Admin")) {
                html += `
                    <button type='button' onclick="addListConfirmation(${presale.presale_id})" class='btn btn-info btn-sm btn-action-marker btn-action-marker-select' style='margin-left:-3.3px;${selected_confirmation ? 'display:none' : ''}'><i class='fa fa-check'></i></button>
                `
                html += `
                        <button type='button' onclick="addListConfirmation(${presale.presale_id})" class='btn btn-danger btn-sm btn-action-marker btn-action-marker-close-selected' style='margin-left:-3.3px;${selected_confirmation ? '' : 'display:none'}'><i class='fa fa-times'></i></button>
                    `
            }

            if(userCanEdit) {
                html += `
                    <button type='button' onclick='editLokasiMarker(this)' data-id='${presale.presale_id}' data-type='2' class='btn btn-primary btn-sm btn-action-marker' style='margin-left:-3.3px;'>
                        <i class='fa fa-arrows-alt'></i>
                    </button>
                `
                html += `
                   <button type='button' onclick='editDataMarker(${presale.presale_id},2)' class='btn-action-marker btn btn-warning btn-sm'>
                        <i class='fa fa-edit'></i>
                   </button> 
                `

                html += `
                    <button type='button' onclick='hapusDataMarker(${presale.presale_id},2)' class='btn-action-marker btn btn-danger btn-sm'>
                        <i class='fa fa-trash'></i>
                    </button>
                `
            }

            if(this.user_roles.name === "Admin ISP") {
                html += `
                    <button type='button' onclick='showRoutes(${presale.presale_id})' class='btn btn-info btn-sm mx-1'>
                        <i class='fa fa-level-up-alt mr-1'></i>  ${ this.trans('presales.jalur kabel')}
                    </button>
                `
                html += `
                    <el-button type='primary' icon='fa fa-shopping-cart' onclick="showFormPelanggan(${presale.presale_id})" class='btn btn-primary btn-sm mx-1' style='margin-left:-3.3px;'>
                        <i class='fa fa-shopping-cart mr-1'> </i>${ this.trans('presales.order') }
                    </el-button>
                `
            } else {
                html += `
                    <button type='button' onclick='showRoutes(${presale.presale_id})' class='btn btn-primary btn-sm btn-action-marker'>
                        <i class='fa fa-level-up-alt'></i>
                    </button>
                `
            }
            
            html += `
                </div>
            `

            return html;
        },
        getHtmlDetailEndpoint(endpoint, selected_confirmation = false) {
            let html = `<div >
                        <table class='table-informasi'>
                            <tr>
                                <td valign="top">
                                    <b>Nama</b>
                                </td>
                                <td valign="top">:</td>
                                <td valign="top">
                                    <b>${endpoint.nama_end_point ? endpoint.nama_end_point : '-'} </b>
                                    <a class='text-primary' href='#!' onclick='copyToClipboard("${endpoint.nama_end_point}")'>copy</a>
                                
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <b>Alamat</b>
                                </td>
                                <td valign="top">:</td>
                                <td valign="top" width="150px"> ${endpoint.address ? endpoint.address : '-'}</td>
                            </tr>
                        </table>
                        <br>`;


            html += `<div class='btn-edit-marker-odp d-flex justify-content-between'>`;

            if(endpoint.settlement_at == null && (this.user_roles.name !== 'Admin ISP')) {
                html  += `<button type='button' onclick='pilihEndpoint(${endpoint.end_point_id})' class='btn-action-marker btn btn-info btn-sm btn-action-marker-select' style="${selected_confirmation ? 'display:none' : ''}"><i class='fa fa-check'></i></button>`;

                html += `
                <button type='button' onclick='pilihEndpoint(${endpoint.end_point_id})' class='btn-action-marker btn btn-danger btn-sm btn-action-marker-close-selected' style="${selected_confirmation ? '' : 'display:none'}"><i class='fa fa-times' ></i></button>
                `
            }
            
            html += `<button type='button' onclick='editLokasiMarker(this)' data-id='endpoint.end_point_id' data-type='1'  class='btn-action-marker btn btn-primary btn-sm'><i class='fa fa-arrows-alt'></i></button>`;

            html  += `<button type='button' onclick='editDataMarker(${endpoint.end_point_id},1)' class='btn-action-marker btn btn-warning btn-sm'><i class='fa fa-edit'></i></button>`;

            html  += `<button type='button' id='btnHapusEndpoint' onclick='hapusDataMarker(${endpoint.end_point_id},1)' class='btn-action-marker btn btn-danger btn-sm'><i class='fa fa-trash'></i></button>`;

            

            html  += `<div style='position:relative;display:inline-block'>
                            <button type='button' onclick='showInterkoneksiPresale(${endpoint.end_point_id},1)' class='btn-action-marker btn btn-primary btn-sm'><i class='fa fa-home'></i></button>
                            <span class='bg-white text-primary span-jumlah-rumah'>${endpoint.total_presale}</span>
                        </div>`;
            
            
            

            html  += `</div>`;
            // End Button
            html  += `</div>`;
            return html
        }
    }
}