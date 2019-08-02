<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Specification;

use App\Domain\Common\Specification\SpecificationInterface;
use Doctrine\ORM\Query\Expr;

class OrmAndSpecification extends OrmSpecification
{
    /** @var SpecificationInterface */
    private $left;

    /** @var SpecificationInterface */
    private $right;


    public function __construct(
        Expr $expr,
        SpecificationInterface $left,
        SpecificationInterface $right
    ) {
        $this->left  = $left;
        $this->right = $right;

        $this->addParameters($left);
        $this->addParameters($right);

        parent::__construct($expr);
    }


    public function getConditions()
    {
        return $this->expr->andX(
            $this->left->getConditions(),
            $this->right->getConditions()
        );
    }
}
