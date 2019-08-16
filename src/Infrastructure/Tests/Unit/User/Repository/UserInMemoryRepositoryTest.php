<?php

declare(strict_types=1);

namespace App\Infrastructure\Tests\Unit\User\Repository;

use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use App\Infrastructure\User\Projection\UserView;
use App\Infrastructure\User\Repository\UserInMemoryRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserInMemoryRepositoryTest extends KernelTestCase
{
    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        static::bootKernel();
        $this->specFactory =
            static::$container->get('App\Infrastructure\User\Specification\Factory\CollectionUserSpecificationFactory');
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_one_user_view()
    {
        $repo     = new UserInMemoryRepository();
        $userView = $this->createUserView();
        $repo->save($userView);

        $specification = $this->specFactory->createForFindOneWithUuid($userView->getUuid());
        $item = $repo->getOneOrNull($specification);

        self::assertInstanceOf(UserView::class, $item);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_null_when_not_exist()
    {
        $repo     = new UserInMemoryRepository();
        $userView = $this->createUserView();
        $repo->save($userView);

        $specification = $this->specFactory->createForFindOneWithUuid(Uuid::uuid4());
        $item = $repo->getOneOrNull($specification);

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