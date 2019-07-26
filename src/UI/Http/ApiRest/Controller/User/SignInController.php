<?php

declare(strict_types=1);

namespace App\UI\Http\ApiRest\Controller\User;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Application\Query\User\JwtToken\GetTokenQuery;
use App\UI\Http\ApiRest\Controller\CommandQueryController;
use Assert\Assertion;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends CommandQueryController
{
    /**
     * @param Request $request
     *
     * @Route("/user/sign-in", methods={"POST"},
     *     name="api_user_sign-in",
     *     requirements={
     *      "username": "\w+",
     *      "password": "\w+"
     *     }
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Login success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials"
     * )
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="password", type="string"),
     *         @SWG\Property(property="username", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Login User")
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $params   = json_decode($request->getContent(), true);
        $username = $params['username'];
        $password = $params['password'];

        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand($username, $password);
        $this->handleCommand($signInCommand);

        $getTokenQuery = new GetTokenQuery($username);

        return JsonResponse::create([
            'token' => $this->handleQuery($getTokenQuery),
        ]);
    }
}
