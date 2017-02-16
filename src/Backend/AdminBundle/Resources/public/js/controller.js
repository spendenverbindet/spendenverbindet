var app = angular.module('BackendApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('backendController', function($scope, $http) {

    //getting all Users

    $http({
        method: 'GET',
        url: '/backend/users'
    }).then(function successCallback(response) {

        $scope.users = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.users.push(response.data[u]);
        }

    }, function errorCallback(response) {

    });

    //getting all Projects

    $http({
        method: 'GET',
        url: '/backend/allprojects'
    }).then(function successCallback(response) {

        $scope.projects = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.projects.push(response.data[u]);
        }

    }, function errorCallback(response) {

    });

    //getting all categories

    $http({
        method: 'GET',
        url: '/categories'
    }).then(function successCallback(response) {

        $scope.categorys = [];

        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.categorys.push(response.data[u]);
        }

    }, function errorCallback(response) {

    });

    // Projektdetailseite

    $scope.actualProjectId = null;
    $scope.projectDetailInfo = [];

    $scope.redirectToProjekt = function(id) {
        $scope.actualProjectId = id;
        window.location.replace('./project/'+id);
    }

    // Get the Projekt Information to a concrete Projekt
    $scope.initProjektDetail = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/project/'+$scope.actualProjectId
        }).then(function successCallback(response) {

            $scope.projectDetailInfo = response.data[0];

            console.log($scope.projectDetailInfo);

        }, function errorCallback(response) {

        });
    }

});