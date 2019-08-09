<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Application\Query\Item;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\User\Service\UserFinder;

class FindByEmailHandler implements QueryHandlerInterface
{
    /** @var UserFinder */
    private $userFinder;


    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }


    public function __invoke(FindByEmailQuery $command)
    {
        $user = $this->userFinder->findByEmail($command->email);

        return new Item($user);
    }
}
