<?php

declare(strict_types=1);

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

$module_path = '/blog';

$category = $route['category'] ?? 'index';

if ($category === 'i' && $user->rights >= 9 && is_file(__DIR__ . '/install.php')) {
    require 'install.php';
    exit;
}



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



if (! empty($route['article'])) {
    (new \Blog\Actions\Article())->index();
}
