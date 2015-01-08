(function() {
    var app = angular.module('categories', [ 'key_indicators' ]);

    app.controller('CategoryController', ['$http', function($http) {
        // Init
        this.selectedCategory = { CategoryID: -1 };
        this.categoryToAdd = { CategoryWeight: 3 };
        
        this.isSelectedOrDefault = function(category) {
            if (this.selectedCategory.CategoryID == -1)
            {
                this.selectedCategory = category;
            }
            return this.isSelectedCategory(category.CategoryID);
        };
        this.isSelectedCategory = function(categoryID) {
            return this.selectedCategory.CategoryID == categoryID;
        };
        this.setCurrentCategory = function(category) {
            this.selectedCategory = category;
        };
        
        // Stuff
        this.addCategory = function(user) {
            // Temporary callback variables
            var thisCtrl = this;
            var thisUser = user;
            
            // Issue the post
            $http.post('../api/categories.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&prettyprint=true', thisCtrl.categoryToAdd)
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