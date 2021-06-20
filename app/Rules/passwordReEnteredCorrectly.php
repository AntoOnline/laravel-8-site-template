<?php

namespace App\Rules;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;
/**
 * Rule; Checks if the password entered matches the password of the logged in user.
 */
class passwordReEnteredCorrectly implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (Auth::user() && Hash::check($value, Auth()->user()->password));

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have entered an incorrect password, please try again.';
    }
}
