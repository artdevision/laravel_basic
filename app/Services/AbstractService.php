<?php

namespace App\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractService
{
    protected ?string $modelClass = null;

    abstract public function create(array $attributes): Model;

    abstract public function update(int $id, array $attributes): Model;

    abstract public function delete(int $id): bool;

    protected function getAllByCriteria(Builder $query): LengthAwarePaginator
    {
        return $query->paginate();
    }

    protected function findOne(int $id): ?Model
    {
        return $this->modelClass::find($id);
    }

    protected function save(int $id = null, array $attributes = []): ?Model
    {
        /** @var Model $model */
        $model = ($id !== null) ? $this->modelClass::find($id) : new $this->modelClass();

        if ($model === null) {
            throw new ModelNotFoundException(sprintf('model %s[%d] not found', $this->modelClass, $id));
        }

        try {
            return ($model->fill($attributes)->save()) ? $model->refresh() : null;
        } catch (Exception $e) {

        }
    }
}
