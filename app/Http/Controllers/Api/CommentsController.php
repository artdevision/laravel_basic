<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\HasNoAccessException;
use Exception;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\CreateCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Http\Transformers\CommentTransformer;
use App\Services\CommentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CommentsController extends ApiController
{
    protected string $transformerClass = CommentTransformer::class;

    protected CommentService $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    public function getAllByPost(int $post_id): JsonResponse|Response
    {
        return $this->respond($this->service->getAllByPost($post_id));
    }

    public function create(int $post_id, CreateCommentRequest $request): JsonResponse|Response
    {
        $model = $this->service->create(array_merge($request->validated(), ['post_id' => $post_id]));

        return $this->respond($model);
    }

    public function update(int $id, UpdateCommentRequest $request): JsonResponse|Response
    {
        try {
            $model = $this->service->update($id, $request->validated());

            return $this->respond($model);
        } catch (HasNoAccessException|ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }

    public function delete(int $id): JsonResponse|Response
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
