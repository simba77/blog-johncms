<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogArticle;
use Blog\Utils\AbstractAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Vote extends AbstractAction
{
    /**
     * Add vote
     */
    public function add(): void
    {
        $article_id = $this->request->getQuery('article_id', 0, FILTER_VALIDATE_INT);
        try {
            $current_article = (new BlogArticle())->findOrFail($article_id);
        } catch (ModelNotFoundException $exception) {
            pageNotFound();
        }
    }
}
