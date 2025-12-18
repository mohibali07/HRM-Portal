<?php defined('BASEPATH') OR exit('No direct script access allowed.');

if (!function_exists('valid_email')) {
    function valid_email($address)
    {
        return (bool) filter_var($address, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('name_email_format')) {

    function name_email_format($name, $email)
    {
        return $name . ' <' . $email . '>';
    }

}
