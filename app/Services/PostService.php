<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\HasNoAccessException;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PostService extends AbstractService
{
    protected ?string $modelClass = Post::class;

    public function getOne(int $id): Model
    {
        $post = $this->findOne($id);

        if ($post === null) {
            throw new ModelNotFoundException();
        }

        return $post;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->getAllByCriteria($this->modelClass::select('*'));
    }

    public function create(array $attributes): Model
    {
        return $this->save(attributes: array_merge($attributes, ['author_id' => auth()->id()]));
    }

    /**
     * @throws HasNoAccessException
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $attributes): Model
    {
        /** @var Post $post */
        $post = $this->findOne($id);

        if ($post === null) {
            throw new ModelNotFoundException();
        }

        if ($post->author_id === (int) auth()->id()) {
            return $this->save($id, $attributes);
        }

        throw new HasNoAccessException();
    }

    /**
     * @throws HasNoAccessException
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        /** @var Post $post */
        $post = $this->findOne($id);

        if ($post === null) {
            throw new ModelNotFoundException();
        }

        if ($post->author_id === (int) auth()->id()) {
            return $post->delete();
        }

        throw new HasNoAccessException();
    }
}
