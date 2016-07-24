<?php
/**
 * Email utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use PHPMailer;

/**
 * Email utilities.
 *
 * @since 151121 Email utilities.
 */
class Email extends Classes\Core\Base\Core implements Interfaces\EmailConstants
{
    /**
     * Send an email.
     *
     * @since 151121 Email utilities.
     *
     * @param string|array $to          Address(es).
     * @param string       $subject     Subject line.
     * @param string       $message     Message contents.
     * @param array        $attachments Files to attach.
     * @param array        $headers     Additional headers.
     * @param bool         $throw       Throw exceptions?
     *
     * @return bool True if the email was sent successfully.
     */
    public function __invoke($to, string $subject, string $message, array $attachments = [], array $headers = [], bool $throw = false)
    {
        if (!$this->App->Config->©email['©smtp_host'] || !$this->App->Config->©email['©smtp_port']) {
            throw $this->c::issue('Missing required email config values.');
        }
        $from_name  = $this->App->Config->©email['©from_name'];
        $from_email = $this->App->Config->©email['©from_email'];

        $reply_to_name  = $this->App->Config->©email['©reply_to_name'];
        $reply_to_email = $this->App->Config->©email['©reply_to_email'];

        $recipients = $this->parseAddresses($to);

        if ($this->c::isHtml($message)) {
            $message_html = $message; // HTML!
            $message_text = $this->c::htmlToText($message);
        } else {
            $message_text = $message; // Text!
            $message_html = $this->c::textToHtml($message);
        }
        if (!$message_text) { // Set a default plain text alternative in this case.
            $message_text = __('To view this email message, open it in a program that understands HTML.');
        }
        $headers = $this->parseHeaders($headers, [
            'from_name'     => &$from_name, 'from_email' => &$from_email,
            'reply_to_name' => &$reply_to_name, 'reply_to_email' => &$reply_to_email,
            'recipients'    => &$recipients,
        ]);
        unset($headers['content-type']); // We always send multipart messages w/ UTF-8 encoding.

        $attachments = $this->parseAttachments($attachments); // An array of file paths.

        if (!$from_email || !$recipients || !$subject || !$message_html || !$message_text) {
            return false; // Not possible. Missing vital argument value(s).
        }
        try { // Maybe catch exceptions.

            $mailer = new PHPMailer();
            $mailer->isSmtp();

            $mailer->SingleTo = count($recipients) > 1;

            $mailer->Host       = $this->App->Config->©email['©smtp_host'];
            $mailer->Port       = $this->App->Config->©email['©smtp_port'];
            $mailer->SMTPSecure = $this->App->Config->©email['©smtp_secure'];

            $mailer->SMTPAuth = (bool) $this->App->Config->©email['©smtp_username'];
            $mailer->Username = $this->App->Config->©email['©smtp_username'];
            $mailer->Password = $this->App->Config->©email['©smtp_password'];

            $mailer->SetFrom($from_email, $from_name);

            if ($reply_to_email) { // Special reply-to?
                $mailer->addReplyTo($reply_to_email, $reply_to_name);
            }
            foreach ($recipients as $_email => $_recipient) {
                $mailer->AddAddress($_recipient->email, $_recipient->name);
            } // unset($_email, $_recipient);

            $mailer->CharSet = 'utf-8';
            $mailer->Subject = $subject;

            $mailer->MsgHTML($message_html);
            $mailer->AltBody = $mailer->normalizeBreaks($message_text);

            foreach ($headers as $_header => $_value) {
                $mailer->AddCustomHeader($_header, $_value);
            } // unset($_header, $_value);

            foreach ($attachments as $_file_path => $_attachment) {
                $mailer->AddAttachment($_attachment);
            } // unset($_file_path, $_attachment);

            return $response = (bool) $mailer->Send();
        } catch (\Throwable $Throwable) {
            if ($throw) {
                throw $Throwable;
            }
            return false;
        }
    }

    /**
     * Valid email address?
     *
     * @since 151121 Email utilities.
     *
     * @param string $email Email address to check.
     *
     * @return bool True if email is valid.
     */
    public function isValid(string $email): bool
    {
        return (bool) preg_match($this::EMAIL_REGEX_VALID, $email);
    }

    /**
     * Is role-based address?
     *
     * @since 151121 Email utilities.
     *
     * @param string $email Email address to check.
     *
     * @return bool True if email is role-based.
     */
    public function isRoleBased(string $email): bool
    {
        $user = mb_strtolower(mb_strstr($email, '@', true));
        $user = str_replace(['-', '_', '.'], '', $user);

        if (!is_null($is = &$this->cacheKey(__FUNCTION__, $user))) {
            return $is; // Cached this already.
        }
        if (in_array($user, $this::EMAIL_ROLE_BASED_STRINGS, true)) {
            return $is = true;
        }
        foreach ($this::EMAIL_ROLE_BASED_REGEX_FRAGS as $_regex_frag) {
            if (preg_match('/^'.$_regex_frag.'$/ui', $user)) {
                return $is = true;
            }
        } // unset($_regex_frag); // Housekeeping.

        return $is = false;
    }

    /**
     * Parse addresses.
     *
     * @since 151121 Email utilities.
     *
     * @param mixed $value  Any input value w/ recipients.
     * @param bool  $strict Optional. Defaults to `false` (faster). Parses all strings w/ `@` signs.
     *                      If `true`, validate each address; and only return 100% valid email addresses.
     *
     * @return \StdClass[] Each object in the array contains 3 properties: `name`, `fname`, `lname`, `email`.
     */
    public function parseAddresses($value, bool $strict = false): array
    {
        $addresses = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => $_value) {
                $addresses = array_merge($addresses, $this->parseAddresses($_value, $strict));
            } // unset($_key, $_value); // Housekeeping.
            return $addresses;
        }
        $string                      = (string) $value;
        $delimiter                   = mb_strpos($string, ';') !== false ? ';' : ',';
        $regex_delimitation_splitter = '/'.$delimiter.'+/u'; // `$this->c::escRegex()` unnecessary.

        $possible_addresses = preg_split($regex_delimitation_splitter, $string, -1, PREG_SPLIT_NO_EMPTY);
        $possible_addresses = $this->c::mbTrim($possible_addresses);

        foreach ($possible_addresses as $_address) {
            if (!$_address || mb_strpos($_address, '@', 1) === false) {
                continue; // NOT an email address.
            }
            if (mb_strpos($_address, '<') !== false && preg_match('/(?:"(?<name>[^"]+?)"\s*)?\<(?<email>.+?)\>/u', $_address, $_m)) {
                if ($_m['email'] && mb_strpos($_m['email'], '@', 1) !== false && (!$strict || $this->isValid($_m['email']))) {
                    $_email             = mb_strtolower($_m['email']);
                    $_name              = !empty($_m['name']) ? $_m['name'] : '';
                    $_fname             = $_name ? $this->c::fnameIn($_name) : '';
                    $_lname             = $_name ? $this->c::lnameIn($_name) : '';
                    $addresses[$_email] = (object) ['name' => $_name, 'fname' => $_fname, 'lname' => $_lname, 'email' => $_email];
                }
            } elseif (!$strict || $this->isValid($_address)) {
                $_email             = mb_strtolower($_address);
                $addresses[$_email] = (object) ['name' => '', 'fname' => '', 'lname' => '', 'email' => $_email];
            }
        } // unset($_address, $_m, $_email, $_name, $_fname, $_lname); // Housekeeping.

        return $addresses;
    }

    /**
     * Parse headers.
     *
     * @since 151121 Email utilities.
     *
     * @param mixed $value  Any input value w/ headers.
     * @param array $refs   An input array of references to fill; for special headers.
     * @param bool  $strict Optional. Defaults to `false` (faster). Parses all strings w/ `@` signs.
     *                      If `true`, validate each address, and only return 100% valid email addresses.
     *
     * @return array Unique/associative array of all parsed headers.
     */
    protected function parseHeaders($value, array $refs = [], bool $strict = false): array
    {
        $headers = []; // Initialize parsed headers.

        foreach ($this->c::parseHeaders($value) as $_header => $_value) {
            switch ($_header) { // Maybe populate refs.

                case 'from':
                    if (array_key_exists('from_name', $refs) && array_key_exists('from_email', $refs)) {
                        if (($_from_addresses = $this->parseAddresses($_value, $strict))) {
                            $_from              = array_shift($_from_addresses);
                            $refs['from_name']  = $this->c::mbTrim($_from->fname.' '.$_from->lname);
                            $refs['from_email'] = $_from->email; // By reference.
                        } // unset($_from_addresses, $_from);
                    } else {
                        $headers[$_header] = $_value;
                    }
                    break; // Break switch.

                case 'reply-to':
                    if (array_key_exists('reply_to_name', $refs) && array_key_exists('reply_to_email', $refs)) {
                        if (($_reply_to_addresses = $this->parseAddresses($_value, $strict))) {
                            $_reply_to              = array_shift($_reply_to_addresses);
                            $refs['reply_to_name']  = $this->c::mbTrim($_reply_to->fname.' '.$_reply_to->lname);
                            $refs['reply_to_email'] = $_reply_to->email; // By reference.
                        } // unset($_reply_to_addresses, $_reply_to);
                    } else {
                        $headers[$_header] = $_value;
                    }
                    break; // Break switch.

                case 'cc':
                case 'bcc':
                    if (array_key_exists('recipients', $refs)) {
                        if (($_recipient_addresses = $this->parseAddresses($_value, $strict))) {
                            $refs['recipients'] = array_merge($refs['recipients'], $_recipient_addresses);
                            $refs['recipients'] = array_unique($refs['recipients']);
                        } // unset($_recipient_addresses);
                    } else {
                        $headers[$_header] = $_value;
                    }
                    break; // Break switch.

                default: // Everything else (default).
                    $headers[$_header] = $_value;
                    break; // Break switch.
            }
        }
        return $headers;
    }

    /**
     * Parse attachments.
     *
     * @since 151121 Email utilities.
     *
     * @param mixed $value Any input value w/ attachments.
     *
     * @return array Unique/associative array of all attachments.
     */
    protected function parseAttachments($value): array
    {
        $attachments = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => $_value) {
                $attachments = array_merge($attachments, $this->parseAttachments($_value));
            } // unset($_key, $_value); // Housekeeping.
            return $attachments;
        }
        $string = (string) $value;

        if (is_file($string)) {
            $attachments[$string] = $string;
        }
        return $attachments;
    }
}
