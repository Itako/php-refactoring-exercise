<?php

if(!$_GET['id'])
{
 die('Some error occured!!');
}

$db = @mysql_connect($app['config']['database']['host'], $app['config']['database']['username'], $app['config']['database']['password']) or die('Can\'t connect do database');
@mysql_select_db($app['config']['database']['name']) or die('The database selected does not exists');

$query = sprintf('DELETE FROM contacts where ID = %s',
                 mysql_real_escape_string($_GET['id']));
                 
if(!mysql_query($query))
{
  die_with_error(mysql_error(), $query);
}

mysql_close($db);

header('Location: '.$app['url_generator']->generate('index'));exit;