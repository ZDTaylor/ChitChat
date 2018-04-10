<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();

    // response must be an array.  We will declare all values at fields to return at the beginning and set them to
    // defaults to be returned if there is a failure.  Later, we will update them when there is a success.
    $response = [
        "success" => false
    ];

    // register.php needs to be accessed as a POST only
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Use this instead of $_POST array.  This is how the front end will transmit data
        // $data will be an associative array, just like $_POST would have been
        $data = json_decode(file_get_contents("php://input"), true);

        // We can use !empty() instead of isset() to check for existance and for non-falsy values
        if (!empty($data["email"]) && !empty($data["password"])) {

            // Always sanitize any input we get
            $email = sanitize_input($data["email"]);
            $password = sanitize_input($data["password"]);

            // Call the relevant class method
            $registration = $userManager->register($email, $password);

            // If the method returned successfully, update the success status
            if ($registration != false) {
                $response["success"] = true;
            }
        }
    }

    // Use json_encode() to return $response
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>