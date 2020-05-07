<?php

declare(strict_types=1);

use Blog\Actions\Article;
use Blog\Actions\Section;
use Johncms\System\i18n\Translator;

require_once __DIR__ . '/lib/HTMLPurifier/HTMLPurifier.auto.php';

// Register the module languages domain and folder
di(Translator::class)->addTranslationDomain('blog', __DIR__ . '/locale');

$route = di('route');

$loader = new Aura\Autoload\Loader();
$loader->register();
$loader->addPrefix('Blog', __DIR__ . '/lib');

if (! empty($route['article'])) {
    // Если запросили страницу статьи, открываем её
    (new Article())->index();
} else {
    // Страница просмотра раздела
    (new Section())->index();
}
