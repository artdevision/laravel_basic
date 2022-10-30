<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as SWG;
use App\Http\Controllers\ApiController;
use App\Http\Transformers\UserTransformer;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

final class UsersController extends ApiController
{
    protected string $transformerClass = UserTransformer::class;

    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * @SWG\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get Users list",
     *     security={{"Bearer":{}}},
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/UserCollection")
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->respond($this->service->getAll());
    }
}
