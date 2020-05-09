<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 *
 * @property $id - Идентификатор
 */
class BlogVote extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'vote',
    ];

    protected $casts = [
        'article_id' => 'integer',
        'user_id'    => 'integer',
        'vote'       => 'integer',
    ];
}
