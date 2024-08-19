$('#provinsi').on('select2:select', function (e) {
    var type = $(this).attr('id').replace("provinsi","");

    var val = $('#provinsi'+type).val();
    var text = '';

    //data
    var data = $('#provinsi'+type).select2('data');
    $('input[name="provinsi'+type+'"]').val(data[0].text);

    $.getJSON($('#jsonKabupaten').val()+'?id='+val, function( data ) {

      $.each( data, function( key, val ) {
        text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
      });
      $('#kabupaten'+type).html("<option value='' disabled selected>Pilih Kabupaten</option>");
      $('#kecamatan'+type).html("<option value='' disabled selected>Pilih Kecamatan</option>");
      $('#kelurahan'+type).html("<option value='' disabled selected>Pilih Kelurahan</option>");
      $('#kabupaten'+type).append(text);
      $('.select2').select2();
    });
  });

  $('#kabupaten').on('select2:select', function (e) {
    var type = $(this).attr('id').replace("kabupaten","");
    var val = $('#kabupaten'+type).val();
    var text = '';

    //data
    var data = $('#kabupaten'+type).select2('data');
    $('input[name="kabupaten'+type+'"]').val(data[0].text);

    $.getJSON($('#jsonKecamatan').val()+'?id='+val, function( data ) {

      $.each( data, function( key, val ) {
        text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
      });
      $('#kecamatan'+type).html("<option value='' disabled selected>Pilih Kecamatan</option>");
      $('#kecamatan'+type).append(text);
      $('.select2').select2();
    });
  });

  $('#kecamatan').on('select2:select', function (e) {
    var type = $(this).attr('id').replace("kecamatan","");
    var val = $('#kecamatan'+type).val();
    var text = '';

    //data
    var data = $('#kecamatan'+type).select2('data');
    $('input[name="kecamatan'+type+'"]').val(data[0].text);

        $.getJSON($('#jsonKelurahan').val()+'?id='+val, function( data ) {

        $.each( data, function( key, val ) {
            text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
        });
        $('#kelurahan'+type).html("<option value='' disabled selected>Pilih Kelurahan</option>");
        $('#kelurahan'+type).append(text);
        $('.select2').select2();
        });
    });

    $('#kelurahan').on('select2:select', function (e) {
        var type = $(this).attr('id').replace("kelurahan","");
        //data
        var data = $('#kelurahan'+type).select2('data');
        $('input[name="kelurahan'+type+'"]').val(data[0].text);
    });

    function getOptId(text,type,index="") {    
        let id = '';
        var wilayah;
        if (type == "provinsi") {
        $('#provinsi'+index).find('*').filter(function() {
            if ($(this).text() === text) {
            id = $(this).val();
            }
        });
        }else if(type == "kabupaten"){
        $('#kabupaten'+index).find('*').filter(function() {
            if ($(this).text() === text) {
            id = $(this).val();
            }
        });
        }else if(type == "kecamatan"){
        $('#kecamatan'+index).find('*').filter(function() {
            if ($(this).text() === text) {
            id = $(this).val();
            }
        });
        }else{
        $('#kelurahan'+index).find('*').filter(function() {
            if ($(this).text() === text) {
            id = $(this).val();
            }
        });
        }
        console.log(id)
        return id;
    }
    function setWilayah(provinsi,kabupaten,kecamatan,kelurahan,index=1){
        if (index == 1) {
        index = "";
        }
        $('input[name="provinsi'+index+'"]').val(provinsi);
        $('input[name="kabupaten'+index+'"]').val(kabupaten);
        $('input[name="kecamatan'+index+'"]').val(kecamatan);
        $('input[name="kelurahan'+index+'"]').val(kelurahan);
        $('#provinsi'+index).val(getOptId(provinsi.toUpperCase(),"provinsi",index)).change();
        var val = $('#provinsi'+index).val();
        var text = '';

        $.getJSON($('#jsonKabupaten').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
            text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kabupaten'+index).html("<option value='' disabled selected>Pilih Kabupaten</option>");
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kecamatan</option>");
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kelurahan</option>");
            $('#kabupaten'+index).append(text);
            $('.select2').select2();
        }).then(function(){
        $('#kabupaten'+index).val(getOptId(kabupaten.toUpperCase(),"kabupaten",index)).change();
        var val = $('#kabupaten'+index).val();
        var text = '';
        $.getJSON($('#jsonKecamatan').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
            text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kecamatan</option>");
            $('#kecamatan'+index).append(text);
            $('.select2').select2();
        }).then(function(){
            $('#kecamatan'+index).val(getOptId(kecamatan.toUpperCase(),"kecamatan",index)).change();
            var val = $('#kecamatan'+index).val();
            var text = '';
            $.getJSON($('#jsonKelurahan').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
                text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kelurahan'+index).html("<option value='' disabled selected>Pilih Kelurahan</option>");
            $('#kelurahan'+index).append(text);
            $('.select2').select2();
            }).then(function(){
            $('#kelurahan'+index).val(getOptId(kelurahan.toUpperCase(),"kelurahan",index)).change(); 
            });
        });
        });
    }
    function setWilayahById(provinsi,kabupaten,kecamatan,kelurahan,index=1){
        if (index == 1) {
        index = "";
        }
        
        $('#provinsi'+index).val(provinsi).change();
        var val = $('#provinsi'+index).val();
        var text = '';

        $.getJSON($('#jsonKabupaten').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
            text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kabupaten'+index).html("<option value='' disabled selected>Pilih Kabupaten</option>");
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kecamatan</option>");
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kelurahan</option>");
            $('#kabupaten'+index).append(text);
            $('.select2').select2();
        }).then(function(){
        $('#kabupaten'+index).val(kabupaten).change();
        var val = $('#kabupaten'+index).val();
        var text = '';
        $.getJSON($('#jsonKecamatan').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
            text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kecamatan'+index).html("<option value='' disabled selected>Pilih Kecamatan</option>");
            $('#kecamatan'+index).append(text);
            $('.select2').select2();
        }).then(function(){
            $('#kecamatan'+index).val(kecamatan).change();
            var val = $('#kecamatan'+index).val();
            var text = '';
            $.getJSON($('#jsonKelurahan').val()+'?id='+val, function( data ) {

            $.each( data, function( key, val ) {
                text = text+"<option value='"+val.id+"'>"+val.name+"</option>";
            });
            $('#kelurahan'+index).html("<option value='' disabled selected>Pilih Kelurahan</option>");
            $('#kelurahan'+index).append(text);
            $('.select2').select2();
            }).then(function(){
            $('#kelurahan'+index).val(kelurahan).change(); 
            });
        });
        });
    }