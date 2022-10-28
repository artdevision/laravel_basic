<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use OpenApi\Annotations as SWG;
use App\Models\Post;
use League\Fractal\TransformerAbstract;

/**
 * @SWG\Schema (
 *     schema="Post",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="author_id", type="integer"),
 *     @SWG\Property(property="title", type="string"),
 *     @SWG\Property(property="content", type="string"),
 *     @SWG\Property(property="is_owner", type="boolean")
 * )
 *
 * @SWG\Schema (
 *     schema="PostCollection",
 *     type="object",
 *     @SWG\Property(
 *         property="data",
 *         type="array",
 *         @SWG\Items(ref="#/components/schemas/Post")
 *     ),
 *     @SWG\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 *
 * @SWG\Schema (
 *     schema="PostData",
 *     type="object",
 *     @SWG\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Post"
 *     )
 * )
 */
final class PostTransformer extends TransformerAbstract
{
    public function transform(Post $model): array
    {
        return array_merge(
            $model->toArray(),
            ['is_owner' => $model->author_id === (int) auth()->id()]
        );
    }
}
