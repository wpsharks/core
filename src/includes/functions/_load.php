<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

require_once __DIR__.'/base64.php';
require_once __DIR__.'/benchmark.php';
require_once __DIR__.'/cli.php';
require_once __DIR__.'/clippers.php';
require_once __DIR__.'/cookies.php';
require_once __DIR__.'/dimensions.php';
require_once __DIR__.'/dirs.php';
require_once __DIR__.'/dump.php';
require_once __DIR__.'/email.php';
require_once __DIR__.'/encryption.php';
require_once __DIR__.'/eols.php';
require_once __DIR__.'/escapes.php';
require_once __DIR__.'/exceptions.php';
require_once __DIR__.'/files.php';
require_once __DIR__.'/headers.php';
require_once __DIR__.'/html.php';
require_once __DIR__.'/i18n.php';
require_once __DIR__.'/ips.php';
require_once __DIR__.'/iterators.php';
require_once __DIR__.'/markdown.php';
require_once __DIR__.'/multibyte.php';
require_once __DIR__.'/names.php';
require_once __DIR__.'/oembed.php';
require_once __DIR__.'/os.php';
require_once __DIR__.'/output.php';
require_once __DIR__.'/patterns.php';
require_once __DIR__.'/pdo.php';
require_once __DIR__.'/percentages.php';
require_once __DIR__.'/php.php';
require_once __DIR__.'/replacements.php';
require_once __DIR__.'/serializer.php';
require_once __DIR__.'/sha1-mods.php';
require_once __DIR__.'/slashes.php';
require_once __DIR__.'/slugs.php';
require_once __DIR__.'/spellcheck.php';
require_once __DIR__.'/templates.php';
require_once __DIR__.'/tokenizer.php';
require_once __DIR__.'/translits.php';
require_once __DIR__.'/url-builders.php';
require_once __DIR__.'/url-current.php';
require_once __DIR__.'/url-parsers.php';
require_once __DIR__.'/url-querys.php';
require_once __DIR__.'/url-utils.php';
require_once __DIR__.'/uuid64.php';
require_once __DIR__.'/uuids.php';
require_once __DIR__.'/versions.php';
require_once __DIR__.'/webpurify.php';
require_once __DIR__.'/yaml.php';

/* Use this snippet to recompile this file after a lot of changes.
foreach (array_diff(scandir(__DIR__), ['.', '..', basename(__FILE__)]) as $_file) {
    echo "require_once __DIR__.'/$_file';\n";
} unset($_file); // Housekeeping.
*/
