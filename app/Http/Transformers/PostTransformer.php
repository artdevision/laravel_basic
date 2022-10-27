<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

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
