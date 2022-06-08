<?php

namespace Aligent\AsyncEvents\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime;

class AsyncEventCleanSubscriberLogs
{
    private $resourceConnection;
    private $scopeConfig;
    private $dateTime;

    /**
     * @param ResourceConnection $resourceConnection
     * @param ScopeConfigInterface $scopeConfig
     * @param DateTime $dateTime
     */
    public function __construct(
        ResourceConnection   $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        DateTime             $dateTime
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->dateTime = $dateTime;
    }

    /**
     * Deletes all logs older than specified days using an SQL command
     *
     * @return void
     */
    public function cleanSubscriberLogs()
    {
        // check if cron is enabled or disabled in system configuration
        $shouldRunCron = $this->scopeConfig->getValue('system/async_events/subscriber_log_cleanup_cron');

        if($shouldRunCron){
            $connection = $this->resourceConnection->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

            // retrieve amount of days for oldest log from system config
            $timePeriodInDays = (string)$this->scopeConfig->getValue('system/async_events/subscriber_log_cron_delete_period');

            // creates a date that is x amount of days in the past and checks if the log record is older than that
            $now = $this->dateTime->formatDate(time());
            $periodDate = $this->dateTime->formatDate(strtotime("$now -$timePeriodInDays days"));

            // deletes all logs older than period date
            $connection->delete("async_event_subscriber_log", ["created < ?" => $periodDate]);

            $this->resourceConnection->closeConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        }
    }
}
