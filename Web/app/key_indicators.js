(function() {
    var app = angular.module('key_indicators', [ ]);
    
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