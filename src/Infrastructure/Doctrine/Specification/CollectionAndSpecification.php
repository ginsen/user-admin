<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Specification;

use App\Domain\Common\Specification\SpecificationInterface;
use Doctrine\Common\Collections\ExpressionBuilder;

class CollectionAndSpecification extends CollectionSpecification
{
    /** @var SpecificationInterface */
    private $left;

    /** @var SpecificationInterface */
    private $right;


    public function __construct(
        ExpressionBuilder $expr,
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
