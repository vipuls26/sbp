<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['name', 'created_at', 'updated_at'])]
class Role extends Model
{
    //
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }


    #[Scope]
    protected function notAdminRole(Builder $query): void
    {
        $query->where('name' , '!=' , 'admin');
    }
}
