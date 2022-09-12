<?php

namespace Nearata\AuthMinecraft\Api\Middleware;

use Flarum\Http\RequestUtil;
use Flarum\User\Exception\PermissionDeniedException;
use Illuminate\Support\Arr;
use Nearata\AuthMinecraft\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Even without this, the user is not able to change the email
 * because they don't know the password of the account do to it.
 */
class DenyMinecraftUserEmailChange implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $actor = RequestUtil::getActor($request);

        if ($actor->isGuest()) {
            return $response;
        }

        $data = Arr::get($request->getParsedBody(), 'data', []);

        if (isset($data['attributes']['email']) && Utils::isMinecraftUser($actor)) {
            throw new PermissionDeniedException();
        }

        return $response;
    }
}
