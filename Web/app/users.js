(function() {
    var app = angular.module('users', [ 'goals' ]); 
    
    app.controller('CurrentUserController', [ '$http', function($http) {
        // Init
        this.user = { };
        
        // Data collection from API
        var thisCtrl = this;
        $http.get('../api/current_user.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&prettyprint=true')
        .success(function(data) {
           thisCtrl.user = data;
        })
        .error(function(data) {
           alert("ERROR:" + JSON.stringify(data));
        });
    }]);
})();