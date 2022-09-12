<?php

namespace Nearata\AuthMinecraft\Api\Controller;

use Flarum\Forum\Auth\Registration;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Nearata\AuthMinecraft\Forum\Auth\CustomResponseFactory;
use Nearata\AuthMinecraft\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MinecraftAuthController implements RequestHandlerInterface
{
    protected $response;

    public function __construct(CustomResponseFactory $response)
    {
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $response = Utils::validate($ipAddress);

        $statusCode = $response->status();

        if ($statusCode !== 200) {
            return new EmptyResponse($statusCode);
        }

        $json = $response->json();

        $id = $json['id'];
        $username = $json['username'];
        $avatar = $json['avatar'];

        $payload = [
            'id' => $id,
            'username' => $username,
            'avatar' => $avatar
        ];

        return $this->response->make(
            'minecraft',
            $id,
            function (Registration $registration) use ($payload) {
                $registration
                    ->provideTrustedEmail($payload['username'] . '+minecraft@machine.local')
                    ->suggestUsername($payload['username'])
                    ->provideAvatar($payload['avatar'])
                    ->setPayload($payload);
            }
        );
    }
}
