<?php

declare(strict_types=1);

namespace App\Application\Query\User\JwtToken;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\User\Service\UserFinderInterface;
use App\Infrastructure\Doctrine\User\Authentication\UserAuthenticationJwtProvider;

class GetTokenHandler implements QueryHandlerInterface
{
    /** @var UserFinderInterface */
    private $userFinder;

    /** @var UserAuthenticationJwtProvider */
    private $authProvider;


    public function __construct(
        UserFinderInterface $userFinder,
        UserAuthenticationJwtProvider $authProvider
    ) {
        $this->userFinder   = $userFinder;
        $this->authProvider = $authProvider;
    }


    public function __invoke(GetTokenQuery $command): string
    {
        $user = $this->userFinder->findByEmail($command->email);

        return $this->authProvider->generateToken(
            $user->getUuid(),
            $user->getCredentials()
        );
    }
}
