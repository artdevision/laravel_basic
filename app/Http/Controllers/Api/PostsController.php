<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\HasNoAccessException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\CreatePostRequest;
use App\Http\Requests\Api\UpdatePostRequest;
use App\Http\Transformers\PostTransformer;
use App\Services\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class PostsController extends ApiController
{
    protected string $transformerClass = PostTransformer::class;

    protected PostService $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    public function index(int $id): JsonResponse|Response
    {
        try {
            return $this->respond($this->service->getOne($id));
        } catch (ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }

    public function list(): JsonResponse
    {
        return $this->respond($this->service->getAll());
    }

    public function create(CreatePostRequest $request): JsonResponse|Response
    {
        $model = $this->service->create($request->validated());

        return $this->respond($model);
    }

    public function update(int $id, UpdatePostRequest $request): JsonResponse|Response
    {
        try {
            $model = $this->service->update($id, $request->validated());

            return $this->respond($model);
        } catch (HasNoAccessException|ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }

    public function delete(int $id): Response|JsonResponse
    {
        try {
            if ($this->service->delete($id)) {
                return $this->respond();
            }
        } catch (HasNoAccessException|ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }
}
