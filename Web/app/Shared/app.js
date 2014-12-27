(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       var tmp = this;
       tmp.users = [];
       $http.get('../../api/users/get.php').success(function(data) {
           tmp.users = data;
       });
    }]);
})();