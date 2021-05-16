<?php

namespace Nearata\AuthMinecraft;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class TokenHelper
{
    public static function validate(string $token): Response
    {
        return (new Factory)
            ->withHeaders(["token" => $token])
            ->get('https://mc-oauth.net/api/api');
    }
}
