(function() {
    var app = angular.module('categories', [ ]);

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
    
    app.directive('categories', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/categories.html'
        }
    });
    
    app.directive('addCategory', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/add_category.html'
        }
    });
})();