<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

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
