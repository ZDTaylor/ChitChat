<?php

    // sanitize input function used from https://www.w3schools.com/php/php_form_validation.asp
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>