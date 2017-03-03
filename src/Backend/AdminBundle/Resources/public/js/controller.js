var app = angular.module('BackendApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('backendController', function($scope, $http) {

    //getting all Users
    $scope.initUsers = function() {
        $http({
            method: 'GET',
            url: '/backend/users'
        }).then(function successCallback(response) {

            $scope.users = [];

            for (var u = 0; u < Object.keys(response.data).length; u++) {
                $scope.users.push(response.data[u]);
            }

        }, function errorCallback(response) {

        });
    }

    //getting all Projects
    $scope.initProjects = function() {
        $http({
            method: 'GET',
            url: '/backend/allprojects'
        }).then(function successCallback(response) {

            $scope.projects = [];

            for (var u = 0; u < Object.keys(response.data).length; u++) {
                $scope.projects.push(response.data[u]);
            }

        }, function errorCallback(response) {

        });
    }

    //getting all categories
    $scope.initCategories = function() {
        $http({
            method: 'GET',
            url: '/categories'
        }).then(function successCallback(response) {

            $scope.categorys = [];

            for (var u = 0; u < Object.keys(response.data).length; u++) {
                $scope.categorys.push(response.data[u]);
            }

        }, function errorCallback(response) {

        });
    }

    // Projektdetailseite
    $scope.actualProjectId = null;
    $scope.projectDetailInfo = [];

    $scope.redirectToProjekt = function(id) {
        $scope.actualProjectId = id;
        window.location.replace('./project/'+id);
    }

    $scope.redirectToProjectFromFollower = function(id) {
        $scope.actualProjectId = id;
        window.location.replace('../'+$scope.actualProjectId);
    }

    $scope.redirectToProjectFromUser = function(id) {
        $scope.actualProjectId = id;
        window.location.replace('../../project/'+$scope.actualProjectId);
    }

    // Get the Projekt Information to a concrete Projekt
    $scope.initProjektDetail = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/backend/projects/'+$scope.actualProjectId
        }).then(function successCallback(response) {

            $scope.projectDetailInfo = response.data[0];

            document.getElementById("activated").checked = response.data[0].active;

        }, function errorCallback(response) {

        });
    }

    // Userdetailseite
    $scope.actualUserId = null;
    $scope.userDetailInfo = [];

    $scope.redirectToUser = function(id) {
        $scope.actualUserId = id;
        window.location.replace('./user/'+id);
    }

    $scope.redirectToUserFromUserProjects = function(id) {
        $scope.actualUserId = id;
        window.location.replace('../'+$scope.actualUserId);
    }

    $scope.redirectToUserFromFollower = function(id) {
        $scope.actualUserId = id;
        window.location.replace('../../user/'+$scope.actualUserId);
    }

    // Get the User Information to a concrete User
    $scope.initUserDetail = function(){

        // Aus der Url die userId holen
        var resArray = document.URL.split("/");
        $scope.actualUserId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/users/'+$scope.actualUserId
        }).then(function successCallback(response) {
            
            $scope.userDetailInfo = response.data[0];

        }, function errorCallback(response) {

        });
    }

    // Userprojekteseite
    $scope.actualUserId = null;
    $scope.userProjekte = [];

    $scope.redirectToUserProjekte = function(id) {
        $scope.actualUserId = id;
        window.location.replace('./projects/'+$scope.actualUserId);
    }

    // Get the User Information to a concrete User
    $scope.initUserProjects = function(){

        // Aus der Url die userId holen
        var resArray = document.URL.split("/");
        $scope.actualUserId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/userProjects/'+$scope.actualUserId
        }).then(function successCallback(response) {

            for (var u = 0; u < Object.keys(response.data).length; u++) {
                $scope.userProjekte.push(response.data[u]);
            }

        }, function errorCallback(response) {

        });
    }


    // Followerprojekteseite
    $scope.actualProjectId = null;
    $scope.projectFollowers = [];

    $scope.redirectToProjectFollowers = function(id) {
        $scope.actualProjectId = id;
        window.location.replace('./followers/'+id);
    }

    // Get the Projekt Information to a concrete Projekt
    $scope.initProjectFollowers = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/followers/'+$scope.actualProjectId
        }).then(function successCallback(response) {

            $scope.projectFollowers = response.data;

        }, function errorCallback(response) {

        });
    }


});