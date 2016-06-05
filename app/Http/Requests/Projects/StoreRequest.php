<?php

namespace App\Http\Requests\Projects;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return ['name' => 'required'];
    }

    public function messages()
    {
        return [
            'name.required' => 'A project must have a name!'
        ];
    }
}
