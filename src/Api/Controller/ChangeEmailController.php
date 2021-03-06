<?php

namespace Nearata\AuthMinecraft\Api\Controller;

use Flarum\Api\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laminas\Diactoros\Response\EmptyResponse;
use Nearata\AuthMinecraft\Validator\ChangeEmailValidator;
use Nearata\AuthMinecraft\TokenHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeEmailController implements RequestHandlerInterface
{
    protected $validator;
    protected $api;

    public function __construct(ChangeEmailValidator $validator, Client $api)
    {
        $this->validator = $validator;
        $this->api = $api;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var \Flarum\User\User
         */
        $actor = $request->getAttribute('actor');
        $body = $request->getParsedBody();
        $email = Arr::get($body, 'email');
        $token = Arr::get($body, 'token');

        $this->validator->assertValid([
            'email' => $email,
            'token' => $token
        ]);

        if (!Str::endsWith($actor->email, 'auth-minecraft.net')) {
            return new EmptyResponse(401);
        }

        $response = TokenHelper::validate($token);
        $status = $response->status();

        if ($status !== 200) {
            return new EmptyResponse($status);
        }

        $provider = $actor->loginProviders()->getQuery()
            ->where('provider', 'minecraft')
            ->where('identifier', $response->json('uuid'))
            ->where('user_id', $actor->id);

        if (!$provider->exists()) {
            return new EmptyResponse(403);
        }

        $actor->is_email_confirmed = false;
        $actor->email = $email;
        $actor->save();

        $response = $this->api->withParentRequest($request)->post("/users/$actor->id/send-confirmation");

        return new EmptyResponse(200);
    }
}
