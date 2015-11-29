<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Dump($App->Utils->Email('jas+test@myinbox.ws', 'Testing™ •', '<p>Hello world!™ •</p><p>hi!</p>'));
