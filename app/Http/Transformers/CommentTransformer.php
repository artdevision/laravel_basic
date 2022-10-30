<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use OpenApi\Annotations as SWG;
use App\Models\Comment;
use League\Fractal\TransformerAbstract;

/**
 * @SWG\Schema (
 *     schema="Comment",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="author_id", type="integer"),
 *     @SWG\Property(property="post_id", type="integer"),
 *     @SWG\Property(property="comment", type="string"),
 *     @SWG\Property(property="is_owner", type="boolean")
 * )
 *
 * @SWG\Schema (
 *     schema="CommentsCollection",
 *     type="object",
 *     @SWG\Property(
 *         property="data",
 *         type="array",
 *         @SWG\Items(ref="#/components/schemas/Comment")
 *     ),
 *     @SWG\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 *
 * @SWG\Schema (
 *     schema="CommentData",
 *     type="object",
 *     @SWG\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Comment"
 *     )
 * )
 */
final class CommentTransformer extends TransformerAbstract
{
    public function transform(Comment $model): array
    {
        return array_merge(
            $model->toArray(),
            ['is_owner' => $model->author_id === (int) auth()->id()]
        );
    }
}
