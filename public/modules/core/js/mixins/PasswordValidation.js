export default {
    methods: {
      validatePassword(value,formName,callback,required=true){
        if (typeof value !== 'undefined'){
          let checkUppercase = this.checkUppercase(value);
          let checkNumber = this.checkNumber(value);
          if((value === '' || value == null) && required) {
            return callback(new Error(this.trans('core.validation.password required')))
          }else if(value.length > 0 && value.length < 8){
            return callback(new Error(this.trans('core.validation.password length')))
          }else if(value.length > 0 && checkUppercase){
            return callback(new Error(this.trans('core.validation.password word')))
          }else if(value.length > 0 && checkNumber){
            return callback(new Error(this.trans('core.validation.password number')))
          }
        }
        this.$refs[formName].validateField('admin.check_pass');
        return callback();
      },
      checkUppercase(value){
        var i=0;
        var character='';
        while (i < value.length){
          character = value.charAt(i);
          if (character == character.toUpperCase() && isNaN(character * 1)) {
            return false;
          }
          i++;
        }
        return true;                 
      },
      checkNumber(value){
        var i=0;
        var character='';
        while (i < value.length){
           character = value.charAt(i);
            if (!isNaN(character * 1)){
                return false;
            }
            i++;
        }
        return true;
      }
    }
}