<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * EquipTrack database-session safeguard.
 *
 * CodeIgniter loads application session drivers before its bundled driver.
 * We extend the stock CI3 database driver and make sure the configured
 * session table exists before PHP asks the driver to read/write a session.
 * This keeps an existing EquipTrack database working even if ci_sessions
 * was missed during a previous SQL import.
 */
require_once BASEPATH.'libraries/Session/drivers/Session_database_driver.php';

#[\AllowDynamicProperties]
class Session_database_driver extends CI_Session_database_driver
{
    public function __construct(&$params)
    {
        parent::__construct($params);
        $this->ensure_session_table();
    }

    /**
     * Create the CI3 session table when it is missing.
     * The project uses MySQL/MariaDB through mysqli.
     */
    private function ensure_session_table()
    {
        $table = isset($this->_config['save_path'])
            ? (string) $this->_config['save_path']
            : 'ci_sessions';

        // sess_save_path is application configuration, but still validate the
        // identifier before placing it into DDL.
        if ($table === '' || ! preg_match('/^[A-Za-z0-9_]+$/', $table))
        {
            throw new RuntimeException('Invalid database session table name.');
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (
            `id` varchar(128) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
            `data` blob NOT NULL,
            PRIMARY KEY (`id`),
            KEY `ci_sessions_timestamp` (`timestamp`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->_db->query($sql);
    }
}
