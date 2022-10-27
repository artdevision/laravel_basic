<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

final class ModelTransformer extends TransformerAbstract
{
    public function transform(Model $model): array
    {
        return $model->toArray();
    }
}
