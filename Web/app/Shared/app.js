(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       var tmp = this;
       tmp.currentUser = {};
       $http.get('../../api/current_user.php?apiKey=329f3e41-8f9d-11e4-86fb-02d737fe62fd&prettyprint=true')
       .success(function(data) {
           tmp.currentUser = data;
       })
       .error(function(data){
           tmp.error = data;
       });
    }]);
})();