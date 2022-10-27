<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserService
{
    protected ?string $modelClass = User::class;

    public function getAll(): LengthAwarePaginator
    {
        return $this->modelClass::paginate();
    }
}
