<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_cli(...$args)
{
    return $GLOBALS[App::class]->Utils->Cli->is(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_cli_interactive(...$args)
{
    return $GLOBALS[App::class]->Utils->Cli->isInteractive(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function read_stdin(...$args)
{
    return $GLOBALS[App::class]->Utils->CliStream->in(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function write_stdout(...$args)
{
    return $GLOBALS[App::class]->Utils->CliStream->out(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function write_stdout_div(...$args)
{
    return $GLOBALS[App::class]->Utils->CliStream->outDiv(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function write_stderr(...$args)
{
    return $GLOBALS[App::class]->Utils->CliStream->err(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function write_stderr_div(...$args)
{
    return $GLOBALS[App::class]->Utils->CliStream->errDiv(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function cli_open_url(...$args)
{
    return $GLOBALS[App::class]->Utils->CliOs->openUrl(...$args);
}
