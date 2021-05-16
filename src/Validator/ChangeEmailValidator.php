<?php

namespace Nearata\AuthMinecraft\Validator;

class ChangeEmailValidator extends CustomValidator
{
    protected function getRules()
    {
        return array_merge($this->rules, [
            'email' => [
                'required',
                'email:filter',
                'unique:users,email'
            ]
        ]);
    }
}
