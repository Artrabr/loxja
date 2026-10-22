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
            $errors = $email_validated->errors('email');      // flat list of messages
            throw new InvalidEmail($errors[0]); // or check message text if needed
        }
        return true;
    }

    public static function isValidUsername($username)
    {
        $username_validated = self::makeValidator(['username' => $username]);
        $username_validated->rule('required', 'username');

        if (!$username_validated->validate()) {
            $errors = $username_validated->errors('username');      // flat list of messages
            throw new InvalidUsername($errors[0]); // or check message text if needed
        }
        return true;
    }

    public static function isValidPassword($password)
    {
        $password_validated = self::makeValidator(['password' => $password]);
        $password_validated->rule('required', 'password');


        if (!$password_validated->validate()) {
            $errors = $password_validated->errors('password');      // flat list of messages
            throw new InvalidPassword($errors[0]); // or check message text if needed
        }
        return true;
    }
}
