(function() {
    var app = angular.module('myLifeGrade', [ 'users' ]); 
    
    app.controller('HomeController', function() {
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