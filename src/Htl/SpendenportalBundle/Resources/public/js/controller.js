var app = angular.module('SpendenverbindetApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('spendenverbindetController', function($scope, $http) {

    $scope.showLoadingIndicator = false;
    $scope.activeCategorie = "Alle Projekte";
    $scope.allCategories = [];

    // Get all categories in an array
    $http({
        method: 'GET',
        url: '/categories'
    }).then(function successCallback(response) {

        $scope.allCategories = response.data;

    }, function errorCallback(response) {

    });


    $scope.projects = [];
    $scope.projectsPrepared = [];


    // Get all projects of all categories in an array
    $scope.showLoadingIndicator = true;

    $http({
        method: 'GET',
        url: '/projects'
    }).then(function successCallback(response) {

        $scope.showLoadingIndicator = false;
        $scope.projects = [];
        $scope.projectsPrepared = [];


        for(var u=0;u<Object.keys(response.data).length;u++){
            $scope.projects.push(response.data[u]);
        }

        if( $scope.projects.length > 3){

                var itarationValue = ($scope.projects.length / 3) | 0;

                for(var z=0;z<itarationValue;z++){
                    $scope.projectsPrepared.push([$scope.projects[0],$scope.projects[1],$scope.projects[2]]);
                    $scope.projects.splice(0, 3);
                }

                if($scope.projects.length % 3 != 0){
                    $scope.projectsPrepared.push($scope.projects);
                }

        }else{
            $scope.projectsPrepared.push($scope.projects);
        }


        //console.log($scope.projectsPrepared);

    }, function errorCallback(response) {

    });


    // Get all projects to the related categorie id
    $scope.loadProjectsWithId = function(id, categoryName){

        $scope.showLoadingIndicator = true;
        $scope.projects = [];
        $scope.projectsPrepared = [];

        $scope.activeCategorie = categoryName;

        $http({
            method: 'GET',
            url: '/projects/'+id
        }).then(function successCallback(response) {

            $scope.showLoadingIndicator = false;

            for(var u=0;u<Object.keys(response.data).length;u++){
                $scope.projects.push(response.data[u]);
            }

            if( $scope.projects.length > 3){

                var itarationValue = ($scope.projects.length / 3) | 0;

                for(var z=0;z<itarationValue;z++){
                    $scope.projectsPrepared.push([$scope.projects[0],$scope.projects[1],$scope.projects[2]]);
                    $scope.projects.splice(0, 3);
                }

                if($scope.projects.length % 3 != 0){
                    $scope.projectsPrepared.push($scope.projects);
                }

            }else{
                $scope.projectsPrepared.push($scope.projects);
            }

        }, function errorCallback(response) {

        });
    }

    

    // Get all projects of all categories in an array
    $scope.loadAllProjects = function(){

        $scope.showLoadingIndicator = true;
        $scope.projects = [];
        $scope.projectsPrepared = [];

        $scope.activeCategorie = "Alle Projekte";

        $http({
            method: 'GET',
            url: '/projects'
        }).then(function successCallback(response) {

            $scope.showLoadingIndicator = false;

            for(var u=0;u<Object.keys(response.data).length;u++){
                $scope.projects.push(response.data[u]);
            }

            if( $scope.projects.length > 3){

                var itarationValue = ($scope.projects.length / 3) | 0;

                for(var z=0;z<itarationValue;z++){
                    $scope.projectsPrepared.push([$scope.projects[0],$scope.projects[1],$scope.projects[2]]);
                    $scope.projects.splice(0, 3);
                }

                if($scope.projects.length % 3 != 0){
                    $scope.projectsPrepared.push($scope.projects);
                }

            }else{
                $scope.projectsPrepared.push($scope.projects);
            }

        }, function errorCallback(response) {

        });
    }



    /* Projektdetailseite */

    $scope.projectDetailInfo = null;

    $scope.redirectToProjekt = function(id, projectTitle) {
        $scope.actualProjectId = id;
        window.location.replace('./projekt/'+projectTitle+"/"+id);
    }

    // Get the Projekt Informatio to a concrete Projekt
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