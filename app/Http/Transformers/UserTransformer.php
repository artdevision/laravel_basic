<?php
declare(strict_types=1);

namespace App\Http\Transformers;

use OpenApi\Annotations as SWG;
use App\Models\User;
use League\Fractal\TransformerAbstract;

/**
 * @SWG\Schema (
 *     schema="User",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="namw", type="string"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="created_at", type="string", format="date-time"),
 *     @SWG\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @SWG\Schema (
 *     schema="UserCollection",
 *     type="object",
 *     @SWG\Property(
 *         property="data",
 *         type="array",
 *         @SWG\Items(ref="#/components/schemas/User")
 *     ),
 *     @SWG\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 */
final class UserTransformer extends TransformerAbstract
{
    public function transform(User $model): array
    {
        return $model->toArray();
    }
}
