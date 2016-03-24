/*
  Date: 216-01
  Author: BlueSky
*/

app.controller('UserCtrl', function( $scope, $http, $mdDialog ){

    $scope.perpage = num_per_page;
    $scope.currentPage = 1;
    $scope.users = [];
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.users.length );
    }

    $scope.getUsers = function(){

      var dataObj = {
        search_text : $scope.search_text
      };

      $scope.users = [];

      var res = $http.post( api_url + "getAllUsers", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {
        if( data != "false" )
        {
          $scope.users = data;
          $scope.currentPage = 1;
          max_page = Math.ceil( $scope.users.length / $scope.perpage );
          $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.users.length );
        }
      });
    }

    $scope.showRoleLang = function( role ){
        userRole = ["Super Admin", "Branch Manager", "Sales"];
        return userRole[role-1];
    }

    $scope.editUserForm = function(ev, userID){

        if( !userID )
          return;

        $http.post( api_url + "getUserById", { "userID": userID }, {headers: {'Content-Type': 'application/json'} })
        .success(function(data, status, headers, config) {

          if( data ){

              $mdDialog.show({
                controller: 'UserFormCtrl',
                templateUrl: 'views/pages/user-add.html',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true,
                locals: {
                   user: data
                 },
              })
              .then(function(answer) {
                $scope.getUsers();
              });
          }
        });
    };

    $scope.addUserForm = function(ev) {

        $mdDialog.show({
          controller: 'UserFormCtrl',
          templateUrl: 'views/pages/user-add.html',
          parent: angular.element(document.body),
          targetEvent: ev,
          clickOutsideToClose:true,
          locals: {
             user: {}
           },
        })
        .then(function(answer) {
          $scope.getUsers();
        });
    };

    $scope.deleteUser = function( userID ){

        if( !window.confirm( "Delete this user?" ) )
          return;
        var dataObj = {
          "userID": userID
        };

        var res = $http.post( api_url + "deleteUser", dataObj, {headers: {'Content-Type': 'application/json'} });
        res.success(function(data, status, headers, config) {
            $scope.getUsers();
        });
    }

    $scope.selectAll = function () {
      angular.forEach($scope.users, function(user) {
        user.selected = $scope.selectedAll;
      });
    };

    $scope.getUsers();
});

app.controller('UserFormCtrl', function( $scope, $mdDialog, $http, user ){

    $scope.user = user;
    $scope.saveUser = function(){
      var dataObj = {
        userID    : $scope.user.userID,
        firstName : $scope.user.firstName,
        lastName  : $scope.user.lastName,
        username  : $scope.user.username,
        email     : $scope.user.email,
        password  : $scope.user.password,
        role      : $scope.user.role
      };

      var res = $http.post( api_url + "saveUser", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {
        if( data )
            $mdDialog.hide();
      });
    };

    $scope.checkEmail = function(){

      var res = $http.post( api_url + "checkExistEmail", {'email':$scope.user.email}, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {

        if( data == "false" || $scope.user.email == data.email )
          $scope.userForm.email.dup = false;
        else{
          $scope.userForm.email.dup = true;
        }
      });
    };

    $scope.checkUserName = function(){

      var res = $http.post( api_url + "checkExistUsername", {'username':$scope.user.username}, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {

        if( data == "false" || $scope.user.username == data.username )
          $scope.userForm.username.dup = false;
        else{
          $scope.userForm.username.dup = true;
        }
      });
    };
});

app.controller('LightBoxCtrl', function( $scope, $mdDialog, $http ){

    $scope.hide = function() {
      $mdDialog.hide();
    };
    $scope.close = function() {
      $mdDialog.cancel();
    };
    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
});

app.controller('NewsCtrl', function( $scope, $http, $state, $mdDialog, $stateParams, $timeout ){

    $scope.page = {
      title: 'Masonry',
      subtitle: 'Place subtitle here...'
    };
    $scope.newslist = {};

    $scope.getNews = function(){
      $scope.newslist = {};
      var request_url = api_url + "getNewsList";
      $http.get( request_url, {})
        .success(function(data, status, headers, config) {
          $scope.newslist = data;
        });
    };

    $scope.deleteNews = function (newsID){

        if( !window.confirm( "Delete this user?" ) )
            return;

        var dataObj = {
            "newsID": newsID
        };

        var res = $http.post( api_url + "deleteNews", dataObj, {headers: {'Content-Type': 'application/json'} });
        res.success(function(data, status, headers, config) {
            $scope.getNews();
        });
    };

    $scope.getNews();
});

app.controller('NewsAddCtrl', function( $scope, $http, $state, $mdDialog, $stateParams){

    $scope.news = {};
    if( $stateParams.newsID != "undefined" && $stateParams.newsID > 0 )
    {
        newsID = $stateParams.newsID;
        $http.post( api_url + "getNewsById", { "newsID": newsID }, {headers: {'Content-Type': 'application/json'} })
          .success(function(data, status, headers, config) {
            $scope.img_url = upload_url + "img/";
            $scope.news = data;
          });
    }

    $scope.saveNews = function(){

      var dataObj = {
        newsID      : $scope.news.newsID,
        newsTitle   : $scope.news.newsTitle,
        imgPath     : $scope.news.imgPath,
        newsContent : $scope.news.newsContent
      };

      var res = $http.post( api_url + "saveNews", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success( function(data, status, headers, config ){
          $scope.getNews();
          $state.go('app.news');
      });
    };

    $scope.showUploadDialog = function(ev) {

      $mdDialog.show({
        controller: 'FileUploadCtrl',
        templateUrl: 'views/pages/upload.html',
        resolve: {
          plugins: ['$ocLazyLoad', function($ocLazyLoad) {
            return $ocLazyLoad.load([
              'scripts/vendor/filestyle/bootstrap-filestyle.min.js'
            ]);
          }]
        },

        parent: angular.element(document.body),
        targetEvent: ev,
        clickOutsideToClose:true,
      })
      .then(function(answer) {
        //$scope.getUsers();
        $scope.news.imgPath = answer;
      });
    };

});

// child of FormUploadCtrl from app.js
app.controller('FileUploadCtrl', ['$scope', 'FileUploader','$mdDialog', function($scope, FileUploader, $mdDialog) {

    var uploader = $scope.uploader = new FileUploader({
      url: api_url+'uploadFile' //enable this option to get f
    });

    // FILTERS
    uploader.filePath = "";
    uploader.filters.push({
      name: 'customFilter',
      fn: function() {
        return this.queue.length < 1;
      }
    });

    uploader.filters.push({
      name: 'imageFilter',
      fn: function(item /*{File|FileLikeObject}*/, options) {
          var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
          return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
      }
    });

    // CALLBACKS

    uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
      $scope.error_message = "Error! Already exist a image in queue or Incorrect file type";
      console.info('onWhenAddingFileFailed', item, filter, options);
    };
    uploader.onAfterAddingFile = function(fileItem) {
      $scope.error_message = "";
      console.info('onAfterAddingFile', fileItem);
    };
    uploader.onAfterAddingAll = function(addedFileItems) {
      console.info('onAfterAddingAll', addedFileItems);
    };
    uploader.onBeforeUploadItem = function(item) {
      console.info('onBeforeUploadItem', item);
    };
    uploader.onProgressItem = function(fileItem, progress) {
      console.info('onProgressItem', fileItem, progress);
    };
    uploader.onProgressAll = function(progress) {
      console.info('onProgressAll', progress);
    };
    uploader.onSuccessItem = function(fileItem, response, status, headers) {
      console.info('onSuccessItem', fileItem, response, status, headers);
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
      console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCancelItem = function(fileItem, response, status, headers) {
      console.info('onCancelItem', fileItem, response, status, headers);
    };
    uploader.onCompleteItem = function(fileItem, response, status, headers) {
      if( response.answer == "success"){
        this.filePath = response.Path;
      }
      console.info('onCompleteItem', fileItem, response, status, headers);
    };
    uploader.onCompleteAll = function() {
      console.info('onCompleteAll');
    };

    $scope.hide = function() {
      $mdDialog.hide( uploader.filePath );
    };

    $scope.close = function() {
      $mdDialog.cancel();
    };

    console.info('uploader', uploader);
  }]);



app.controller('RouteUploadCtrl', function( $scope, $mdDialog, $http ){

    $scope.showAddStoreDialog = function(ev, storeID) {

      $mdDialog.show({
        controller: 'LightBoxCtrl',
        templateUrl: 'views/pages/route-new-dest.html',
        parent: angular.element(document.body),
        targetEvent: ev,
        clickOutsideToClose:true
      })
      .then(function(answer) {
        //$scope.getUsers();
      });
    };

    $scope.showMapDialog = function(ev) {

      $mdDialog.show({
        controller: 'LightBoxCtrl',
        templateUrl: 'views/pages/route-map.html',
        parent: angular.element(document.body),
        targetEvent: ev,
        clickOutsideToClose:true
      })
      .then(function(answer) {
        //$scope.getUsers();
      });
    };

    $scope.deleteStore = function( storeID ){

        if( !window.confirm( "Delete this Store?" + storeID ) )
          return;

        var dataObj = {
          "storeID": storeID
        };

        $http.post( api_url + "deleteStore", dataObj, {headers: {'Content-Type': 'application/json'} })
        .success(function(data, status, headers, config) {
            //$scope.getUsers();
        });
    }
});

app.controller('SalesDetailCtrl', function( $scope, $http, $stateParams ){

    orderID = $stateParams.orderID;
    $scope.order = [];

    $scope.perpage  = num_per_page;
    $scope.currentPage = 1;
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.order.product_info.length );
    }

    $scope.getOrderInfo = function(){
      $http.post( api_url + "getOrderDetails", { "orderID": orderID }, {headers: {'Content-Type': 'application/json'} })
        .success(function(data, status, headers, config) {
          $scope.order = data;
          $scope.currentPage = 1;
          max_page = Math.ceil( $scope.order.product_info.length / $scope.perpage );
          $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.order.product_info.length );
        });
    }

    $scope.getOrderInfo();
});

app.controller('SalesCtrl', function( $scope, $http ){

    $scope.orders   = [];
    $scope.perpage  = num_per_page;
    $scope.currentPage = 1;
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.orders.length );
    }

    $scope.getOrders = function(){

      var dataObj = {
      };

      $scope.orders = [];

      $http.post( api_url + "getOrders", dataObj, {headers: {'Content-Type': 'application/json'} }).success(function(data, status, headers, config) {
        if( data != "false" )
        {
          $scope.orders = data;
          $scope.currentPage = 1;
          max_page = Math.ceil( $scope.orders.length / $scope.perpage );
          $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.orders.length );
        }
      });
    }

    $scope.getOrders();
});

app.controller('RouteCtrl', function( $scope, $http, $timeout, $mdDialog ){

    $scope.users = [];
    $scope.routes = [];
    $scope.perpage  = num_per_page;
    $scope.currentPage = 1;
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.routes.length );
    }

    var res = $http.post( api_url + "getAllUsers", {}, {headers: {'Content-Type': 'application/json'} });
    res.success(function(data, status, headers, config) {
      if( data != "false" )
      {
        $scope.users = data;
      }
    });

    $scope.getRoutes = function( selectedUser='' ){
      $scope.routes = [];
      var dataObj = {
          userID : selectedUser
      };
      var res = $http.post( api_url + "getRoutes", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {
        if( data != "false" )
        {
          $scope.routes = data;
        }
      });
    }
    $scope.changeSales = function(){
        this.getRoutes( this.salesUser );
    }

    $scope.showMapDialog = function(ev) {

      $mdDialog.show({
        controller: 'LightBoxCtrl',
        templateUrl: 'views/pages/route-map.html',
        parent: angular.element(document.body),
        targetEvent: ev,
        clickOutsideToClose:true
      })
      .then(function(answer) {
        //$scope.getUsers();
      });
    };
    $scope.getRoutes();
});

app.controller('customMapCtrl', function($scope) {
    $scope.map = {center: {latitude: 51.218553, longitude: 4.40491 }, zoom: 16, bounds: {},
    polylines :[
    {
      id: 1,
      path: [
        {
            latitude: 51.219053,
            longitude: 4.40441
        },
        {
            latitude: 51.218053,
            longitude: 4.40541
        },
        {
            latitude: 51.220053,
            longitude: 4.40841
        }
      ],
      stroke: {
        color: '#00BFF3',
        weight: 12
      },
      editable: false,
      draggable: false,
      geodesic: false,
      visible: true,
      }
    ],
    circleOptions:{
        radius: 4,
        stroke: {
          color: '#FE6D40',
          weight: 30,
          opacity: 1
        },
        fill: {
          color: '#FE6D40',
          opacity: 1
        },
        geodesic: false, // optional: defaults to false
        draggable: false, // optional: defaults to false
        clickable: true, // optional: defaults to true
        editable: false, // optional: defaults to false
        visible: true, // optional: defaults to true
        events:{
          dblclick: function(){
            window.alert("circle dblclick");
          }
        }
      },
      circles: [
        {
          id: 1,
          center: {
            latitude: 51.219053,
            longitude: 4.40441
          }
        },
        {
          id: 2,
          center: {
            latitude: 51.218053,
            longitude: 4.40541
          }
        },
        {
          id: 3,
          center: {
            latitude: 51.220053,
            longitude: 4.40841
          }
        },
      ]
    };

    $scope.options = {scrollwheel: false};
  });

app.controller('ReportCtrl', function($scope, $http ) {

    $scope.perpage = num_per_page;
    $scope.currentPage = 1;
    $scope.reports = [];
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.reports.length );
    }

    $scope.getReports = function(){

      var dataObj = {};
      $scope.reports = [];

      var res = $http.post( api_url + "getReports", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {
        if( data != "false" )
        {
          $scope.reports = data;
          $scope.currentPage = 1;
          max_page = Math.ceil( $scope.reports.length / $scope.perpage );
          $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.reports.length );
        }
      });
    }

    $scope.getReports();
});

app.controller('ReportDetailCtrl', function($scope, $http ) {
});

app.controller('ReportPersonCompleteCtrl', function($scope, $http, $stateParams ) {

    $scope.perpage = num_per_page;
    $scope.currentPage = 1;
    $scope.person_reports = [];
    max_page = 1;

    $scope.setpage = function( pagenum ){

      if( pagenum < 1)
        $scope.currentPage = 1;
      else if( pagenum > max_page)
        $scope.currentPage = max_page;
      else
        $scope.currentPage = pagenum;

      $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.person_reports.length );
    }

    userID = $stateParams.userID;

    $scope.user = {};
    $http.post( api_url + "getUserById", { "userID": userID }, {headers: {'Content-Type': 'application/json'} })
      .success(function(data, status, headers, config) {
        if( data )
          $scope.user = data;
      });

    $scope.getPersonCompleteReports = function( ){

      var dataObj = {"userID": userID};
      $scope.person_reports = [];

      var res = $http.post( api_url + "getPersonCompleteReports", dataObj, {headers: {'Content-Type': 'application/json'} });
      res.success(function(data, status, headers, config) {
        if( data != "false" )
        {
          $scope.person_reports = data;1
          $scope.currentPage = 1;
          max_page = Math.ceil( $scope.person_reports.length / $scope.perpage );
          $scope.to_limit = Math.min( $scope.currentPage * $scope.perpage, $scope.person_reports.length );
          $scope.total_count = $scope.person_reports.length;
        }
      });
    }

    $scope.getPersonCompleteReports();
});
