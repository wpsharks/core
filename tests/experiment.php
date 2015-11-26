<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$encrypted = $App->Utils->Rijndael256->encrypt('hello world', md5('.'), false);
$decrypted = $App->Utils->Rijndael256->decrypt($encrypted, md5('.'));

echo $encrypted."\n";
echo $decrypted;
