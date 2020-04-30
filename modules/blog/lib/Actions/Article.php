<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogArticle;
use Blog\Utils\AbstractAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        $section_id = $this->request->getQuery('section_id', 0, FILTER_VALIDATE_INT);

        $data = [
            'action_url' => '/blog/admin/add_article/?section_id=' . $section_id,
            'back_url'   => '/blog/admin/content/?section_id=' . $section_id,
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

    /**
     * The edit article page
     */
    public function edit(): void
    {
        $this->nav_chain->add(__('Edit article'));
        $this->render->addData(
            [
                'title'      => __('Edit article'),
                'page_title' => __('Edit article'),
            ]
        );

        $article_id = $this->request->getQuery('article_id', 0, FILTER_VALIDATE_INT);
        try {
            $article = (new BlogArticle())->findOrFail($article_id);
        } catch (ModelNotFoundException $exception) {
            pageNotFound();
        }

        $data = [
            'action_url' => '/blog/admin/edit_article/?article_id=' . $article->id,
            'back_url'   => '/blog/admin/content/?section_id=' . $article->section_id,
            'article_id' => $article_id,
            'fields'     => [
                'name'        => $this->request->getPost('name', $article->name, FILTER_SANITIZE_STRING),
                'page_title'  => $this->request->getPost('page_title', $article->page_title, FILTER_SANITIZE_STRING),
                'code'        => $this->request->getPost('code', $article->code, FILTER_SANITIZE_STRING),
                'keywords'    => $this->request->getPost('keywords', $article->keywords, FILTER_SANITIZE_STRING),
                'description' => $this->request->getPost('description', $article->description, FILTER_SANITIZE_STRING),
                'text'        => $this->request->getPost('text', $article->text),
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
                $check = (new BlogArticle())
                    ->where('code', $data['fields']['code'])
                    ->where('section_id', $article->section_id)
                    ->where('id', '!=', $article->id)
                    ->first();

                if (! $check) {
                    $data['fields']['updated_by'] = $this->user->id;
                    $article->update($data['fields']);
                    $_SESSION['success_message'] = __('The article was updated successfully');
                    header('Location: /blog/admin/content/?section_id=' . $article->section_id);
                    exit;
                }
                $errors[] = __('An article with this code already exists');
            }
        }

        $data['errors'] = $errors;

        echo $this->render->render('blog::admin/add_article', ['data' => $data]);
    }
}
