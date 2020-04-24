<?php

declare(strict_types=1);

use Blog\Actions\Article;
use Blog\Actions\Section;
use Johncms\NavChain;
use Johncms\System\Http\Request;
use Johncms\System\View\Render;
use Johncms\Users\User;

/** @var Render $view */
$view = di(Render::class);

/** @var Request $request */
$request = di(Request::class);

/** @var NavChain $nav_chain */
$nav_chain = di(NavChain::class);

/** @var User $user */
$user = di(User::class);

$route = di('route');

// Register Namespace for module templates
$view->addFolder('blog', __DIR__ . '/templates/');

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


/*

// Определяем что хочет открыть пользователь...
$segments = explode('/', $category);
d($segments);

$module_pages = [
    'add_sec',
];


$str = \Illuminate\Support\Str::slug('Говно');

d($str);

$str = \Illuminate\Support\Str::studly('test-test');
d($str);
$test = ucfirst('test_test');

d($test);

d($route);
*/


