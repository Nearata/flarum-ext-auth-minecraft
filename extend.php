<?php

namespace Nearata\AuthMinecraft;

use Flarum\Extend;
use Nearata\AuthMinecraft\Api\Controller\MinecraftAuthController;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->post('/auth/minecraft', 'nearata.auth-minecraft', MinecraftAuthController::class)
];
