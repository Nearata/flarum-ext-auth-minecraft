<?php

namespace Nearata\AuthMinecraft;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;

class Utils
{
    public static function validate(string $ip): Response
    {
        $settings = resolve(SettingsRepositoryInterface::class);

        $secret = $settings->get('nearata-auth-minecraft.api-secret');
        $api = $settings->get('nearata-auth-minecraft.api-url');

        return (new Factory)
            ->withHeaders(['secret' => $secret])
            ->post($api, ['ip' => $ip]);
    }

    public static function isMinecraftUser(User $user): bool
    {
        $provider = 'minecraft';
        $user_id = $user->id;

        $found = $user->loginProviders()->where(compact('provider', 'user_id'))->first();

        if ($found == null) {
            return false;
        }

        return true;
    }
}
