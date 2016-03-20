<?php

    //======================================================================
    // The singleton Database class
    //======================================================================

    class Database
    {
        // The static DB instance field. Is null until the first DB conn is made
        private static $instance = NULL;

        // The MySQL databse parameters
        private static $servername = "localhost";
        private static $username = "root";
        private static $password = "";
        private static $db_name = "music";

        // Constructor does nothing because we only want once DB instance
        private function __construct() {}

        // Cloning does nothing because we only want one Db instance
        private function __clone() {}

        /*
         * Gets a DB instance.
         *
         * @return A new DB connection instance if one has not yet been created
         *         The current DB connecton if one already exists
         */
        public static function getInstance()
        {
            // Check if the current DB instance is set and not NULL
            if (!isset(self::$instance))
            {
                // If it is not set, create the new DB instance
                self::$instance = new mysqli(self::$servername, self::$username, self::$password, self::$db_name);

                if(self::$instance->connect_error)
                {
                    die("Connectin Failed:".self::$instance->connect_error);
                }
            }

            return self::$instance;
        }

        /*
         * Dummy function to test thigns out
         */
        public function printText()
        {
            echo "This is the DB's printText function <br>";
        }
    }
?>
