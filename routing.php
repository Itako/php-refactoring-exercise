<?php

use PRE\Controller\ContactController;

$app->get("/", array(new ContactController,"indexAction"))->bind("index");
$app->match("/new", array(new ContactController,"newAction"))->bind("new");
$app->match("/edit", array(new ContactController,"editAction"))->bind("edit");
$app->match("/remove", array(new ContactController,"removeAction"))->bind("remove");