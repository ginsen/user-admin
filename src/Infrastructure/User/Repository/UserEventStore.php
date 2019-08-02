<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserEventStoreInterface;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class UserEventStore extends EventSourcingRepository implements UserEventStoreInterface
{
    /**
     * UserStore constructor.
     * @param EventStore $eventStore
     * @param EventBus   $eventBus
     * @param array      $eventStreamDecorators
     */
    public function __construct(EventStore $eventStore, EventBus $eventBus, array $eventStreamDecorators = [])
    {
        parent::__construct(
            $eventStore,
            $eventBus,
            User::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }


    /**
     * @param User $user
     */
    public function store(User $user): void
    {
        $this->save($user);
    }


    /**
     * @param  UuidInterface $uuid
     * @return User
     */
    public function get(UuidInterface $uuid): User
    {
        /** @var User $user */
        $user = $this->load($uuid->toString());

        return $user;
    }
}
