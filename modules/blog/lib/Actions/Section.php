<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogSection;
use Blog\Utils\AbstractAction;
use Illuminate\Support\Str;

class Section extends AbstractAction
{

    public function index()
    {
        echo 'section index';
    }

    /**
     * Section creation page
     */
    public function add(): void
    {
        $this->nav_chain->add(__('Create section'));
        $this->render->addData(
            [
                'title'      => __('Create section'),
                'page_title' => __('Create section'),
            ]
        );

        $section_id = $this->request->getQuery('section_id', 0);

        $data = [
            'section_id' => $section_id,
            'fields'     => [
                'parent'      => $section_id,
                'name'        => $this->request->getPost('name', '', FILTER_SANITIZE_STRING),
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
                $errors[] = __('The section name cannot be empty');
            }

            // Code generation
            if (empty($data['fields']['code'])) {
                $data['fields']['code'] = Str::slug($data['fields']['name']);
            } else {
                $data['fields']['code'] = Str::slug($data['fields']['code']);
            }

            if (empty($errors)) {
                if (! empty($section_id)) {
                    $check = (new BlogSection())
                        ->where('code', $data['fields']['code'])
                        ->where('parent', $section_id)
                        ->first();
                } else {
                    $check = (new BlogSection())
                        ->where('code', $data['fields']['code'])
                        ->whereNull('parent')
                        ->orWhere('parent', '=', 0)
                        ->first();
                }

                if (! $check) {
                    (new BlogSection())->create($data['fields']);
                    $_SESSION['success_message'] = __('The section was created successfully');
                    header('Location: /blog/admin/content/?section_id=' . $section_id);
                    exit;
                }
                $errors[] = __('A section with this code already exists');
            }
        }

        $data['errors'] = $errors;

        echo $this->render->render('blog::admin/add_section', ['data' => $data]);
    }
}
