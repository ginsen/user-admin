<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Collection;

use App\Domain\User\ValueObj\Email;
use App\Infrastructure\Doctrine\Specification\CollectionSpecification;
use Doctrine\Common\Collections\ExpressionBuilder;

class UserWithEmail extends CollectionSpecification
{
    /** @var Email */
    private $email;

    public function __construct(ExpressionBuilder $expr, Email $email)
    {
        $this->email = $email;

        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->eq('user.credentials.email', $this->email);
    }
}
