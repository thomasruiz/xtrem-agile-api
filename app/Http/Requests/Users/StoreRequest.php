<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => 'We need your name so that others can know who you are.',
            'email.required'     => 'We need your email to verify you\'re a real person, and also to message you ' .
                                    'sometimes (just to know if you\'re okay <3).',
            'password.min'       => '6 characters is an absolute minimum for a password. We strongly encourage you ' .
                                    'to go above 10, and use special characters.',
            'password.required'  => 'Use a password to protect your account. ' .
                                    'Getting used to it by that time, aren\'t you?',
            'password.confirmed' => 'The passwords don\'t match!',
        ];
    }
}
