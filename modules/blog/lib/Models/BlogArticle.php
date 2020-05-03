<?php

namespace Blog\Models;

use Blog\Casts\FormattedDate;
use Blog\Casts\SpecialChars;
use Blog\Utils\SectionPathCache;
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
 * @property $preview_text - Краткое описание статьи в списке
 * @property $text - Текст с описанием
 * @property $view_count - Количество просмотров
 * @property $keywords - Ключевые слова
 * @property $description - Описание
 * @property $created_at - Дата создания
 * @property $updated_at - Дата изменения
 * @property $created_by - Автор
 * @property $updated_by - Пользователь, изменивший запись
 *
 * @property BlogSection $parentSection - Родительский раздел
 * @property $url - URL адрес страницы просмотра статьи
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
        'preview_text',
        'text',
        'view_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'parent'      => 'integer',
        'view_count'  => 'integer',
        'name'        => SpecialChars::class,
        'page_title'  => SpecialChars::class,
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
        return $this->hasOne(BlogSection::class, 'id', 'section_id');
    }

    /**
     * Returns the url of the section page
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        $url = '';
        if (! empty($this->section_id)) {
            /** @var SectionPathCache $cache */
            $cache = di(SectionPathCache::class);
            $url = $cache->getSectionPath($this->section_id) . '/';
        }
        return '/blog/' . $url . $this->code . '.html';
    }
}
