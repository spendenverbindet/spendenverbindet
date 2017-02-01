var app = angular.module('BackendApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('backendController', function($scope) {

    $scope.hello = "Hello World from Backend";
    
    $http({
        method: 'GET',
        url: '/users'
    }).then(function successCallback(response) {

        $scope.users = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.users.push(response.data[u]);
        }

        console.log($scope.users);

    }, function errorCallback(response) {

    });

});