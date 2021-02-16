<?php
    /*
     * Class Session
     * session.php
     */

    class Session {

        /**
         * @param string $name
         * @return mixed
         */
        public static function get_session ( string $name ) {
            return $_SESSION[$name];
        }

        /**
         * @param string $name
         * @param int $value
         * @return int
         */
        public static function put ( string $name, int $value ): int {
            return $_SESSION[$name] = $value;
        }

        /**
         * @param string $name
         * @return bool
         */
        public static function exists( string $name ): bool
        {
            return isset( $_SESSION[$name] );
        }

        /**
         * @param string $name
         */
        public static function delete ( string $name ) {
            if ( self::exists( $name ) ) {
                unset( $_SESSION[$name] );
            }
        }

    }