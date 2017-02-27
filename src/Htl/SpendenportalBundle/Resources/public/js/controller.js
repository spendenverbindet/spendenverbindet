var app = angular.module('SpendenverbindetApp', []);

app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

app.controller('spendenverbindetController', function($scope, $http) {


    $scope.inishedLoadingProjektDetailCounter = 0;



    $scope.readMoreTxt = "mehr lesen";
    var zaehler = 0;

    $scope.readMoreOrLessTriggered = function(){
        if(zaehler%2 == 0){
            $scope.readMoreTxt = "weniger lesen";
        }else{
            $scope.readMoreTxt = "mehr lesen";
        }
        zaehler+=1;
    }



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


            $scope.inishedLoadingProjektDetailCounter+=1;
            $scope.didFinishLoading();

        }, function errorCallback(response) {

        });
    }

    $scope.followerBtnText = "";

    // Folgt der User dem Projekt gerade ja oder nein? Folgende rquest ist dafür da um dies herauszufinden:
    $scope.initFollowerButton = function(){
        
        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/initFollower/'+$scope.actualProjectId
        }).then(function successCallback(response) {

            if(response.data == true){
                $scope.followerBtnText = "ENTFOLGEN";
            }
            if(response.data == false){
                $scope.followerBtnText = "FOLGEN";
            }

            $scope.inishedLoadingProjektDetailCounter+=1;
            $scope.didFinishLoading();

        }, function errorCallback(response) {

        });
    }

    $scope.followerBtnDisabled = false;

    $scope.followerBtnPressed = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];


        if($scope.followerBtnText != ""){

            if($scope.followerBtnText == "ENTFOLGEN"){

                $scope.followerBtnDisabled = true;
                document.body.style.cursor='wait';

                $http({
                    method: 'DELETE',
                    url: '/deleteFollower/'+$scope.actualProjectId

                }).then(function successCallback(response) {
                    document.body.style.cursor='default';
                    $scope.followerBtnText = "FOLGEN";
                    $scope.followerBtnDisabled = false;


                }, function errorCallback(response) {
                });
            }
            else{

                $scope.followerBtnDisabled = true;
                document.body.style.cursor='wait';

                $http({
                    method: 'POST',
                    url: '/createFollower/'+$scope.actualProjectId

                }).then(function successCallback(response) {
                    document.body.style.cursor='default';
                    $scope.followerBtnText = "ENTFOLGEN";
                    $scope.followerBtnDisabled = false;


                }, function errorCallback(response) {
                });
            }
        }
    }

    $scope.allProjectPictures = [];

    $scope.loadingFinished = false;

    $scope.initAllPicturesOfProject = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/pictures/'+$scope.actualProjectId

        }).then(function successCallback(response) {

            $scope.allProjectPictures = response.data;


        }, function errorCallback(response) {

        });




        // insert the pictures manually because if not the silderframework would have problems
        window.onload = function () {

            var ul = document.getElementById("allSilderPicturesId");
            ul.removeChild(document.getElementById("dummyentry"));

            var img_counter = 0;

            for (var property in $scope.allProjectPictures) {
                if ($scope.allProjectPictures.hasOwnProperty(property)) {

                    var ul = document.getElementById("allSilderPicturesId");

                    var li_element = document.createElement("li");
                    var img_element = document.createElement("img");

                    img_element.setAttribute("src", "/bundles/htlspendenportal/img/".concat($scope.allProjectPictures[property].pictureUrl));
                    img_element.setAttribute("onclick", "showThisImageInBig('".concat($scope.allProjectPictures[property].pictureUrl).concat("')"));
                    img_element.setAttribute("class", "clickable");
                    img_element.setAttribute("onload", "pictureLoaded()");


                    li_element.appendChild(img_element);

                    ul.appendChild(li_element);

                    img_counter+=1;
                }
            }
            
            if( img_counter == 0){
                document.getElementById("gallery_image_loader_id").setAttribute("style", "display:none");
            }

        }
    }
    

    $scope.allPostsOfCurrentSite = null;

    $scope.initPosts = function(){

        // Aus der Url die projectId holen
        var resArray = document.URL.split("/");
        $scope.actualProjectId = resArray[resArray.length-1];

        $http({
            method: 'GET',
            url: '/posts/'+$scope.actualProjectId

        }).then(function successCallback(response) {

            $scope.allPostsOfCurrentSite = response.data;

            console.log($scope.allPostsOfCurrentSite);

            $scope.inishedLoadingProjektDetailCounter+=1;
            $scope.didFinishLoading();

        }, function errorCallback(response) {
        });

    }



    $scope.didFinishLoading = function(){
        if($scope.inishedLoadingProjektDetailCounter == 3){ // Becuase of the functions initFollowerButton();initProjektDetail();initPosts()
            $scope.loadingFinished = true;
        }
    }




});

