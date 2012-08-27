<?php

namespace PRE\Controller;

use Silex\Application;

class ContactController
{
    public function indexAction(Application $app)
    {
        include $app['root_dir'] . "/index.php";
    }

    public function editAction(Application $app)
    {
        include $app['root_dir'] . "/edit.php";
    }

    public function newAction(Application $app)
    {
        include $app['root_dir'] . "/new.php";
    }

    public function removeAction(Application $app)
    {
        include $app['root_dir'] . "/remove.php";
    }

}