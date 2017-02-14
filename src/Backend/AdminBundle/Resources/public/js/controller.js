var app = angular.module('BackendApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('backendController', function($scope, $http) {
    
    $http({
        method: 'GET',
        url: '/backend/users'
    }).then(function successCallback(response) {

        $scope.users = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.users.push(response.data[u]);
        }

        //console.log($scope.users);

    }, function errorCallback(response) {

    });

    $http({
        method: 'GET',
        url: '/projects'
    }).then(function successCallback(response) {

        $scope.projects = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.projects.push(response.data[u]);
        }

        console.log($scope.projects);

    }, function errorCallback(response) {

    });

    $http({
        method: 'GET',
        url: '/categories'
    }).then(function successCallback(response) {

        $scope.categorys = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.categorys.push(response.data[u]);
        }

        console.log($scope.categorys);

    }, function errorCallback(response) {

    });
});