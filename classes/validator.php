<?php
    /*
    * Class Validator
    * validator.php
    */

    class Validator {

        /**
         * @param null $username
         * @return false|int
         */
        public static function validate_username ( $username = null ) {
            return preg_match( '/^[a-z0-9]{6,16}$/i', $username );

        }

        /**
         * @param string $name
         * @return false|int
         */
        public static function validate_name ( $name = "" ) {
            return preg_match( '/^[A-Z \'.-]{3,50}$/i', $name );

        }

        /**
         * @param string $mobile
         * @return false|int
         */
        public static function validate_mobile ( string $mobile ) {
            return preg_match( '/2637[13478][0-9]{7}$/i', $mobile );
        }

    }