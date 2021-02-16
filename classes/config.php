<?php
    /*
     * Class Config
     * config.php
     */

    class Config {

        private $_data;

        private static $_instance;

        public function __construct() {
            $json = file_get_contents( CONFIG_DIR . 'configuration.json' );
            $this->_data = json_decode( $json, true );
        }

        public static function get_instance(): Config {
            if ( self::$_instance === null ) {
                self::$_instance = new Config();
            }

            return self::$_instance;

        }

        public function get ( $key = null ) {
            if ( !isset( $this->_data[$key] ) ) {
                throw new Exception( "Key {$key} not found." );
            }

            return $this->_data[$key];

        }
    }