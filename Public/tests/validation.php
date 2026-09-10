<?php

require_once __DIR__ . "/../../Src/Validation.php";

function is_valid_email($email)
{
    $is_valid_email = Validation::isValidEmail($email);

    echo "<p>email {$email} is a " . ($is_valid_email ? 'true' : 'false') . " email</p>" . PHP_EOL;
}
is_valid_email("email@example.com");

is_valid_email("error_example.com");
