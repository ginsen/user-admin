# Creating a new Projection

A Projection is a representation of a stream of events (aggregates) into a structural representation, usually called,
read model.

#### Infrastructure implementation

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserView implements UserViewInterface
{
    /** @var UuidInterface */
    protected $uuid;

    /** @var Credentials */
    protected $credentials;

    /** @var bool */
    protected $active;

    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime|null */
    protected $updatedAt;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->uuid->toString();
    }


    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }


    /**
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }


    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }


    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->credentials->email;
    }


    /**
     * @param Email $email
     */
    public function setEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }


    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->credentials->password;
    }


    /**
     * @param Password $password
     */
    public function setPassword(Password $password): void
    {
        $this->credentials->password = $password;
    }


    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }


    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }


    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }


    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * @param  Serializable $event
     * @throws \Exception
     * @return UserView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }


    /**
     * @param  array      $data
     * @throws \Exception
     * @return UserView
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();

        $instance->uuid        = Uuid::fromString($data['uuid']);
        $instance->credentials = new Credentials(
            Email::fromStr($data['email']),
            Password::fromHash($data['password'] ?? '')
        );

        $instance->active    = $data['active'];
        $instance->createdAt = DateTime::fromStr($data['created_at']);
        $instance->updatedAt = isset($data['updated_at']) ? DateTime::fromStr($data['updated_at']) : null;

        return $instance;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'        => $this->getId(),
            'credentials' => [
                'email' => (string) $this->credentials->email,
            ],
            'active' => $this->active,
        ];
    }
}
```

> Then you need to implement the Infrastructure for this. Something like `App\Infrastructure\User\Projection\UserProjectionFactory`

#### Create the Projector Listener

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Repository\UserReadModelInterface;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    /** @var UserReadModelInterface */
    private $readModel;

    public function __construct(UserReadModelInterface $userReadModel)
    {
        $this->readModel = $userReadModel;
    }


    /**
     * @param  UserWasCreated $event
     * @throws \Exception
     */
    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $userView = UserView::fromSerializable($event);
        $this->readModel->save($userView);
    }
}
```

And you're done. 

### Why this works?

`Broadway\ReadModel\Projector` implements `Broadway\EventHandling\EventListener` so it's automatically added to the
service container and tagged as a Broadway event listener.

`config/services.yaml`
```yaml

services:
    ...
    _instanceof:
        ...
        Broadway\EventHandling\EventListener:
          public: true
          tags:
              - { name: broadway.domain.event_listener }
```
The `Broadway/EventSourcing/EventSourcingRepository::save` method will store the events in the EventStore and publish
all the events in the event bus: 

```php
<?php
...
	public function save(AggregateRoot $aggregate): void
	{
	    // maybe we can get generics one day.... ;)
	    Assert::isInstanceOf($aggregate, $this->aggregateClass);
	    $domainEventStream = $aggregate->getUncommittedEvents();
	    $eventStream = $this->decorateForWrite($aggregate, $domainEventStream);
	    $this->eventStore->append($aggregate->getAggregateRootId(), $eventStream);
	    $this->eventBus->publish($eventStream);
	}
```

The projections are automatically added to the EventBus by the Compiler pass of `broadway-bundle`, 
[see here](https://github.com/broadway/broadway-bundle/blob/master/src/DependencyInjection/RegisterBusSubscribersCompilerPass.php#L66)
