var pageTotal = 0;
var pageAktif = 0;
var jumlahSeluruh = 0;
var indexContent = 0;
let cari = "";
$(document).ready(function () {
    $(document).keypress(function(e) {
        if(e.which == 13) {
          // $('#buttonSearchnya').focus();
          pageCount = 0;
          pageAktif = 0;
          cari = $('#search').val();
          view();
        }
    });
    view();
});


function setPaging(show, between) {
    // show : itu buat nampilin di awal atau akhirnya mau view berapa
    // contoh : show = 5 ( 1,2,3,4,5,...,20 ) atau (1,...,16,17,18,19,20)
    // between : untuk ngasik tiap kiri dan kanan berapa index kalau ditengah
    // contoh : between = 2 (1,...,6,7,[8],9,10,...,20)
    between = parseInt(between + 1);
    var txt = "";
    txt += `<div class="btn-group">`;
    txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='pervPage();'><i class='fa fa-chevron-left'></i></button>";
    if (pageAktif < (parseInt(show) - 1) || pageAktif == (parseInt(show) - 1) && pageTotal <= pageAktif) {
        for (var i = 0; i < (pageTotal); i++) {
            if (i < parseInt(show)) {
                txt = txt + "<button type='button' class='btn ";
                if (i == pageAktif) {
                    txt = txt + "btn-info ";
                } else {
                    txt = txt + "btn-secondary ";
                }
                txt = txt + "btn-sm' onclick='pageGanti(" + i + ")'>" + (i + 1) + "</button>";
            }
        }
        if (pageTotal > parseInt(show)) {
            txt = txt + "<button type='button' class='btn btn-secondary btn-sm'>...</button>";
            txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(" + (pageTotal - 1) + ")'>" + (pageTotal) + "</button>";
        }
    } else if (pageAktif - ((pageTotal) - parseInt(show)) > 0) {
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i > ((pageTotal - 1) - parseInt(show))) {
                txt = txt + "<button type='button' class='btn ";
                if (i == pageAktif) {
                    txt = txt + "btn-info ";
                } else {
                    txt = txt + "btn-secondary ";
                }
                txt = txt + "btn-sm' onclick='pageGanti(" + i + ")'>" + (i + 1) + "</button>";
            }
        }
    } else {
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i > (pageAktif - between) && i < (pageAktif + between)) {
                txt = txt + "<button type='button' class='btn ";
                if (i == pageAktif) {
                    txt = txt + "btn-info ";
                } else {
                    txt = txt + "btn-secondary ";
                }
                txt = txt + "btn-sm' onclick='pageGanti(" + i + ")'>" + (i + 1) + "</button>";
            }
        }
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(" + (pageTotal - 1) + ")'>" + (pageTotal) + "</button>";
    }

    txt = txt + "<button type='button' class='btn btn-secondary btn-sm' onclick='nextPage();'><i class='fa fa-chevron-right'></i></button>";
    txt += `</div>`;
    txt = txt + "<select class='select2 jumpPage form-control form-control-sm' onchange='changePage(this)' style='width:60px;'>";
    for (var j = 0; j < pageTotal; j++) {
        var selectedPage = '';
        if (j == pageAktif) {
            selectedPage = 'selected';
        }
        txt = txt + "<option value='" + j + "' " + selectedPage + ">" + (j + 1) + "</option>";
    }
    txt = txt + "</select>";
    $('#btnPaging1').html(txt);
    $('#btnPaging2').html(txt);
}

function pageGanti(index) {
    pageAktif = index;
    view(index);
}

function changePage(e) {
    pageGanti(parseInt($(e).val()));
}


function pervPage() {
    if (pageAktif != 0) {
        pageAktif--;
        view();
    }
}

function nextPage() {
    if (indexContent != jumlahSeluruh) {
        pageAktif++;
        view();
    }
}

function pageIndex(index) {
    pageAktif = index;
    view();
}

function cariData() {
    pageAktif = 0;
    view();
}

function pageShow(index) {
    pageAktif = index;
    view();
}

view = () => {
    let a = "";
    let created = "-";
    let updated = "-";
    let no = (parseInt(pageAktif) * 10) + 1;
    let awal = no;
    let empty =`
                <tr style="background-color:#ffff;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><h4 class="card-title" style="padding-top:35%;padding-bottom:35%;">Tidak Tersedia</h4></td>
                        <td></td>
                        <td></td>
                        <td>
                        </td>
                </tr>
                `;
    $.getJSON(route('admin.peralatan.alat.view', { page: pageAktif, cari:cari }), function (data) {
        $.each(data[1], function (key, value) {
            jumlahSeluruh = data[0];
            pageTotal = parseInt(Math.ceil(jumlahSeluruh / 10));
            if (value.created_at !== null) created = value.created_at;
            if (value.updated_at !== null) updated = value.updated_at;
            a += `
                <tr>
                        <td>${no}</td>
                        <td>
                            ${value.nama_alat}
                        </td>
                        <td>
                            ${created}
                        </td>
                        <td>
                            ${updated}
                        </td>
                        <td>
                            <button type="button" class="el-button el-button--default el-button--mini"><span><i class="fa fa-edit"></i></span></button>
                            <button type="button" class="el-button el-button--danger el-button--mini"><span><i class="fa fa-trash"></i></span></button>
                        </td>
                </tr>
                `;
            indexContent = no;
            no++;
        });
    }).done(function () {
        $('#displayPage').html((awal) + '-' + indexContent + '/' + jumlahSeluruh);
        $('#displayPage2').html((awal) + '-' + indexContent + '/' + jumlahSeluruh);
        if (a == "" || a == null) {
            $('#tbodyListAlat').html(empty);
        } else {
            $('#tbodyListAlat').html(a);
        }
        setPaging(5, 2);
    });
}