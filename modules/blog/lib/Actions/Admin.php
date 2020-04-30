<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogArticle;
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

                // Collecting parent sections to build a navigation chain
                $parent_tree = [];
                $parent = $current_section->parentSection;
                while ($parent !== null) {
                    $parent_tree[] = [
                        'name' => $parent->name,
                        'url'  => '/blog/admin/content/?section_id=' . $parent->id,
                    ];
                    $parent = $parent->parentSection;
                }

                krsort($parent_tree);
                foreach ($parent_tree as $item) {
                    $this->nav_chain->add($item['name'], $item['url']);
                }

                // Adding the current section to the navigation chain
                $this->nav_chain->add($current_section->name);
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
            $data['articles'] = (new BlogArticle())->where('section_id', $section_id)->orWhereNull('section_id')->orderBy('id')->paginate($this->user->set_user->kmess);
        } else {
            $data['sections'] = (new BlogSection())->where('parent', $section_id)->get();
            $data['articles'] = (new BlogArticle())->where('section_id', $section_id)->orderBy('id')->paginate($this->user->set_user->kmess);
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
