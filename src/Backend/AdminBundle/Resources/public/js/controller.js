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

        console.log($scope.users);

    }, function errorCallback(response) {
        console.log("hellO");
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

        console.log($scope.projects);

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

    $scope.actualProjectId = 0;
    $scope.projectDetailInfo = [];

    $scope.redirectToProjekt = function(id, projectTitle) {
        $scope.actualProjectId = id;
        window.location.replace('./projekt/'+projectTitle);
    }

    // Get the Projekt Information to a concrete Projekt
    $scope.initProjektDetail = function(){
        $http({
            method: 'GET',
            url: '/project/'+$scope.actualProjectId
        }).then(function successCallback(response) {

            $scope.projectDetailInfo = response.data;

            console.log("Here-----");
            console.log($scope.actualProjectId);
            console.log(projectDetailInfo);
            console.log("Here-----");

        }, function errorCallback(response) {

        });
    }

});