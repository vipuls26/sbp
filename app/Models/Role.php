<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

#[Fillable(['name','created_at','updated_at'])]
class Role extends Model
{
    //
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}
