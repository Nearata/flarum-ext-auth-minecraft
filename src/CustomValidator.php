<?php

namespace Nearata\AuthMinecraft;

use Flarum\Foundation\AbstractValidator;

class CustomValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'email' => [
                'required',
                'email:filter'
            ],
            'token' => [
                'required',
                'regex:/^[0-9]+$/i',
                'min:6',
                'max:6'
            ]
        ];
    }

    protected function getMessages()
    {
        return [
            'token.regex' => $this->translator->trans('nearata-auth-minecraft.api.invalid_token_format')
        ];
    }
}
