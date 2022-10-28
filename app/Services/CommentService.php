<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\HasNoAccessException;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CommentService extends AbstractService
{
    protected ?string $modelClass = Comment::class;

    public function getAllByPost(int $post_id): LengthAwarePaginator
    {
        return $this->getAllByCriteria($this->modelClass::where(['post_id' => $post_id]));
    }

    public function create(array $attributes): Model
    {
        return $this->save(attributes: array_merge($attributes, ['author_id' => auth()->id()]));
    }

    /**
     * @throws HasNoAccessException
     */
    public function update(int $id, array $attributes): Model
    {
        /** @var Comment $post */
        $model = $this->findOne($id);

        if ($model === null) {
            throw new ModelNotFoundException();
        }

        if ($model->author_id === (int) auth()->id()) {
            return $this->save($id, $attributes);
        }

        throw new HasNoAccessException();
    }

    /**
     * @throws HasNoAccessException
     */
    public function delete(int $id): bool
    {
        /** @var Comment $post */
        $model = $this->findOne($id);

        if ($model === null) {
            throw new ModelNotFoundException();
        }

        if ($model->author_id === (int) auth()->id()) {
            return $model->delete();
        }

        throw new HasNoAccessException();
    }
}
