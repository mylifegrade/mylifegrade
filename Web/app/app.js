(function() {
    var app = angular.module('myLifeGrade', []); 
    
    app.controller('HomeController', function() {
        // Init
        this.activeTabName = 'tabGoals';
        
        // Tabs
        this.setActiveTab = function(tabName) {
            this.activeTabName = tabName;
        };
        this.isActiveTab = function(tabName) {
            return this.activeTabName === tabName;
        };
        
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
    
    app.controller('UserController', [ '$http', function($http) {
        // Init
        this.user = { };
        
        // Data collection from API
        var thisCtrl = this;
        $http.get('../api/current_user.php?apiKey=94e21cbe-92f2-11e4-86fb-02d737fe62fd&prettyprint=true')
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
            $http.post('../api/categories.php?apiKey=94e21cbe-92f2-11e4-86fb-02d737fe62fd&prettyprint=true', thisCtrl.category)
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
            // TODO: Do stuff
        };
    }]);
        
})();