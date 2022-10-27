<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

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

    public function list(): JsonResponse
    {
        return $this->respond($this->service->getAll());
    }
}
