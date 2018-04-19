<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = [
        "success" => false
    ];

    session_start();

    if(isset($_SESSION["user"])) {
        $_SESSION["user"] = $userManager->checkBannedSuspended($_SESSION["user"]);
        if ($_SESSION["user"]->banned || new DateTime() < $_SESSION["user"]->suspended) {
            unset($_SESSION["user"]);
        }
    }

	if($_SESSION["user"]->isAdmin == true){

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$data = json_decode(file_get_contents("php://input"), true);

			if (!empty($data["userID"])) {

				// pass userid to usermanager ban and
				// update $response["success"] accordingly
					$ban = $userManager->ban($data["userID"]);
					// $data["userid"] is the user to suspend, current user should be in session variable
					if($ban == true){
					$response["success"] = true;
					}
            // $data["userid"] is the user to ban, current user should be in session variable
			}
        }
    }

    // Use json_encode() to return $response
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>