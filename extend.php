<?php

namespace Nearata\AuthMinecraft;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\Extend;
use Nearata\AuthMinecraft\Api\Controller\MinecraftAuthController;
use Nearata\AuthMinecraft\Api\Middleware\DenyMinecraftUserEmailChange;
use Nearata\AuthMinecraft\Api\Serializer\ExtendUserSerializer;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('api'))
        ->post('/auth/minecraft', 'nearata-auth-minecraft.auth', MinecraftAuthController::class),

    (new Extend\Settings)
        ->serializeToForum('nearataMinecraftServerIp', 'nearata-auth-minecraft.server-ip'),

    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('nearataAuthMinecraftFromServer', ExtendUserSerializer::class),

    (new Extend\Middleware('api'))
        ->add(DenyMinecraftUserEmailChange::class)
];
