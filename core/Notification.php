<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik
 * @package Piwik
 */
namespace Piwik;

/**
 * @package Piwik
 * @subpackage Notification
 * @api
 */
class Notification
{
    const CONTEXT_SUCCESS = 'success';
    const CONTEXT_ERROR   = 'error';
    const CONTEXT_INFO    = 'info';
    const CONTEXT_WARNING = 'warning';

    /**
     * Lowest priority
     */
    const PRIORITY_MIN    = 100;

    /**
     * Lower priority
     */
    const PRIORITY_LOW    = 50;

    /**
     * Higher priority
     */
    const PRIORITY_HIGH   = 25;

    /**
     * Highest priority
     */
    const PRIORITY_MAX    = 1;

    /**
     * If flag applied, no close icon will be displayed. Please note that persistent notifications always have a close
     * icon
     */
    const FLAG_NO_CLEAR   = 1;

    /**
     * Implies transient. Notification will be displayed for a few seconds and then faded out
     */
    const TYPE_TOAST      = 'toast';

    /**
     * Notification will be displayed until the new user explicitly closes the notification
     */
    const TYPE_PERSISTENT = 'persistent';

    /**
     * Notification will be displayed only once.
     */
    const TYPE_TRANSIENT  = 'transient';

    /**
     * The title of the notification. For instance the plugin name. The title is optional.
     * @var string
     */
    public $title;

    /**
     * The actual message that will be displayed. Must be set.
     * @var string
     */
    public $message;

    /**
     * @var int optional flags. Usage: $notification->flags = Notification::FLAG_BAR | Notification::FLAG_FOO
     */
    public $flags;

    /**
     * The type of the notification. See self::TYPE_*
     * @var string
     */
    public $type = self::TYPE_TRANSIENT;

    /**
     * Context of the notification. For instance info, warning, success or error.
     * @var string
     */
    public $context = self::CONTEXT_INFO;

    /**
     * The priority of the notification, the higher the priority, the higher the order. Notifications having the
     * highest priority will be displayed first and all other notifications below.
     * @var int
     */
    public $priority = self::PRIORITY_LOW;

    /**
     * @param  string $message   The notification message.
     * @throws \Exception        In case the message is empty.
     */
    public function __construct($message)
    {
        if (empty($message)) {
            throw new \Exception('No notification message given');
        }

        $this->message = $message;
    }

    public function hasNoClear()
    {
        if ($this->flags & self::FLAG_NO_CLEAR) {
            return 1;
        }

        return 0;
    }

}