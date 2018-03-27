<?php
    require_once "../lib/UserManager.php";
    header('Content-type: application/json');

    $userManager = new UserManager();

    // Check for variables that got posted, and use userManager to try to login.

    // Use json_encode() to return whatever we decide needs to be returned
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode(/* whatever we are returning */);
?>