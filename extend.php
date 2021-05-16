<?php

namespace Nearata\AuthMinecraft;

use Flarum\Extend;
use Nearata\AuthMinecraft\Api\Controller\ChangeEmailController;
use Nearata\AuthMinecraft\Api\Controller\MinecraftAuthController;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->post('/auth/minecraft', 'nearata-auth-minecraft.auth', MinecraftAuthController::class)
        ->post('/minecraft/changeEmail', 'nearata-auth-minecraft.change_email', ChangeEmailController::class)
];
