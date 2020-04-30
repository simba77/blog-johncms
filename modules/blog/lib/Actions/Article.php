<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogArticle;
use Blog\Utils\AbstractAction;
use Illuminate\Support\Str;

class Article extends AbstractAction
{
    /**
     * Article creation page
     */
    public function add(): void
    {
        $this->nav_chain->add(__('Add article'));
        $this->render->addData(
            [
                'title'      => __('Add article'),
                'page_title' => __('Add article'),
            ]
        );

        $section_id = $this->request->getQuery('section_id', 0);

        $data = [
            'section_id' => $section_id,
            'fields'     => [
                'section_id'  => $section_id,
                'name'        => $this->request->getPost('name', '', FILTER_SANITIZE_STRING),
                'page_title'  => $this->request->getPost('page_title', '', FILTER_SANITIZE_STRING),
                'code'        => $this->request->getPost('code', '', FILTER_SANITIZE_STRING),
                'keywords'    => $this->request->getPost('keywords', '', FILTER_SANITIZE_STRING),
                'description' => $this->request->getPost('description', '', FILTER_SANITIZE_STRING),
                'text'        => $this->request->getPost('text', ''),
            ],
        ];

        $data['fields'] = array_map('trim', $data['fields']);

        $errors = [];
        // Processing the sent data from the form.
        if ($this->request->getMethod() === 'POST') {
            if (empty($data['fields']['name'])) {
                $errors[] = __('The article name cannot be empty');
            }

            // Code generation
            if (empty($data['fields']['code'])) {
                $data['fields']['code'] = Str::slug($data['fields']['name']);
            } else {
                $data['fields']['code'] = Str::slug($data['fields']['code']);
            }

            if (empty($errors)) {
                if (! empty($section_id)) {
                    $check = (new BlogArticle())
                        ->where('code', $data['fields']['code'])
                        ->where('section_id', $section_id)
                        ->first();
                } else {
                    $check = (new BlogArticle())
                        ->where('code', $data['fields']['code'])
                        ->whereNull('parent')
                        ->orWhere('section_id', '=', 0)
                        ->first();
                }

                if (! $check) {
                    $data['fields']['created_by'] = $this->user->id;
                    (new BlogArticle())->create($data['fields']);
                    $_SESSION['success_message'] = __('The article was created successfully');
                    header('Location: /blog/admin/content/?section_id=' . $section_id);
                    exit;
                }
                $errors[] = __('An article with this code already exists');
            }
        }

        $data['errors'] = $errors;

        echo $this->render->render('blog::admin/add_article', ['data' => $data]);
    }
}
