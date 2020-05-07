<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;
use Johncms\System\View\Render;

$view = di(Render::class);

Capsule::Schema()->create(
    'blog_sections',
    static function (Blueprint $table) {
        $table->increments('id');
        $table->integer('parent')->index()->nullable();
        $table->string('name');
        $table->string('code')->index()->nullable();
        $table->text('text')->nullable();
        $table->text('keywords')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
);

Capsule::Schema()->create(
    'blog_articles',
    static function (Blueprint $table) {
        $table->increments('id');
        $table->integer('section_id')->unsigned()->index();
        $table->string('name');
        $table->string('page_title')->nullable();
        $table->string('code')->index();
        $table->text('keywords')->nullable();
        $table->text('description')->nullable();
        $table->text('preview_text')->nullable();
        $table->longText('text');
        $table->integer('view_count')->nullable();
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->unique(['section_id', 'code'], 'section_code');

        $table->foreign('section_id')
            ->references('id')
            ->on('blog_sections')
            ->onUpdate('cascade')
            ->onDelete('cascade');
    }
);

echo $view->render(
    'system::pages/result',
    [
        'title'   => __('Blog'),
        'type'    => 'alert-danger',
        'message' => __('The module was installed successfully'),
    ]
);
