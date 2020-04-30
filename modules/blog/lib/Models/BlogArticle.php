<?php

namespace Blog\Models;

use Blog\Casts\FormattedDate;
use Blog\Casts\SpecialChars;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property $id - Идентификатор
 * @property $section_id - Родительский раздел
 * @property $name - Название
 * @property $page_title - Заголовок страницы
 * @property $code - Символьный код
 * @property $text - Текст с описанием
 * @property $keywords - Ключевые слова
 * @property $description - Описание
 * @property $created_at - Дата создания
 * @property $updated_at - Дата изменения
 * @property $created_by - Автор
 * @property $updated_by - Пользователь, изменивший запись
 *
 * @property BlogSection $parentSection - Родительский раздел
 */
class BlogArticle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'section_id',
        'name',
        'page_title',
        'code',
        'keywords',
        'description',
        'text',
        'view_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'parent'      => 'integer',
        'name'        => SpecialChars::class,
        'keywords'    => SpecialChars::class,
        'description' => SpecialChars::class,
        'created_at'  => FormattedDate::class,
        'updated_at'  => FormattedDate::class,
    ];

    /**
     * @return HasOne
     */
    public function parentSection(): HasOne
    {
        return $this->hasOne(BlogSection::class, 'id', 'parent');
    }
}
