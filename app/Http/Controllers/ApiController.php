<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Annotations as SWG;
use App\Exceptions\HasNoAccessException;
use App\Http\Transformers\ModelTransformer;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection as BaseCollection;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use function abort;
use function app;
use function response;

/**
 * @SWG\Swagger(
 *   schemes={"http"},
 *   host="localhost:8880",
 *   basePath="/api",
 *   @SWG\Info(
 *     title="Blog posts API",
 *     version="1.0.0"
 *   )
 * )
 *
 * @SWG\Schema (
 *     schema="Meta",
 *     type="object",
 *     @SWG\Property(
 *          property="pagination",
 *          type="object",
 *          @SWG\Property(property="total", type="integer"),
 *          @SWG\Property(property="count", type="integer"),
 *          @SWG\Property(property="per_page", type="integer"),
 *          @SWG\Property(property="current_page", type="integer"),
 *          @SWG\Property(property="total_pages", type="integer"),
 *          @SWG\Property(property="links", type="array", @SWG\Items(type="string"))
 *     )
 * )
 */
class ApiController extends Controller
{
    private Manager $manager;

    protected string $transformerClass = ModelTransformer::class;

    public function __construct()
    {
        $this->manager = app(Manager::class);
    }

    protected function respond($data = null): JsonResponse|Response
    {
        if ($data === null) {
            return response()->noContent();
        }

        if ($data instanceof Model) {
            return response()
                ->json(
                    $this->manager->createData(
                        new Item($data, app($this->transformerClass))
                    )->toArray()
                );
        }

        if ($data instanceof LengthAwarePaginator) {
            return response()
                ->json(
                    $this->manager->createData(
                        (new Collection($data->getCollection(), app($this->transformerClass)))
                            ->setPaginator(new IlluminatePaginatorAdapter($data))
                    )->toArray()
                );
        }

        if ($data instanceof BaseCollection) {
            return response()
                ->json(
                    $this->manager->createData(
                        new Collection($data, app($this->transformerClass))
                    )->toArray()
                );
        }

        return abort(406, 'Not supported content');
    }

    protected function catchException(Exception $exception): Response
    {
        $statusCode = match($exception::class) {
            HasNoAccessException::class => 403,
            ModelNotFoundException::class => 404,
            default => 500,
        };

        return abort($statusCode, $exception->getMessage());
    }
}
