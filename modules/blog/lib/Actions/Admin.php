<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogSection;
use Blog\Utils\AbstractAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Admin extends AbstractAction
{
    /**
     * Главная страница админ панели блога
     */
    public function index(): void
    {
        echo $this->render->render(
            'blog::admin/index',
            [
                'title'      => __('Admin panel'),
                'page_title' => __('Admin panel'),
            ]
        );
    }

    /**
     * Список разделов
     */
    public function section(): void
    {
        $section_id = $this->request->getQuery('section_id', 0);
        $title = __('Section list');
        $this->nav_chain->add($title, '/blog/admin/content/');

        if (! empty($section_id)) {
            try {
                $current_section = (new BlogSection())->findOrFail($section_id);
                $title = $current_section->name;
            } catch (ModelNotFoundException $exception) {
                pageNotFound();
            }
        }

        $data = [];
        $data['sections'] = (new BlogSection())->where('parent', $section_id)->orWhereNull('parent')->get();

        echo $this->render->render(
            'blog::admin/sections',
            [
                'title'      => $title,
                'page_title' => $title,
                'data'       => $data,
            ]
        );
    }
}
