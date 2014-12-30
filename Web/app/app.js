(function() {
    var app = angular.module('myLifeGrade', []); 
    app.controller('UserController', [ '$http', function($http) {
       
        // Init
        this.user = { };
        this.activeTabName = 'tabOverview';
        
        // Tabs
        this.setActiveTab = function(tabName) {
            this.activeTabName = tabName;
        };
        this.isActiveTab = function(tabName) {
            return this.activeTabName === tabName;
        };
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
        
            // Data collection from API
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