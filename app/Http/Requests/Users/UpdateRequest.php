<?php

namespace App\Http\Requests\Users;

class UpdateRequest extends StoreRequest
{
    public function rules()
    {
        if ($this->has('password')) {
            return ['password' => 'min:6|confirmed'];
        }

        $rules = [
            'email' => 'required|unique:users,email,' . $this->route('users')->id,
        ];

        return $this->rulesWIthoutPassword($rules + parent::rules());
    }

    protected function rulesWIthoutPassword($rules)
    {
        unset($rules['password']);

        return $rules;
    }
}
