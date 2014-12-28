(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       var tmp = this;
       tmp.users = [];
       $http.get('../../api/current_user.php?apiKey=6ea70c49-8ed4-11e4-86fb-02d737fe62fd')
       .success(function(data) {
           tmp.users = data;
       })
       .error(function(data){
           tmp.error = data;
       });
    }]);
})();