(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('HomeController', [ '$http', function($http) {
       
       // Tabs
       this.activeTabName = 'tabOverview';
       this.setActiveTab = function(tabName) {
           this.activeTabName = tabName;
       };
       this.isActiveTab = function(tabName) {
           return this.activeTabName === tabName;
       };
       
       // Data collection from API
       this.user = { };
       var tmp = this;
       $http.get('../api/current_user.php?apiKey=329f3e41-8f9d-11e4-86fb-02d737fe62fd&prettyprint=true')
       .success(function(data) {
           tmp.user = data;
       })
       .error(function(data){
           tmp.error = JSON.stringify(data);
       });
    }]);
})();