<?php

declare(strict_types=1);

namespace App\UI\Http\ApiRest\Controller\User;

use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Application\Query\User\JwtToken\GetTokenQuery;
use App\Infrastructure\Exception\Session\ForbiddenException;
use App\Infrastructure\User\Authentication\Session;
use App\UI\Http\ApiRest\Controller\CommandQueryController;
use Assert\Assertion;
use League\Tactician\CommandBus;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserChangeEmailController extends CommandQueryController
{
    /** @var Session */
    private $session;


    /**
     * UserChangeEmailController constructor.
     * @param Session    $session
     * @param CommandBus $commandBus
     * @param CommandBus $queryBus
     */
    public function __construct(Session $session, CommandBus $commandBus, CommandBus $queryBus)
    {
        $this->session = $session;
        parent::__construct($commandBus, $queryBus);
    }


    /**
     * @Route(
     *     "/user/{uuid}/email",
     *     name="api_user_change_email",
     *     methods={"PUT"}
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Email changed"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="change-email",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     * @SWG\Parameter(
     *     name="uuid",
     *     type="string",
     *     in="path"
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @param  string       $uuid
     * @param  Request      $request
     * @return JsonResponse
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $this->validateUuid($uuid);

        $params = json_decode($request->getContent(), true);
        $email  = $params['email'];

        Assertion::notNull($email, "Email can\'t be null");

        $command = new ChangeEmailCommand($uuid, $email);
        $this->handleCommand($command);

        $getTokenQuery = new GetTokenQuery($email);

        return JsonResponse::create([
            'new_email'  => $email,
            'new_token' => $this->handleQuery($getTokenQuery),
        ]);
    }


    /**
     * @param string $uuid
     */
    private function validateUuid(string $uuid): void
    {
        if (!$this->session->sameByUuid($uuid)) {
            throw new ForbiddenException();
        }
    }
}
