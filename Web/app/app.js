(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       var tmp = this;
       tmp.user = {};
       
       var url = '../api/current_user.php?apiKey=329f3e41-8f9d-11e4-86fb-02d737fe62fd&prettyprint=true';
       $http.get(url)
       .success(function(data) {
           tmp.user = data;
       })
       .error(function(data){
           tmp.error = 'Problem requesting URL ' + url + ': ' + JSON.stringify(data);
       });
    }]);
})();