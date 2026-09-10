<?php

// 1. Manually require the Valitron source file
require_once __DIR__ . '/../Librarys/Valitron/Validator.php';

use Valitron\Validator as V;

V::langDir(__DIR__ . '/../Librarys/Valitron/lang');

V::lang('pt-br');

class InvalidEmail extends Exception
{
}

class InvalidUsername extends Exception
{
}

class InvalidPassword extends Exception
{
}

class Validation
{
    public static function makeValidator($input)
    {
        return (new Valitron\Validator($input));
    }

    public static function isValidEmail($email)
    {
        $email_validated = self::makeValidator(['email' => $email]);
        $email_validated->rule('required', 'email');
        $email_validated->rule('email', 'email');

        if (!$email_validated->validate()) {
            $errors = $email_validated->errors('email'); // Gets errors specifically for the 'email' field

            // Check which specific rule failed
            if (isset($errors['required'])) {
                throw new InvalidEmail($errors['required'][0]);
            }

            if (isset($errors['email'])) {
                throw new InvalidEmail($errors['email'][0]);
            }
        }

        return true;
    }

    public static function isValidUsername($username)
    {
        $username_validated = self::makeValidator(['username' => $username]);
        $username_validated->rule('required', 'username');

        if (!$username_validated->validate()) {
            $errors = $username_validated->errors('username'); // Gets errors specifically for the 'username' field

            // Check which specific rule failed
            if (isset($errors['required'])) {
                throw new InvalidUsername($errors['required'][0]);
            }
        }
        return true;
    }

    public static function isValidPassword($password)
    {
        $password_validated = self::makeValidator(['password' => $password]);
        $password_validated->rule('required', 'password');

        if (!$password_validated->validate()) {
            $errors = $password_validated->errors('password'); // Gets errors specifically for the 'password' field

            // Check which specific rule failed
            if (isset($errors['required'])) {
                throw new InvalidPassword($errors['required'][0]);
            }
        }
        return true;
    }
}
