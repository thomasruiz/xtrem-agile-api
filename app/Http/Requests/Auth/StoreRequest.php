<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return ['email' => 'required', 'password' => 'required'];
    }
}
