<?php

namespace Blog\Models;

use Blog\Casts\FormattedDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property $id - Идентификатор
 * @property $parent - Родительский раздел
 * @property $name - Название раздела
 * @property $code - Символьный код раздела
 * @property $text - Текст с описанием
 * @property $keywords - Ключевые слова
 * @property $description - Описание
 * @property $created_at - Дата создания
 * @property $updated_at - Дата изменения
 */
class BlogSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent',
        'name',
        'code',
        'text',
        'keywords',
        'description',
    ];

    protected $casts = [
        'parent'     => 'integer',
        'created_at' => FormattedDate::class,
        'updated_at' => FormattedDate::class,
    ];
}
