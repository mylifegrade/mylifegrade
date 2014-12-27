(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       var tmp = this;
       tmp.users = [];
       $http.get('../../api/users.php?userID=1')
       .success(function(data) {
           tmp.users = data;
       })
       .error(function(data){
           tmp.error = data;
       });
    }]);
})();