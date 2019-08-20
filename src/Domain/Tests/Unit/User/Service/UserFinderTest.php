<?php

declare(strict_types=1);

namespace App\Domain\Tests\Unit\User\Service;

use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use App\Infrastructure\User\Projection\UserView;
use App\Infrastructure\User\Repository\UserInMemoryReadModel;
use App\Infrastructure\User\Specification\Factory\CollectionUserSpecificationFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserFinderTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_should_find_by_uuid()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory = new CollectionUserSpecificationFactory();

        $finder = new UserFinder($readModel, $specFactory);

        $item = $finder->findByUuid($userView->getUuid());
        self::assertEquals($userView, $item);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_find_by_email()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory = new CollectionUserSpecificationFactory();

        $finder = new UserFinder($readModel, $specFactory);

        $item = $finder->findByEmail($userView->getEmail());
        self::assertEquals($userView, $item);
    }


    /**
     * @return UserView
     * @throws \Exception
     */
    protected function createUserView(): UserView
    {
        $uuid      = Uuid::uuid4();
        $email     = Email::fromStr('test@test.com');
        $password  = Password::encode('123456');
        $createdAt = DateTime::now();

        $userView = UserView::deserialize([
            'uuid'       => $uuid->toString(),
            'email'      => $email->toStr(),
            'password'   => $password->toStr(),
            'active'     => true,
            'created_at' => $createdAt->toStr(),
        ]);

        return $userView;
    }
}
