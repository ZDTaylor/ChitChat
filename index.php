<?php
    // session code will go here.  Shouldn't need much other than that (we'll see :P)
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8 lt-ie7 full-height"> <![endif]-->
<!--[if IE 7]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9 lt-ie8 full-height"> <![endif]-->
<!--[if IE 8]>         <html lang="en" ng-app="myApp" class="no-js lt-ie9 full-height"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" ng-app="app" class="no-js full-height"> <!--<![endif]-->
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
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">

            <!-- Chit Chat icon -->
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
                </a>
            </div>

            <!--Center align(Couldn't get this to work, I never could find a solution with CSS or html)-->
            <div class="nav1 navbar-nav1 navbar-center">
                <p class="navbar-text text-center"> Chit Chat </p>
            </div>

            <!--Right aligned Dropdown menu-->
            <div class="nav navbar-nav navbar-right" ng-controller="NavController as nav">
                <div class="dropdown">
                    <div class="btn-group btn-group-lg" role="group" aria-label="..." uib-dropdown auto-close="outsideClick" is-open="nav.dropdownIsOpen">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" uib-dropdown-toggle>
                            {{nav.dropdownMessage}}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1" uib-dropdown-menu ng-if="nav.user.userId === null">
                            <li class="dropdown-header">Email</li>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter email" aria-describedby="basic-addon1" ng-model="nav.email">
                            </div>

                            <li class="dropdown-header">Password</li>
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Enter password" aria-describedby="basic-addon1" ng-model="nav.passwd">
                            </div>
                            <li><button type="button" class="btn btn-default" ng-click="nav.login()">Login</button></li>
                            <li><button type="button" class="btn btn-default" ng-click="nav.register()">Register</button></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" class="forgot-password">Forgot password?</a></li>
                        </ul>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1" uib-dropdown-menu ng-if="nav.user.userId !== null">
                            <li><button type="button" class="btn btn-default" ng-click="nav.logout()">Logout</button></li>
                            <li><button type="button" class="btn btn-default" ng-click="nav.deleteAccount()">Delete Account</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main body of the chat application -->
    <div class="container full-height">
        <div class="row full-height">
            <div class="col-xs-12 full-height">
                <div class="well full-height chat-window">
                    <p class="text-center"></p>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-inverse navbar-fixed-bottom cc-messagebox" ng-controller="MessageBoxController as messageBox">
        <form class="navbar-form" ng-submit="messageBox.postMessage()">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" name="Post message" placeholder="Type message here..." autocomplete="off" autofocus="autofocus" type="textarea" ng-model="messageBox.message.content">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-send"></span></button>
                    </span>
                </div>
            </div>
        </form>
    </nav>




    <!-- Comment the following before final upload to reduce file size -->
    <script src="js/vendor/angular.js"></script>
    <script src="js/vendor/angular-resource.js"></script>
    <script src="js/vendor/angular-animate.js"></script>
    <script src="js/vendor/ui-bootstrap-tpls-2.5.0.js"></script>

    <!-- Unomment the following before final upload to reduce file size -->
    <!--
    <script src="js/vendor/angular.min.js"></script>
    <script src="js/vendor/angular-resource.js"></script>
    <script src="js/vendor/angular-animate.min.js"></script>
    <script src="js/vendor/ui-bootstrap-tpls-2.5.0.min.js"></script>
    -->

    <!-- Custom angular app code for Chit Chat
         app.module.js must be loaded after other app modules.
         app modules must be loaded before any services that depend on them.
    -->
    <script src="js/user/users.module.js"></script>
    <script src="js/user/user.factory.js"></script>
    <script src="js/user/user.service.js"></script>

    <script src="js/message/messages.module.js"></script>
    <script src="js/message/message.factory.js"></script>
    <script src="js/message/message.service.js"></script>

    <script src="js/app.module.js"></script>
    <script src="js/constants.js"></script>

    <script src="js/nav.controller.js"></script>
    <script src="js/messageBox.controller.js"></script>
</body>
</html>