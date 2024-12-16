<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;

class PasswordValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(ValidationFactory $factory)
    {
        $factory->extend('validatePassword', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        });

        $factory->replacer('validatePassword', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.');
        });
    }
}
