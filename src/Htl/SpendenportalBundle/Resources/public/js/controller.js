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
    $scope.getAllCategoriesInArray = function(){
        $http({
            method: 'GET',
            url: '/categories'
        }).then(function successCallback(response) {

            $scope.allCategories = response.data;

        }, function errorCallback(response) {

        });
    }


    $scope.projects = [];
    $scope.projectsPrepared = [];
    $scope.showNoProjects = false;


    // Get all projects of all categories in an array
    $scope.showLoadingIndicator = true;
    
    $scope.getAllProjects = function(){

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

            if(Object.keys($scope.projectsPrepared[0]).length == 0){
                $scope.showNoProjects = true;
            }



        }, function errorCallback(response) {

        });
        
    }

    


    // Get all projects to the related categorie id
    $scope.loadProjectsWithId = function(id, categoryName){

        $scope.showNoProjects = false;

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

            if(Object.keys($scope.projectsPrepared[0]).length == 0){
                $scope.showNoProjects = true;
            }

        }, function errorCallback(response) {

        });
    }

    

    // Get all projects of all categories in an array
    $scope.loadAllProjects = function(){

        $scope.showNoProjects = false;

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

            if(Object.keys($scope.projectsPrepared[0]).length == 0){
                $scope.showNoProjects = true;
            }

        }, function errorCallback(response) {

        });
    }



    /* Projektdetailseite */

    $scope.projectDetailInfo = null;
    $scope.firstPartOfText = "";
    $scope.secondPartOfText = "";


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

            // Split Project description into two parts

            var cutTextAtCharLength = 400;

            if( $scope.projectDetailInfo.description.length <= cutTextAtCharLength ){

                $scope.firstPartOfText = $scope.projectDetailInfo.description;

            }else{
                
                for(var i=0;i<$scope.projectDetailInfo.description.length;i++){
                    var c = $scope.projectDetailInfo.description.charAt(i);
                    if(i > (cutTextAtCharLength-50) && c==" "){
                        $scope.firstPartOfText = $scope.projectDetailInfo.description.substring(0, i);
                        $scope.secondPartOfText = $scope.projectDetailInfo.description.substring(i,$scope.projectDetailInfo.description.length);
                        break;
                    }
                }
                
                document.getElementById('readMoreBtnId').style.display = "block";
            }

            
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

            callThisFuncAfterAllPicturesLoaded();

        }, function errorCallback(response) {
        });

        // insert the pictures manually because if not the silderframework would have problems
        function callThisFuncAfterAllPicturesLoaded() {

            // Schon gespendet button ein/ausblenden anfang
            window.onload = function () {
                var hasDonated = document.getElementById("getHasDonatedValueId").innerHTML;
                if(hasDonated == "true" ){
                    document.getElementById("ifDonatedShowId").setAttribute("style", "display:block");
                }
            }
            // Schon gespendet button ein/ausblenden ende

            var img_counter = 0;

            for (var property in $scope.allProjectPictures) {
                if ($scope.allProjectPictures.hasOwnProperty(property)) {

                    var ul = document.getElementById("allSilderPicturesId");

                    var li_element = document.createElement("li");
                    var img_element = document.createElement("img");

                    img_element.setAttribute("src", "/bundles/htlspendenportal/img/".concat($scope.allProjectPictures[property].pictureUrl));

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









    $scope.showLoadingIndicatorAbonniert = true;
    $scope.projectsAbonniert = [];
    $scope.projectsPreparedAbonniert = [];
    $scope.showNoAbbonenten = false;

    // Get all abonnierte projects in an array
    $scope.loadAllProjectsAbonniert = function(){

        $http({
            method: 'GET',
            url: '/followingproject'
        }).then(function successCallback(response) {

            $scope.showLoadingIndicatorAbonniert = false;

            for(var u=0;u<Object.keys(response.data).length;u++){
                $scope.projectsAbonniert.push(response.data[u]);
            }

            if( $scope.projectsAbonniert.length > 3){

                var itarationValue = ($scope.projectsAbonniert.length / 3) | 0;

                for(var z=0;z<itarationValue;z++){
                    $scope.projectsPreparedAbonniert.push([$scope.projectsAbonniert[0],$scope.projectsAbonniert[1],$scope.projectsAbonniert[2]]);
                    $scope.projectsAbonniert.splice(0, 3);
                }

                if($scope.projectsAbonniert.length % 3 != 0){
                    $scope.projectsPreparedAbonniert.push($scope.projectsAbonniert);
                }

            }else{
                $scope.projectsPreparedAbonniert.push($scope.projectsAbonniert);
            }

            if(Object.keys($scope.projectsPreparedAbonniert[0]).length == 0){
                $scope.showNoAbbonenten = true;
            }


        }, function errorCallback(response) {

        });
    }


    /* Empfänger Dashboard Seite */
    $scope.allFinishedProjects = null;
    $scope.showFinishedProjects = false;

    
    $scope.finishedDashboardLoadingCounter = 0;
    $scope.showIndicatorDashboard = true;
   

    $scope.loadAllfinishedProjects = function(){

        $http({
            method: 'GET',
            url: '/finishedprojects'

        }).then(function successCallback(response) {

            $scope.allFinishedProjects = response.data;

            if( false == isEmpty($scope.allFinishedProjects) && $scope.allFinishedProjects != null ){
                $scope.showFinishedProjects = true;
            }

            $scope.finishedDashboardLoadingCounter+=1;

            if($scope.finishedDashboardLoadingCounter == 2){
                $scope.showIndicatorDashboard = false;
            }

        }, function errorCallback(response) {
        });
    }

    $scope.activeProject = null;
    $scope.showActiveProject = false;
    
    $scope.showAnlegenText = false; // Wenn er noch kein Projekt angelegt hat!
    
    $scope.loadActiveProject = function(){

        $http({
            method: 'GET',
            url: '/activeprojects'

        }).then(function successCallback(response) {

            $scope.activeProject = response.data;

            if( false == isEmpty($scope.activeProject) && $scope.activeProject != null ){
                $scope.showActiveProject = true;
            }else{
                $scope.showAnlegenText = true;
            }

            $scope.finishedDashboardLoadingCounter+=1;

            if($scope.finishedDashboardLoadingCounter == 2){
                $scope.showIndicatorDashboard = false;
            }

        }, function errorCallback(response) {
        });
    }


    function isEmpty(obj) {

        // null and undefined are "empty"
        if (obj == null) return true;

        // Assume if it has a length property with a non-zero value
        // that that property is correct.
        if (obj.length > 0)    return false;
        if (obj.length === 0)  return true;

        // If it isn't an object at this point
        // it is empty, but it can't be anything *but* empty
        // Is it empty?  Depends on your application.
        if (typeof obj !== "object") return true;

        // Otherwise, does it have any properties of its own?
        // Note that this doesn't handle
        // toString and valueOf enumeration bugs in IE < 9
        for (var key in obj) {
            if (hasOwnProperty.call(obj, key)) return false;
        }

        return true;
    }


    $scope.clickUpload = function(){
        document.getElementById('myupload').click();
    };

    $scope.clickUpload2 = function(){
        document.getElementById('file').click();
    };

    $scope.clickUpload3 = function(){
        document.getElementById('fos_user_registration_form_fileUpload').click();
    };

    $scope.displayTitelPictureError = false;

    $scope.titlePictureSelected = function(){
        if(document.getElementById("myupload").value == "") {
          // Bild muss noch ausgewählt werden
            document.getElementById('errorMsgId').innerHTML = "Kein Titelbild ausgewählt";
            $scope.displayTitelPictureError = true;
        }
    };


    // Projekt_bearbeiten Seite

    $scope.projektInfoBearbeiten = null;

    $scope.initBearbeiten = function(){

        $http({
            method: 'GET',
            url: '/activeprojects'

        }).then(function successCallback(response) {

            $scope.projektInfoBearbeiten = response.data;

        }, function errorCallback(response) {
        });
    }


    // mein_profil_empfaenger Seite

    $scope.empfaengerProfilInfo = null;
    
    $scope.initMeinProfilEmpfaenger = function(){

        $http({
            method: 'GET',
            url: '/userData'

        }).then(function successCallback(response) {

            $scope.empfaengerProfilInfo = response.data;

            console.log($scope.empfaengerProfilInfo);

        }, function errorCallback(response) {
        });
    }



    //Empfänger dashboard

    $scope.setProjectToDeleted = function(){

        document.getElementById('delBtnId').setAttribute("disabled","disabled");
        document.body.style.cursor='wait';

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

        $http.post('/deleteProject', null, config).then(function successCallback(response) {
            location.reload();
            document.body.style.cursor='default';
        }, function errorCallback(response) {
            console.log(response);
        });
    }



    $scope.checkIfUsernameOrEmalExists = function(){

        document.getElementById('saveButtonId').setAttribute("disabled","disabled");
        document.body.style.cursor='wait';

        var username = document.getElementById("usernameID").value;
        var email = document.getElementById('emailID').value;

        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

        var data = $.param({
            username: username,
            email: email
        });

        $http.post('/alreadyGiven', data, config).then(function successCallback(response) {

            $scope.alreadGiven = response.data;
            
            if($scope.alreadGiven[0].email == false){

                if($scope.alreadGiven[0].username == false){

                    // add action to btn and trigger the button

                    document.getElementById('addActionId').setAttribute("action","/updateUser");

                    document.forms["addActionId"].submit();
                    
                }else{
                    // Username gibts bereits
                    alert('Benutzername existiert bereits!');
                }
            }else{
                // Email gibts bereits
                alert('Email existiert bereits!');
            }

            document.body.style.cursor='default';
            document.getElementById('saveButtonId').removeAttribute("disabled");

        }, function errorCallback(response) {
            console.log(response);
        });
    }
    
    
});


