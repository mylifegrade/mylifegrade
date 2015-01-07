(function() {
    var app = angular.module('myLifeGrade', [ 'goals' ]); 
    
    app.controller('HomeController', function() {
        // Serialization
        this.serialize = function(obj, prettyPrint, splitIntoLines) {
            if (prettyPrint) {
                var json = JSON.stringify(obj, undefined, 4);
                if (splitIntoLines) {
                    json = json.match(/[^\r\n]+/g);
                }
                return json;
            }
            return JSON.stringify(obj);
        };
    });
    
    app.controller('NavController', function() {
        // Init
        this.activeTabName = 'tabGoals';
        this.activeTitle = 'Goals';
        
        // Tabs
        this.setActiveTab = function(tabName, title) {
            this.activeTabName = tabName;
            this.activeTitle = title;
        };
        this.isActiveTab = function(tabName) {
            return this.activeTabName === tabName;
        };
    });
    
    app.controller('UserController', [ '$http', function($http) {
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
    
    app.directive('topNavbar', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/top_navbar.html',
        };
    });
    
    app.directive('sidebarLinks', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/sidebar_links.html'
        };
    });
        
})();