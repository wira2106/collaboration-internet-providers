var pageTotal = 0;
var pageAktif = 0;
var pageCount = 0;
var jumlahSeluruh = 0;
var indexContent = 0;

$(document).ready(function(){
    loadListCompany();
});

function setPaging(show,between){
      // show : itu buat nampilin di awal atau akhirnya mau tampil berapa
      // contoh : show = 5 ( 1,2,3,4,5,...,20 ) atau (1,...,16,17,18,19,20)
      // between : untuk ngasik tiap kiri dan kanan berapa index kalau ditengah
      // contoh : between = 2 (1,...,6,7,[8],9,10,...,20)
      between = parseInt(between+1);
      var txt = "";
      txt += `<div class="btn-group">`;
      txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pervPage();'><i class='fa fa-chevron-left'></i></button>";
      if (pageAktif<(parseInt(show)-1) || pageAktif == (parseInt(show)-1) && pageTotal<=pageAktif) {
        for (var i = 0; i < (pageTotal); i++) {
            if (i<parseInt(show)) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
        if (pageTotal>parseInt(show)) {
          txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
          txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti("+(pageTotal-1)+")'>"+(pageTotal)+"</button>";
        }
      }else if(pageAktif - ((pageTotal)-parseInt(show))>0){
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i>((pageTotal-1)-parseInt(show))) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
      }else{
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i>(pageAktif-between) && i<(pageAktif+between)) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti("+(pageTotal-1)+")'>"+(pageTotal)+"</button>";
      }

      txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='nextPage();'><i class='fa fa-chevron-right'></i></button>";
      txt += `</div>`;
      txt = txt+"<select class='select2 jumpPage form-control form-control-sm' onchange='changePage(this)' style='width:60px;'>";
      for(var j = 0;j<pageTotal;j++){
        var selectedPage = '';
        if (j == pageAktif) {
          selectedPage = 'selected';
        }
        txt = txt+"<option value='"+j+"' "+selectedPage+">"+(j+1)+"</option>";
      }
      txt = txt+"</select>";
      $('#btnPaging1').html(txt);
      $('#btnPaging2').html(txt);
      $('.select2').select2();
    }

    $(document).keypress(function(e) {
        if(e.which == 13) {
          // $('#buttonSearchnya').focus();
          pageCount = 0;
          pageAktif = 0;
          loadListCompany();
        }
    });
    function pageGanti(index){
      pageAktif = index;
      pageCount = index;
      loadListCompany(index);
    }

    function changePage(e){
        pageGanti(parseInt($(e).val()));
    }


    function pervPage(){
    if (pageCount != 0) {
      pageAktif--;
      pageCount = pageCount-1;
      loadListCompany();
    }
  }

  function nextPage(){
    if (indexContent != jumlahSeluruh) {
      pageAktif++;
      pageCount = pageCount+1;
      loadListCompany();
    }
  }
  function pageIndex(index){
      pageAktif = index;
      pageCount = index;
      loadListCompany();
    }
  function cariCompany(){
	 	pageAktif = 0;
	  pageCount = 0;
	  loadListCompany();
 }

  function pageShow(index){
	  pageAktif = index;
	  pageCount = index;
	  loadListCompany();
	}

// staff
  function loadListCompany(){
    $('.tableCompany').loading('toggle');
    var txt ="";
    page      = pageCount;
    var no    = (parseInt(page)*20)+1;
    var awal  = no;
    var cari = $('#cariCompany').val();
    var role = $('#roleCompany').val();
    const paramsListCompany = {
        page:page,
        cari:cari,
        role:role
    }
    var endpointJsonListCompany = route('admin.company.company.jsonListCompany', paramsListCompany).url();
    $.getJSON(endpointJsonListCompany,function(data){
    // console.log(data)
      jumlahSeluruh = data[0];
      pageTotal = parseInt(Math.ceil(jumlahSeluruh/20));
      $.each(data[1], function(key, val){
        
        txt += `<tr>
                  <td align="left"><img style="width:50px;height:50px" src="${val.logo_perusahaan}" onerror="this.src=window.location.origin + '/uploadgambar/nologo.png'"></td>
                  <td align="left"><a href="${route('admin.company.company.edit', {company: val.company_id})}">${val.name}</a></td>
                  <td align="left">${val.type}</td>
                  <td align="left">${val.rating ? val.rating : '-'}</td>
                  <td align="left">${val.created_at}</td>
                  <td align="left">
                    ${val.urls.edit_url ? 
                        `<a href="${val.urls.edit_url}" class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i></a>`
                        : ""
                    }
                    ${val.urls.delete_url ? 
                        `<a href="#" onclick="deleteCompany('${val.urls.delete_url}')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>`
                        : ""
                    }
                  </td>
                  
                </tr>
                `;
        indexContent = no;
        no++;
      });
    }).done(function(){
      $('#displayPage').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#displayPage2').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#tbodyListCompany').html(txt);
      $('.tableCompany').loading('toggle');
      // $('[data-toggle="tooltip"]').tooltip();
      setPaging(5,2);
      if (jumlahSeluruh == 0) {
        $('#tbodyListCompany').html("<td colspan='6'><center>Data tidak ditemukan</center></td>");
      }
    });
  }

  deleteCompany = (link) =>{
     Swal.fire({
        title: 'Apa anda Yakin?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.value) {
            Swal.fire({
              title: 'Loading..',
              html: '',
              allowOutsideClick: false,
              onOpen: () => {
                swal.showLoading()
              }
            });
            axios.delete(link)
            .then( function(data){
                pageCount = 0;
                pageAktif = 0;
                 Swal.fire(
                   'Proses berhasil',
                   '',
                   'success'
                 )
                loadListCompany();
             });
          
        }
      });
  }
