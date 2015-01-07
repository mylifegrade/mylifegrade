(function() {
    var app = angular.module('myLifeGrade', []); 
    
    app.directive('sidebarLinks', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/sidebar_links.html'
        };
    });
    
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
    
    app.controller('CategoryController', ['$http', function($http) {
        // Init
        this.category = { CategoryWeight: 3 };
        
        // Stuff
        this.addCategory = function(user) {
            // Temporary callback variables
            var thisCtrl = this;
            var thisUser = user;
            
            // Issue the post
            $http.post('../api/categories.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&prettyprint=true', thisCtrl.category)
            .success(function(data) {
               thisUser.Categories = data.Categories;
            })
            .error(function(data) {
               alert("ERROR:" + JSON.stringify(data));
            });
        };
    }]);
    
    app.controller('KeyIndicatorController', ['$http', function($http) {
        // Init
        this.keyIndicator = { KeyIndicatorWeight: 3 };
        
        // Stuff
        this.addKeyIndicator = function (category) {
            // Temporary callback variables
            var thisCtrl = this;
            var thisCategory = category;
            
            // Issue the post
            $http.post('../api/key_indicators.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&categoryID=' + thisCategory.CategoryID + '&prettyprint=true', thisCtrl.keyIndicator)
            .success(function(data) {
               thisCategory.KeyIndicators = data.KeyIndicators;
            })
            .error(function(data) {
               alert("ERROR:" + JSON.stringify(data));
            });
        };
    }]);
        
})();