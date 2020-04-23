<?php

declare(strict_types=1);

namespace Blog\Utils;

use Johncms\NavChain;
use Johncms\System\Http\Request;
use Johncms\System\View\Render;
use Johncms\Users\User;

abstract class AbstractAction
{
    /** @var NavChain $nav_chain Навигационная цепочка */
    protected $nav_chain;

    /** @var Render $render Шаблонизатор */
    protected $render;

    /** @var Request $request Запрос */
    protected $request;

    /** @var User $user Текущий пользователь */
    protected $user;

    /** @var string Название модуля */
    protected $module_name;

    /** @var string Базовый URL */
    protected $base_url;

    /** @var array Параметры маршрута */
    protected $route = [];

    public function __construct()
    {
        $this->nav_chain = di(NavChain::class);
        $this->render = di(Render::class);
        $this->request = di(Request::class);
        $this->user = di(User::class);
        $this->module_name = 'Блог';
        $this->base_url = '/blog/';
        $this->route = di('route');;
    }
}
