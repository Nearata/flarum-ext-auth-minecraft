<?php

namespace Nearata\AuthMinecraft\Api\Controller;

use Flarum\Forum\Auth\Registration;
use Flarum\Foundation\ValidationException;
use Flarum\User\LoginProvider;
use Flarum\User\User;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Nearata\AuthMinecraft\CustomValidator;
use Nearata\AuthMinecraft\Forum\Auth\CustomResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MinecraftAuthController implements RequestHandlerInterface
{
    protected $response;
    protected $validator;
    protected $translator;
    protected $loginProvider;

    public function __construct(CustomResponseFactory $response, CustomValidator $validator, TranslatorInterface $translator, LoginProvider $loginProvider)
    {
        $this->response = $response;
        $this->validator = $validator;
        $this->translator = $translator;
        $this->loginProvider = $loginProvider;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $email = Arr::get($body, 'email');
        $token = Arr::get($body, 'token');

        $this->validator->assertValid([
            'email' => $email,
            'token' => $token
        ]);

        $userExists = User::query()->where('email', $email)->exists();
        $providerExists = $this->loginProvider
            ->query()
            ->where('provider', 'minecraft')
            ->where('identifier', $email)
            ->exists();

        if ($userExists && !$providerExists) {
            throw new ValidationException(['auth' => $this->translator->trans('nearata-auth-minecraft.api.email_used')]);
        }

        $response = (new Factory)
            ->withHeaders([
                "token" => $token
            ])
            ->get('https://mc-oauth.net/api/api');

        $statusCode = $response->status();

        if ($statusCode !== 200) {
            return new EmptyResponse($statusCode);
        }

        $json = $response->json();

        $payload = [
            'email' => $email,
            'username' => $json['username'],
            'avatar' => 'https://crafatar.com/avatars/'.$json['uuid']
        ];

        return $this->response->make(
            'minecraft',
            $email,
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
