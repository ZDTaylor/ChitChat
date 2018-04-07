<?php
    // session code will go here.  Shouldn't need much other than that (we'll see :P)
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8 lt-ie7 full-height"> <![endif]-->
<!--[if IE 7]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8 full-height"> <![endif]-->
<!--[if IE 8]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9 full-height"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" ng-app="myApp" class="no-js full-height"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chit Chat</title>
    <meta name="description" content="An anonymous chat application for the 21st century">

    <!-- Comment the following before final upload to reduce file size -->
    <link rel="stylesheet" href="css/vendor/bootstrap.css">
    <link rel="stylesheet" href="css/vendor/bootstrap-theme.css">

    <!-- Uncomment the following before final upload to reduce file size -->
    <!--
    <link rel="stylesheet" href="css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="css/vendor/bootstrap-theme.min.css">
    -->

    <!-- Custom stylesheet for Chit Chat -->
    <link rel="stylesheet" href="css/app.css">

</head>
<body class="full-height">

    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Navbar for the page -->
    <nav class="navbar navbar-default navbar-fixed-top" ng-controller="NavCtrl">
        <div class="container">

            <!-- Chit Chat icon -->
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
                </a>
            </div>

            <p class="navbar-text text-center"> {{headerMessage}} </p>

            <!-- Sign in button -->
            <button type="button" class="btn btn-default navbar-btn navbar-right">Sign in</button>
        </div>
    </nav>

    <!-- Main body of the chat application -->
    <div class="container full-height" ng-controller="ChatCtrl">
        <div class="row full-height">
            <div class="col-xs-12 full-height">
                <div class="well full-height chat-window">
                    <p class="text-center"> {{bodyMessage}} </p>
                </div>
            </div>
        </div>
    </div>



    <!-- Comment the following before final upload to reduce file size -->
    <script src="js/vendor/angular.js"></script>
    <script src="js/vendor/angular-animate.js"></script>
    <script src="js/vendor/ui-bootstrap-tpls-2.5.0.js"></script>

    <!-- Unomment the following before final upload to reduce file size -->
    <!--
    <script src="js/vendor/angular.min.js"></script>
    <script src="js/vendor/angular-animate.min.js"></script>
    <script src="js/vendor/ui-bootstrap-tpls-2.5.0.min.js"></script>
    -->

    <!-- Custom angular app code for Chit Chat
         app.js must be loaded before all other controllers
    -->
    <script src="js/app.js"></script>
    <script src="js/app.navCtrl.js"></script>
    <script src="js/app.chatCtrl.js"></script>
</body>
</html>