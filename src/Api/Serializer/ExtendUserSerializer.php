<?php

namespace Nearata\AuthMinecraft\Api\Serializer;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;
use Nearata\AuthMinecraft\Utils;

class ExtendUserSerializer
{
    public function __invoke(UserSerializer $serializer, User $user, array $attributes)
    {
        return Utils::isMinecraftUser($user);
    }
}
