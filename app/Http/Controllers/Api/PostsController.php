<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as SWG;
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

    /**
     * @SWG\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Get Post by Id",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Post Id",
     *          @SWG\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/PostData")
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Model not found"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function index(int $id): JsonResponse|Response
    {
        try {
            return $this->respond($this->service->getOne($id));
        } catch (ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="List of posts",
     *     security={{"Bearer":{}}},
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/PostCollection")
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Model not found"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->respond($this->service->getAll());
    }

    /**
     * @SWG\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create Post",
     *     security={{"Bearer":{}}},
     *     @SWG\RequestBody(
     *          required=true,
     *          @SWG\MediaType(
     *              mediaType="application/json",
     *              @SWG\Schema (
     *                  type="object",
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="content", type="string")
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/PostData")
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Model not found"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     * @param CreatePostRequest $request
     * @return JsonResponse|Response
     */
    public function create(CreatePostRequest $request): JsonResponse|Response
    {
        $model = $this->service->create($request->validated());

        return $this->respond($model);
    }

    /**
     * @SWG\Post(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update Post",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Post Id",
     *          @SWG\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @SWG\RequestBody(
     *          required=true,
     *          @SWG\MediaType(
     *              mediaType="application/json",
     *              @SWG\Schema (
     *                  type="object",
     *                  @SWG\Property(property="title", type="string"),
     *                  @SWG\Property(property="content", type="string")
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/PostData")
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Model not found"
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          description="User has now permissions to edit entity"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     * @param int $id
     * @param UpdatePostRequest $request
     * @return JsonResponse|Response
     */
    public function update(int $id, UpdatePostRequest $request): JsonResponse|Response
    {
        try {
            $model = $this->service->update($id, $request->validated());

            return $this->respond($model);
        } catch (HasNoAccessException|ModelNotFoundException $e) {
            return $this->catchException($e);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Delete Post",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Post Id",
     *          @SWG\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="success"
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Model not found"
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          description="User has now permissions to edit entity"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Not authorized request"
     *     )
     * )
     *
     * @param int $id
     * @return Response|JsonResponse
     */
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
