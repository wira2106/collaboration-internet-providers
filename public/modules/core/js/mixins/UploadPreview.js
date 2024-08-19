export default {
  methods:{
    checkFile(file){
          var fileSize = this.byteToMB(file.size)
          if(fileSize <= 50) {
            return true;
          } 
          return false;
      },
      byteToMB(byte){
        return byte / 1024 / 1024;
      },
      setInputFoto(event,target_preview,target_input){
        let _ = require("lodash");
        let files = event.target.files;
        let vm = this;
        if (files && files[0]) {     
            var cekFile = this.checkFile(files[0]);
            if (cekFile) {
              var type = files[0].type;
              var type_reg = /^image\/(jpg|png|jpeg)$/;
              if (!(type_reg.test(type))) {
                    Swal.fire(
                      'Format gambar tidak valid!',
                      '(Masukkan gambar dengan tipe : .jpg,.jpeg atau .png)',
                      'warning'
                    );
                _.set(this,target_input,null);                    
                _.set(this,target_preview,null);                    
              }else{
                 Swal.fire({
                  title: 'Sedang memproses gambar..',
                  html: '',
                  allowOutsideClick: false,
                  onOpen: () => {
                    swal.showLoading()
                  }
                });
                var reader = new FileReader();
                reader.onload = function (readerEvent) {
                      var image = new Image();
                      image.onload = function (imageEvent) {

                          // Resize the image
                          var canvas = document.createElement('canvas'),
                              max_size = 544,// TODO : pull max size from a site config
                              width = image.width,
                              height = image.height;
                          if (width > height) {
                              if (width > max_size) {
                                  height *= max_size / width;
                                  width = max_size;
                              }
                          } else {
                              if (height > max_size) {
                                  width *= max_size / height;
                                  height = max_size;
                              }
                          }
                          canvas.width = width;
                          canvas.height = height;
                          canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                          var dataUrl = canvas.toDataURL('image/jpeg');
                          var resizedImage = vm.dataURLToBlob(dataUrl);
                           _.set(vm,target_input,null);                              
                           _.set(vm,target_input,resizedImage);                              
                           _.set(vm,target_preview,dataUrl);
                          swal.close();
                      }
                      image.src = readerEvent.target.result;
                  }
                reader.readAsDataURL(files[0]);        
              }
            }else{
              _.set(this,target_input,null);                    
              _.set(this,target_preview,null);    
              Swal.fire("Maksimal upload 50Mb!","","warning");
            }
          }else{
            _.set(this,target_input,null);                    
            _.set(this,target_preview,null);                   
          }
        $(".fancybox").fancybox();
      },
      dataURLToBlob(dataURL) {
          var BASE64_MARKER = ';base64,';
          if (dataURL.indexOf(BASE64_MARKER) == -1) {
              var parts = dataURL.split(',');
              var contentType = parts[0].split(':')[1];
              var raw = parts[1];

              return new Blob([raw], {type: contentType});
          }

          var parts = dataURL.split(BASE64_MARKER);
          var contentType = parts[0].split(':')[1];
          var raw = window.atob(parts[1]);
          var rawLength = raw.length;

          var uInt8Array = new Uint8Array(rawLength);

          for (var i = 0; i < rawLength; ++i) {
              uInt8Array[i] = raw.charCodeAt(i);
          }

          return new Blob([uInt8Array], {type: contentType});
      },
  }
}