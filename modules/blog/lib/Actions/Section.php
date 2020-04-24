<?php

declare(strict_types=1);

namespace Blog\Actions;

use Blog\Utils\AbstractAction;

class Section extends AbstractAction
{

    public function index()
    {
        echo 'section index';
    }

    public function add()
    {
        echo 'testttt';
    }
}
