<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    public function transform(User $model): array
    {
        return $model->toArray();
    }
}
