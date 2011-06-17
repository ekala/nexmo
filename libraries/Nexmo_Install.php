<?php defined('SYSPATH') or die('No direct script access');
/**
 * Nexmo SMS plugin installer
 * @author Nexmo - http://nexmo.com
 * @copyright 
 */
class Nexmo_Install {
	/**
	 * Database object to be used for running the install SQL
	 * @var Database
	 */
	private $db;
	
	/**
	 * Prefix for the database tables
	 * @var string
	 */
	private $table_prefix;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Initialze the database object
		$this->db = new Database();
		
		// Get the table prefix from the datebase config
		$this->table_prefix = Kohana::config('database.default.table_prefix');
	}
	
	/**
	 * Installs the plugin by executing the SQL script for creating the plugins schema
	 */
	public function run_install()
	{
        if (FALSE !== $setup_sql_file = Kohana::find_file('sql', 'install_nexmo', FALSE, 'sql'))
        {
            // Fetch the contents of the file
            $sql = file_get_contents($setup_sql_file);

            // If a table prefix is specified add it to the SQL
            if ($this->table_prefix)
            {
                $find = array(
                    'CREATE TABLE IF NOT EXISTS `',
                    'INSERT INTO `',
                    'ALTER TABLE `',
                    'UPDATE `'
                );

                $replace = array(
                    'CREATE TABLE IF NOT EXISTS `'.$this->table_prefix.'_',
                    'INSERT INTO `'.$this->table_prefix.'_',
                    'ALTER TABLE `'.$this->table_prefix.'_',
                    'UPDATE `'.$this->table_prefix.'_'
                );

                // Rebuild the SQL
                $sql = str_replace($find, $replace, $sql);
            }

			// Split by ; to get the SQL statement for individual tables
			$queries = explode(';', $sql);

			// Execute the individual CREATE statements
			foreach ($queries as $query)
			{
				$this->db->query($query);
			}
		}
	}
	
	/**
	 * Uinstalls the nexmo plugin by dropping its schema objects
	 */
	public function uninstall()
	{
		// Execute the uinstall_nexmo.sql script
	}
}
?>