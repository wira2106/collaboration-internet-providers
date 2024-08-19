import Vue from 'vue';
Vue.filter('harga', function(data){
    
    if(data == null) return "Rp. 0";
    
    return 'Rp. ' + data.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
});

Vue.filter("role_name", function(role_name) {
  if(role_name === 'Admin ISP' || role_name === 'Admin OSP') return 'Admin';

  return role_name;
});

Vue.filter("table", function(data) {

  return data ? data : '-';
});