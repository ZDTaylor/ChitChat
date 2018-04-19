<?php
    set_include_path(getcwd() . '/..');
    require_once "lib/UserManager.php";
    require_once "lib/sanitize_input.php";
    header('Content-type: application/json');
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

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

			if (!empty($data["userID"]) && !empty($data["datetime"])) {

                $datetime = new DateTime("@".$data["datetime"]);
				// pass userid and datetime to usermanager suspend and
                // update $response["success"] accordingly
				$suspend = $userManager->suspend($data["userID"], $datetime);
				// $data["userid"] is the user to suspend, current user should be in session variable
				if($suspend ==true){
				$response["success"] = true;
				}
			}
		}
	}

    // Use json_encode() to return $response
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>