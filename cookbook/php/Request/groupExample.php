<?php

/*
** =====================================================================================================
** Create a group
** =====================================================================================================
*/

//Parametres
$name = 'groupName';
$description = 'description';
$type = 1;

//Creation du Json du message
$arr = array(
		'name' => $name,
		'description' => $description,
		'type' => $type,);

//On affiche le message
$message = json_encode($arr);
echo $message . "\n";


/*
** =====================================================================================================
** Delete the group
** =====================================================================================================
*/

$id = 0123;
$url = 'http://v8.mailperformance.com/groups/' . $id;

echo $url . "\n";


/*
** =====================================================================================================
** Retrieve a group
** =====================================================================================================
*/

$id = 0123;
$url = 'http://v8.mailperformance.com/groups/' . $id;

echo $url . "\n";


/*
** =====================================================================================================
** Retrieve all groups
** =====================================================================================================
*/

$url = 'http://v8.mailperformance.com/groups/';

echo $url . "\n";


/*
** =====================================================================================================
** Retrieve the contacts in a group
** =====================================================================================================
*/

$id = 0123;
$url = 'http://v8.mailperformance.com/groups/' . $id . '/contacts';

echo $url . "\n";


/*
** =====================================================================================================
** Update a group
** =====================================================================================================
*/

//Parametres
$name = 'groupName';
$description = 'description';
$type = 1;

//Creation du Json du message
$arr = array(
'name' => $name,
'description' => $description,
'type' => $type,);

//On affiche le message
$message = json_encode($arr);
echo $message . "\n";

?>