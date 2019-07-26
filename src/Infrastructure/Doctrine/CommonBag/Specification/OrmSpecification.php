<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\CommonBag\Specification;

use App\Domain\Common\Specification\SpecificationInterface;
use Doctrine\ORM\Query\Expr;

abstract class OrmSpecification implements SpecificationInterface
{
    /** @var Expr */
    protected $expr;

    /** @var array */
    protected $parameters = [];

    /** @var array */
    protected $types = [];

    public function __construct(Expr $expr)
    {
        $this->expr = $expr;
    }


    abstract public function getConditions();


    public function getParameters(): array
    {
        return $this->parameters;
    }


    public function getTypes(): array
    {
        return $this->types;
    }


    public function andX(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrmAndSpecification($this->expr, $this, $specification);
    }


    public function orX(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrmOrSpecification($this->expr, $this, $specification);
    }


    protected function addParameters(SpecificationInterface $specification): void
    {
        $types = $specification->getTypes();
        foreach ($specification->getParameters() as $key => $value) {
            $this->setParameter($key, $value, $types[$key]);
        }
    }


    protected function setParameter(string $key, $value, string $type = null): void
    {
        $this->parameters[$key] = $value;
        $this->types[$key]      = $type;
    }
}
