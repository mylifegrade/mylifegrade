(function() {
    var app = angular.module('goals', [ ]);

    /*
     * CATEGORIES
     */
    app.controller('CategoryController', ['$http', function($http) {
        // Init
        this.selectedCategory = { CategoryID: -1 };
        this.categoryToAdd = { CategoryWeight: 3, };
        this.keyIndicatorToAdd = { KeyIndicatorWeight: 3, PointsPossible: 1 };
        
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
        
        // Functions
        this.addCategory = function() {
            // Temporary callback variables
            var thisCtrl = this;
            
            // Issue the post
            $http.post('../api/categories.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&prettyprint=true', thisCtrl.categoryToAdd)
            .success(function(data) {
               location.reload();
            })
            .error(function(data) {
               alert("ERROR:" + JSON.stringify(data));
            });
        };
        
        // Functions
        this.addKeyIndicator = function(categoryID) {
            
            // Assign the category
            this.keyIndicatorToAdd.CategoryID = categoryID;
            
            // Temporary callback variables
            var thisCtrl = this;
            
            // Issue the post
            $http.post('../api/key_indicators.php?apiKey=53288076-3666-48e6-a978-27a6c5962ad3&prettyprint=true', thisCtrl.keyIndicatorToAdd)
            .success(function(data) {
               location.reload();
            })
            .error(function(data) {
               alert("ERROR:" + JSON.stringify(data));
            });
        };
    }]);
    
    app.directive('goals', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/goals.html'
        }
    });
    
    app.directive('addCategory', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/add_category.html'
        }
    });
    
    /*
     * KEY INDICATORS
     */
    app.controller('KeyIndicatorController', ['$http', function($http) {
        
    }]);
    
    app.directive('addKeyIndicator', function() {
        return {
            restrict: 'E',
            templateUrl: 'Partials/add_key_indicator.html'
        }
    });
})();