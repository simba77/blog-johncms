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
        $this->render->addData(
            [
                'title'      => __('Admin panel'),
                'page_title' => __('Admin panel'),
            ]
        );
        echo $this->render->render('blog::admin/index');
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

        if (! empty($_SESSION['success_message'])) {
            $data['messages'] = htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']);
        }

        if (empty($section_id)) {
            $data['sections'] = (new BlogSection())->where('parent', $section_id)->orWhereNull('parent')->get();
        } else {
            $data['sections'] = (new BlogSection())->where('parent', $section_id)->get();
        }

        $data['current_section'] = $section_id;

        $this->render->addData(
            [
                'title'      => $title,
                'page_title' => $title,
            ]
        );
        echo $this->render->render('blog::admin/sections', ['data' => $data]);
    }
}
