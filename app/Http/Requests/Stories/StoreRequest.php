<?php

namespace App\Http\Requests\Stories;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return ['title' => 'required'];
    }

    public function messages()
    {
        return ['title.required' => 'A story needs a title.'];
    }
}
