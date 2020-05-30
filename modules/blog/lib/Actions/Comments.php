<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Models\BlogArticle;
use Blog\Models\BlogComments;
use Blog\Utils\AbstractAction;
use Blog\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Johncms\System\Http\Environment;
use Johncms\System\View\Extension\Avatar;

class Comments extends AbstractAction
{
    /**
     * The list of comments
     */
    public function index(): void
    {
        $article_id = $this->request->getQuery('article_id', 0, FILTER_VALIDATE_INT);
        if (empty($article_id)) {
            http_response_code(400);
            Helpers::returnJson(['error' => __('Bad Request')]);
        }

        if (! empty($article_id)) {
            /** @var LengthAwarePaginator $comments */
            $comments = (new BlogComments())->with('user')->where('article_id', $article_id)->paginate($this->user->config->kmess);

            /** @var Avatar $avatar */
            $avatar = di(Avatar::class);

            $array = [
                'current_page'   => $comments->currentPage(),
                'data'           => $comments->getItems()->map(
                    static function (BlogComments $comment) use ($avatar) {
                        $user = $comment->user;
                        $user_data = [];
                        if ($user) {
                            $user_data = [
                                'id'          => $user->id,
                                'user_name'   => $user->name,
                                'status'      => $user->status,
                                'is_online'   => $user->is_online,
                                'rights_name' => $user->rights_name,
                                'profile_url' => $user->profile_url,
                                'avatar'      => $avatar->getUserAvatar($user->id),
                            ];
                        }

                        return [
                            'id'         => $comment->id,
                            'created_at' => $comment->created_at,
                            'text'       => $comment->text,
                            'user'       => $user_data,
                        ];
                    }
                ),
                'first_page_url' => $comments->url(1),
                'from'           => $comments->firstItem(),
                'last_page'      => $comments->lastPage(),
                'last_page_url'  => $comments->url($comments->lastPage()),
                'next_page_url'  => $comments->nextPageUrl(),
                'path'           => $comments->path(),
                'per_page'       => $comments->perPage(),
                'prev_page_url'  => $comments->previousPageUrl(),
                'to'             => $comments->lastItem(),
                'total'          => $comments->total(),
            ];

            Helpers::returnJson($array);
        }
    }

    public function add(): void
    {
        $article_id = $this->request->getQuery('article_id', 0);
        $post_body = $this->request->getBody();
        if ($post_body) {
            $post_body = json_decode($post_body->getContents(), true);
        }

        try {
            $article = (new BlogArticle())->findOrFail($article_id);
        } catch (ModelNotFoundException $exception) {
            Helpers::returnJson(['message' => $exception->getMessage()]);
        }

        /** @var Environment $env */
        $env = di(Environment::class);

        $comment = trim($post_body['comment']);

        if (! empty($comment)) {
            (new BlogComments())->create(
                [
                    'article_id' => $article->id,
                    'user_id'    => $this->user->id,
                    'text'       => $comment,
                    'user_data'  => [
                        'user_agent'   => $env->getUserAgent(),
                        'ip'           => $env->getIp(false),
                        'ip_via_proxy' => $env->getIpViaProxy(false),
                    ],
                    'created_at' => Carbon::now()->toIso8601String(),
                ]
            );

            $last_page = (new BlogComments())->where('article_id', $article->id)->paginate($this->user->config->kmess)->lastPage();
            Helpers::returnJson(['message' => __('The comment was added successfully'), 'last_page' => $last_page]);
        } else {
            http_response_code(422);
            Helpers::returnJson(['message' => __('Enter the comment text')]);
        }
    }
}
