<!doctype html>
<html ng-app="minovateApp" ng-controller="MainCtrl" class="no-js {{containerClass}}">
  <head>
    <meta charset="utf-8">
    <title>Triputra - Admin Dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="icon" type="image/png" href="favicon.png" />
    <link rel="stylesheet" href="styles/vendor.0cc3d200.css">
    <link rel="stylesheet" href="styles/main.1be6a35c.css">
    <link rel="stylesheet" href="styles/style.css">

  </head>
  <body id="minovate" class="{{main.settings.navbarHeaderColor}} {{main.settings.activeColor}} {{containerClass}} header-fixed aside-fixed rightbar-hidden appWrapper" ng-class="{'header-fixed': main.settings.headerFixed, 'header-static': !main.settings.headerFixed, 'aside-fixed': main.settings.asideFixed, 'aside-static': !main.settings.asideFixed, 'rightbar-show': main.settings.rightbarShow, 'rightbar-hidden': !main.settings.rightbarShow}">

    <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Application content -->
    <div id="wrap" ui-view autoscroll="false"></div>

    <!-- Page Loader -->
    <div id="pageloader" page-loader></div>


    <!--script src='//maps.googleapis.com/maps/api/js?libraries=weather,geometry,visualization,places,drawing&sensor=false&language=en&v=3.17'></script>

    <!--[if lt IE 9]>
    <script src="scripts/oldieshim.f2dbeece.js"></script>
    <![endif]-->
    <!--script src="scripts/googlemap.js"></script-->
    <script src="scripts/vendor.js"></script>
    <script src="scripts/app.js"></script>
    <script src="scripts/script.js"></script>

</body>
</html>
