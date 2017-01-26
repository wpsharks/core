<?php
/**
 * Debugging utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Debugging utilities.
 *
 * @since 160522 Debugging utilities.
 */
class Debugging extends Classes\Core\Base\Core implements Interfaces\ByteConstants
{
    /**
     * Logs directory.
     *
     * @since 160522 Debugging utilities.
     *
     * @type string Logs directory.
     */
    protected $logs_dir;

    /**
     * Max log file size.
     *
     * @since 160522 Debugging utilities.
     *
     * @type int Max log file size.
     */
    protected $max_log_file_size;

    /**
     * Max log file age.
     *
     * @since 160522 Debugging utilities.
     *
     * @type string Max log file age.
     */
    protected $max_log_file_age;

    /**
     * First-process writes by file.
     *
     * @since 160522 Debugging utilities.
     *
     * @type array First-process writes by file.
     */
    protected $first_process_writes;

    /**
     * Class constructor.
     *
     * @since 160522 Debugging utilities.
     *
     * @param Classes\App $App Instance.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©logs_dir']) {
            throw new Exception('Missing logs directory.');
        }
        $this->logs_dir             = $this->App->Config->©fs_paths['©logs_dir'].'/debug';
        $this->max_log_file_size    = $this::BYTES_IN_MB * 2;
        $this->max_log_file_age     = strtotime('-7 days');
        $this->first_process_writes = []; // Initialize.
    }

    /**
     * Log issue & generate exception.
     *
     * @since 160522 Debugging utilities.
     *
     * @param mixed  $data  Data to log (e.g., {@link vars()}).
     * @param string $note  A brief note or description of the issue.
     * @param string $event Event name (e.g., {@link __METHOD__}).
     *
     * @return Exception An exception ready to be thrown.
     *
     * @internal If only one parameter is passed and it's a string, it's treated as the `$note`.
     */
    public function logIssue($data = [], string $note = '', string $event = ''): Exception
    {
        if (func_num_args() === 1 && is_string($data)) {
            $note = $data; // Use data as note.
            $data = []; // Data empty in this case.
        }
        $event = $event ?: $this->traceLogEventTrigger();

        if (mb_strpos($event, '#') === false) {
            $event .= '#issue';
        } // Assume this is an issue.

        $this->maybeLog($data, $note, $event);

        return new Exception($note ? $note : $event);
    }

    /**
     * Log for review.
     *
     * @since 160522 Debugging utilities.
     *
     * @param mixed  $data  Data to log (e.g., {@link vars()}).
     * @param string $note  A brief note or description of the issue.
     * @param string $event Event name (e.g., {@link __METHOD__}).
     *
     * @return int Total number of bytes written.
     *
     * @internal If only one parameter is passed and it's a string, it's treated as the `$note`.
     */
    public function logReview($data = [], string $note = '', string $event = ''): int
    {
        if (func_num_args() === 1 && is_string($data)) {
            $note = $data; // Use data as note.
            $data = []; // Data empty in this case.
        }
        $event = $event ?: $this->traceLogEventTrigger();

        if (mb_strpos($event, '#') === false) {
            $event .= '#review';
        } // Assume this is for review.

        return $this->maybeLog($data, $note, $event);
    }

    /**
     * Maybe log for debugging.
     *
     * @since 160522 Debugging utilities.
     *
     * @param mixed  $data  Data to log (e.g., {@link vars()}).
     * @param string $note  A brief note or description of the issue.
     * @param string $event Event name (e.g., {@link __METHOD__}).
     *
     * @return int Total number of bytes written.
     */
    protected function maybeLog($data = [], string $note = '', string $event = ''): int
    {
        if (!$this->App->Config->©debug['©log']) {
            return 0; // Not applicable.
        }
        $is_cli       = $this->c::isCli();
        $is_wordpress = $this->c::isWordPress();
        $event        = $this->cleanLogEvent($event);

        $lines[] = __('Time:').'         '.date('F jS, Y, g:i a T');
        $lines[] = __('Microtime:').'    '.number_format(microtime(true), 8, '.', '');
        $lines[] = __('Event:').'        '.($event ? $event : __('unknown event name'));
        $lines[] = __('Note:').'         '.($note ? $note : __('no note given by caller'))."\n";

        $lines[] = __('System:').'       '.PHP_OS.'; PHP v'.PHP_VERSION.' ('.PHP_SAPI.')';
        $lines[] = __('Software:').'     WSC v'.Classes\App::VERSION.// Core app class version.

                                         ($is_wordpress ? '; WP v'.$GLOBALS['wp_version'] : '').
                                         ($is_wordpress && defined('WC_VERSION') ? '; WC v'.WC_VERSION : '').

                                         ($this->App->Parent ? '; '.$this->App->Parent->Config->©brand['©acronym'].' v'.$this->App->Parent::VERSION : '').
                                        (!$this->App->is_ws_core ? '; '.$this->App->Config->©brand['©acronym'].' v'.$this->App::VERSION : '')."\n";

        if ($is_wordpress && ($user = wp_get_current_user()) && $user->exists()) {
            $lines[] = __('User:').'         #'.$user->ID.' @'.$user->user_login.' \''.$user->display_name.'\'';
        }
        $lines[] = __('User IP:').'      '.($is_cli ? __('n/a; CLI process') : $this->c::currentIp());
        $lines[] = __('User Agent:').'   '.($is_cli ? __('n/a; CLI process') : ($_SERVER['HTTP_USER_AGENT'] ?? ''));
        $lines[] = __('URL:').'          '.($is_cli ? __('n/a; CLI process') : $this->c::currentUrl())."\n";

        $lines[] = $this->c::mbTrim($this->c::dump($data, true), "\r\n"); // A dump of the data (variables).

        if (is_callable($this->App->Config->©debug['©log_callback'])) {
            $this->App->Config->©debug['©log_callback'](compact('event', 'data', 'note', 'lines'));
        }
        return $this->writeLogFileLines($event, $lines); // Writes the log entry.
    }

    /**
     * Write lines to log file.
     *
     * @since 160522 Debugging utilities.
     *
     * @param string $event Event name.
     * @param array  $lines Log entry lines.
     *
     * @return int Total number of bytes written.
     */
    protected function writeLogFileLines(string $event, array $lines): int
    {
        if (!$lines) { // No lines?
            return 0; // Stop; nothing to do here.
        }
        $this->prepareLogsDir(); // Prepares (and secures).

        if (mb_strpos($event, '#') !== false) {
            $file_name = mb_strstr($event, '#');
            $file_name = $this->c::nameToSlug($file_name);
            $file_name .= '.log'; // Add extension now.
        }
        $file_name    = $file_name ?: 'debug.log';
        $process_file = $this->logs_dir.'/process.'.$file_name;
        $file         = $this->logs_dir.'/'.$file_name;

        $this->maybeRotateLogFiles($file);

        $entry = implode("\n", $lines)."\n\n".str_repeat('-', 3)."\n\n";

        if (!isset($this->first_process_writes[$process_file])) {
            $this->first_process_writes[$process_file] = -1;
            @unlink($process_file); // First-process write.
        } // ↑ Empty file on the first write in each process.

        file_put_contents($process_file, $entry, LOCK_EX | FILE_APPEND);
        return (int) file_put_contents($file, $entry, LOCK_EX | FILE_APPEND);
    }

    /**
     * Cleans log event name.
     *
     * @since 160522 Debugging utilities.
     *
     * @param string $event Log event name.
     *
     * @return string Cleaned log event name.
     */
    protected function cleanLogEvent(string $event): string
    {
        if (($classes_pos = mb_strripos($event, '\\Classes\\')) !== false) {
            $event = mb_substr($event, $classes_pos + 9);
        } // This chops off `*\Classes\` from `__METHOD__`.
        $event = str_replace($this->App->namespace, '', $event);

        return $event = $this->c::mbTrim($event, '', '\\');
    }

    /**
     * Prepares the logs directory.
     *
     * @since 160522 Debugging utilities.
     */
    protected function prepareLogsDir()
    {
        if (is_dir($this->logs_dir)) {
            if (!is_writable($this->logs_dir)) {
                throw new Exception(sprintf('Logs directory not writable: `%1$s`.', $this->logs_dir));
            } // Always check to be sure the logs directory is still writable.
            return; // Otherwise; nothing to do here.
        }
        if (!mkdir($this->logs_dir, $this->App->Config->©fs_permissions['©transient_dirs'], true)) {
            throw new Exception(sprintf('Logs directory not writable: `%1$s`.', $this->logs_dir));
        } elseif (!$this->c::apacheHtaccessDeny($this->logs_dir)) {
            throw new Exception(sprintf('Unable to secure logs directory: `%1$s`.', $this->logs_dir));
        }
    }

    /**
     * Maybe rotate log files.
     *
     * @since 160522 Debugging utilities.
     *
     * @param string $file Absolute file path.
     */
    protected function maybeRotateLogFiles(string $file)
    {
        if (!$file || !is_file($file)) {
            return; // Nothing to do at this time.
        } elseif (filesize($file) < $this->max_log_file_size) {
            return; // Nothing to do at this time.
        } // Only rotate when log file becomes large.

        rename($file, $this->uniqueSuffixLogFile($file));

        foreach ($this->c::dirRegexRecursiveIterator($this->logs_dir, '/\.log$/ui') as $_Resource) {
            if ($_Resource->isFile() && $_Resource->getMTime() < $this->max_log_file_age) {
                unlink($_Resource->getPathname());
            }
        } // unset($_Resource); // Housekeeping.
    }

    /**
     * Trace log event trigger.
     *
     * @since 160522 Debugging utilities.
     *
     * @return string Log event trigger.
     */
    protected function traceLogEventTrigger(): string
    {
        // `[0]` is the call to this function.
        // `[1]` is the call to the utility function in this class.
        $callers = $this->c::backtraceCallers(2);
        return $callers ? $callers[0] : '';
    }

    /**
     * Unique suffix log file.
     *
     * @since 160522 Debugging utilities.
     *
     * @param string $file Absolute file path.
     *
     * @return string New unique (suffixed) log file.
     */
    protected function uniqueSuffixLogFile(string $file): string
    {
        return preg_replace('/\.log$/ui', '', $file).'-'.$this->c::uniqueId().'.log';
    }
}
