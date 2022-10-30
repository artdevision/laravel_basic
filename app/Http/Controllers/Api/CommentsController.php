<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as SWG;
use App\Exceptions\HasNoAccessException;
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

    /**
     * @SWG\Get(
     *     path="/api/posts/{id}/comments",
     *     tags={"Comments"},
     *     summary="List of posts",
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
     *          @SWG\JsonContent(ref="#/components/schemas/CommentsCollection")
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
     * @param int $post_id
     * @return JsonResponse|Response
     */
    public function getAllByPost(int $post_id): JsonResponse|Response
    {
        return $this->respond($this->service->getAllByPost($post_id));
    }

    /**
     * @SWG\Post(
     *     path="/api/posts/{id}/comments",
     *     tags={"Comments"},
     *     summary="Create Post",
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
     *                  @SWG\Property(property="comment", type="string")
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/CommentData")
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
     * @param int $post_id
     * @param CreateCommentRequest $request
     * @return JsonResponse|Response
     */
    public function create(int $post_id, CreateCommentRequest $request): JsonResponse|Response
    {
        $model = $this->service->create(array_merge($request->validated(), ['post_id' => $post_id]));

        return $this->respond($model);
    }

    /**
     * @SWG\Post(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Update Post",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Comment Id",
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
     *                  @SWG\Property(property="comment", type="string")
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="success",
     *          @SWG\JsonContent(ref="#/components/schemas/CommentData")
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
     * @param UpdateCommentRequest $request
     * @return JsonResponse|Response
     */
    public function update(int $id, UpdateCommentRequest $request): JsonResponse|Response
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
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Delete Comment",
     *     security={{"Bearer":{}}},
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Comment Id",
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
     * @return JsonResponse|Response
     */
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
