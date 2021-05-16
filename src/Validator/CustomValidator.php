<?php

namespace Nearata\AuthMinecraft\Validator;

use Flarum\Foundation\AbstractValidator;

class CustomValidator extends AbstractValidator
{
    protected $rules = [
        'token' => [
            'required',
            'regex:/^[0-9]+$/i',
            'min:6',
            'max:6'
        ]
    ];

    protected function getRules()
    {
        return $this->rules;
    }

    protected function getMessages()
    {
        return [
            'token.regex' => $this->translator->trans('nearata-auth-minecraft.api.invalid_token_format')
        ];
    }
}
