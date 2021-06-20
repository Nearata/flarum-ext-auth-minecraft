<?php

namespace Nearata\AuthMinecraft\Api\Controller;

use Flarum\Forum\Auth\Registration;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Nearata\AuthMinecraft\Validator\CustomValidator;
use Nearata\AuthMinecraft\Forum\Auth\CustomResponseFactory;
use Nearata\AuthMinecraft\TokenHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MinecraftAuthController implements RequestHandlerInterface
{
    protected $response;
    protected $validator;

    public function __construct(CustomResponseFactory $response, CustomValidator $validator)
    {
        $this->response = $response;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $token = Arr::get($body, 'token');

        $this->validator->assertValid(['token' => $token]);

        $response = TokenHelper::validate($token);
        $statusCode = $response->status();

        if ($statusCode !== 200) {
            return new EmptyResponse($statusCode);
        }

        $json = $response->json();
        $uuid = $json['uuid'];

        $payload = [
            'email' => $uuid.'@auth-minecraft.net',
            'username' => $json['username'],
            'avatar' => 'https://crafatar.com/avatars/'.$uuid
        ];

        return $this->response->make(
            'minecraft',
            $uuid,
            function (Registration $registration) use ($payload) {
                $registration
                    ->provideTrustedEmail($payload['email'])
                    ->suggestUsername($payload['username'])
                    ->provideAvatar($payload['avatar'])
                    ->setPayload($payload);
            }
        );
    }
}
