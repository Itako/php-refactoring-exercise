<?php

namespace PRE\Controller;

use Silex\Application;

class ContactController
{
    public function indexAction(Application $app)
    {
        $contacts = $app['db']->fetchAll('SELECT * FROM contacts ORDER BY lastname');
        return $app['twig']->render("contact/index.twig", array('contacts' => $contacts));
    }

    public function editAction(Application $app)
    {
        $errors = array();
        if (!$app['request']->get("id"))
        {
            die('Some error occured!!');
        }

        if ($app['request']->getMethod() == 'POST')
        {
            $errors = validate(array('id', 'firstname', 'lastname', 'phone'), $_POST);

            if (count($errors) == 0)
            {
                $app['db']->executeUpdate(
                        "UPDATE contacts SET firstname = ?,
                            lastname = ?,
                            phone = ?,
                            mobile = ?
                            WHERE id = ?", array(
                    $app['request']->get("firstname"),
                    $app['request']->get("lastname"),
                    $app['request']->get("phone"),
                    $app['request']->get("mobile"),
                    $app['request']->get("id")
                        )
                );
                return $app->redirect($app['url_generator']->generate('index'));
            }
        }
        else
        {
            $contact = $app['db']->fetchAssoc('SELECT * FROM contacts WHERE id = ?', array($app['request']->get("id")));
        }

        return $app['twig']->render("contact/edit.twig", array("contact" => $contact, "errors" => $errors));
    }

    public function newAction(Application $app)
    {
        $errors = array();
        if ($app['request']->getMethod() == 'POST')
        {
            $errors = validate(array('firstname', 'lastname', 'phone'), $_POST);

            if (count($errors) == 0)
            {
                $app['db']->executeQuery("INSERT INTO contacts (firstname, lastname, phone, mobile) VALUES (?, ?, ?, ?)", array(
                    $app['request']->get("firstname"),
                    $app['request']->get("lastname"),
                    $app['request']->get("phone"),
                    $app['request']->get("mobile")
                        )
                );

                return $app->redirect($app['url_generator']->generate('index'));
            }
        }
        return $app['twig']->render("contact/edit.twig", array('errors' => $errors));
    }

    public function removeAction(Application $app)
    {
        if (!$app['request']->get("id"))
        {
            die('Some error occured!!');
        }

        $app['db']->executeQuery('DELETE FROM contacts where ID = ?', array($app['request']->get("id")));

        return $app->redirect($app['url_generator']->generate('index'));
    }

}