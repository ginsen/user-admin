<?php

declare(strict_types=1);

namespace App\Infrastructure\Tests\Unit\User\Repository;

use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use App\Infrastructure\User\Projection\UserView;
use App\Infrastructure\User\Repository\UserInMemoryReadModel;
use App\Infrastructure\User\Specification\Factory\CollectionUserSpecificationFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserInMemoryRepositoryTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_one_user_view_by_uuid()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory   = new CollectionUserSpecificationFactory();
        $specification = $specFactory->createForFindOneWithUuid($userView->getUuid());
        $item = $readModel->getOneOrNull($specification);

        self::assertInstanceOf(UserView::class, $item);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_null_when_uuid_not_exist()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory   = new CollectionUserSpecificationFactory();
        $specification = $specFactory->createForFindOneWithUuid(Uuid::uuid4());
        $item = $readModel->getOneOrNull($specification);

        self::assertNull($item);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_one_user_view_by_email()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory   = new CollectionUserSpecificationFactory();
        $specification = $specFactory->createForFindOneWithEmail($userView->getEmail());
        $item = $readModel->getOneOrNull($specification);

        self::assertEquals($userView, $item);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_null_when_email_not_exist()
    {
        $userView  = $this->createUserView();
        $readModel = new UserInMemoryReadModel();
        $readModel->save($userView);

        $specFactory   = new CollectionUserSpecificationFactory();
        $specification = $specFactory->createForFindOneWithEmail(Email::fromStr('notexist@test.com'));
        $item = $readModel->getOneOrNull($specification);

        self::assertNull($item);
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