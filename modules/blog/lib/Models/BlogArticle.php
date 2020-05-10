<?php

namespace Blog\Models;

use Blog\Casts\FormattedDate;
use Blog\Casts\SpecialChars;
use Blog\Utils\Helpers;
use Blog\Utils\SectionPathCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property $preview_text_safe - Краткое описание статьи в списке в безопасном виде
 * @property $text - Текст с описанием
 * @property $text_safe - Текст с описанием в безопасном виде
 * @property $view_count - Количество просмотров
 * @property $keywords - Ключевые слова
 * @property $description - Описание
 * @property $created_at - Дата создания
 * @property $updated_at - Дата изменения
 * @property $created_by - Автор
 * @property $updated_by - Пользователь, изменивший запись
 *
 * @property BlogSection $parentSection - Родительский раздел
 * @property BlogVote $votes - Votes for the article
 * @property $url - URL адрес страницы просмотра статьи
 * @property $meta_title
 * @property $meta_keywords
 * @property $meta_description
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
        'section_id'  => 'integer',
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

    /**
     * Returns the url of the section page
     *
     * @return string
     */
    public function getTextSafeAttribute(): string
    {
        return Helpers::purifyHtml($this->text);
    }

    /**
     * Returns the url of the section page
     *
     * @return string
     */
    public function getPreviewTextSafeAttribute(): string
    {
        return Helpers::purifyHtml($this->preview_text);
    }

    /**
     * Meta title
     *
     * @return string|string[]
     */
    public function getMetaTitleAttribute()
    {
        if (! empty($this->page_title)) {
            return $this->page_title;
        }
        $config = di('config')['blog'];
        return ! empty($config['article_title']) ? str_replace('#article_name#', $this->name, $config['article_title']) : $this->name;
    }

    /**
     * Meta keywords
     *
     * @return string|string[]
     */
    public function getMetaKeywordsAttribute()
    {
        if (! empty($this->keywords)) {
            return $this->keywords;
        }
        $config = di('config')['blog'];
        return str_replace('#article_name#', $this->name, $config['article_meta_keywords']);
    }

    /**
     * Meta description
     *
     * @return string|string[]
     */
    public function getMetaDescriptionAttribute()
    {
        if (! empty($this->description)) {
            return $this->description;
        }
        $config = di('config')['blog'];
        return str_replace('#article_name#', $this->name, $config['article_meta_description']);
    }

    /**
     * Votes
     *
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(BlogVote::class, 'article_id', 'id');
    }
}
