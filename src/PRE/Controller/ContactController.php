<?php

namespace PRE\Controller;

use Silex\Application;

class ContactController
{
    public function indexAction(Application $app)
    {
        $db = @mysql_connect($app['config']['database']['host'], $app['config']['database']['username'], $app['config']['database']['password']) or die('Can\'t connect do database');
        @mysql_select_db($app['config']['database']['name']) or die('The database selected does not exists');

        $query = 'SELECT * FROM contacts ORDER BY lastname';
        $rs = mysql_query($query);

        if (!$rs)
        {
            die_with_error(mysql_error(), $query);
        }

        $contacts = array();
        while ($row = mysql_fetch_assoc($rs))
        {
            $contacts[] = $row;
        }

        mysql_free_result($rs);
        mysql_close($db);

        return $app['twig']->render("contact/index.twig", array('contacts' => $contacts));
    }

    public function editAction(Application $app)
    {
        $errors = null;
        if (!$_GET['id'])
        {
            die('Some error occured!!');
        }

        $db = @mysql_connect($app['config']['database']['host'], $app['config']['database']['username'], $app['config']['database']['password']) or die('Can\'t connect do database');
        @mysql_select_db($app['config']['database']['name']) or die('The database selected does not exists');

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $errors = validate(array('id', 'firstname', 'lastname', 'phone'), $_POST);

            if (count($errors) == 0)
            {
                $query = sprintf("UPDATE contacts set firstname = '%s', 
                                                                          lastname = '%s',
                                                                          phone = '%s', 
                                                                          mobile = '%s' WHERE id = %s", mysql_real_escape_string($_POST['firstname']), mysql_real_escape_string($_POST['lastname']), mysql_real_escape_string($_POST['phone']), mysql_real_escape_string($_POST['mobile']), mysql_real_escape_string($_POST['id'])
                );

                $rs = mysql_query($query);

                if (!$rs)
                {
                    die_with_error(mysql_error(), $query);
                }

                header('Location: ' . $app['url_generator']->generate('index'));
                exit;
            }
        }
        else
        {
            $query = sprintf('SELECT * FROM contacts WHERE id = %s', mysql_real_escape_string($_GET['id']));

            $rs = mysql_query($query);

            if (!$rs)
            {
                die_with_error(mysql_error(), $query);
            }

            $row = mysql_fetch_assoc($rs);
        }

        mysql_close($db);

        return $app['twig']->render("contact/edit.twig", array("contact" => $row, "errors" => $errors));
    }

    public function newAction(Application $app)
    {
        $errors = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $errors = validate(array('firstname', 'lastname', 'phone'), $_POST);

            if (count($errors) == 0)
            {
                $db = @mysql_connect($app['config']['database']['host'], $app['config']['database']['username'], $app['config']['database']['password']) or die('Can\'t connect do database');
                @mysql_select_db($app['config']['database']['name']) or die('The database selected does not exists');

                $query = sprintf("INSERT INTO contacts (firstname, lastname, phone, mobile) VALUES ('%s', '%s', '%s', '%s')", mysql_real_escape_string($_POST['firstname']), mysql_real_escape_string($_POST['lastname']), mysql_real_escape_string($_POST['phone']), mysql_real_escape_string($_POST['mobile'])
                );

                $rs = mysql_query($query);

                if (!$rs)
                {
                    die_with_error(mysql_error(), $query);
                }

                mysql_close($db);

                header('Location: ' . $app['url_generator']->generate('index'));
                exit;
            }
        }
        return $app['twig']->render("contact/edit.twig", array('errors' => $errors));
    }

    public function removeAction(Application $app)
    {
        if (!$_GET['id'])
        {
            die('Some error occured!!');
        }

        $db = @mysql_connect($app['config']['database']['host'], $app['config']['database']['username'], $app['config']['database']['password']) or die('Can\'t connect do database');
        @mysql_select_db($app['config']['database']['name']) or die('The database selected does not exists');

        $query = sprintf('DELETE FROM contacts where ID = %s', mysql_real_escape_string($_GET['id']));

        if (!mysql_query($query))
        {
            die_with_error(mysql_error(), $query);
        }

        mysql_close($db);

        header('Location: ' . $app['url_generator']->generate('index'));
        exit;
    }

}