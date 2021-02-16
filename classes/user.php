<?php
    /*
     * Class User
     * user.php
     */

    class User {

        private $_data, $_session_name;
        private bool $_is_signed_in = false;
        private Database $_db;
        private array $_where = [];
        private string $_primary_key = 'user_id';
        private string $_table_name = 'tbl_users';

        /**
         * User constructor.
         * @param null $user
         * @throws Exception
         */
        public function __construct ( $user = null ) {
            $this->_db = Database::get_instance();
            $this->_session_name = Config::get_instance()->get( 'admin_session' );

            if ( !$user ) {
                if ( Session::exists( $this->_session_name ) ) {
                    $user = Session::get_session( $this->_session_name );

                    if ( $this->find( $user ) ) {
                        $this->_is_signed_in = true;
                    }
                } else {
                    $this->sign_out();
                }
            } else {
                $this->find( $user );
            }

        }

        /**
         * @return mixed
         */
        public function data() {
            return $this->_data;
        }

        /**
         * @param null $user
         * @return bool
         */
        public function find ( $user = null ): bool
        {
            if ( $user ) {
                $field = is_numeric( $user ) ? $this->_primary_key : 'username';
                $this->_where = [ $field, '=', $user ];

                $data = $this->_db->get( $this->_table_name, $this->_where );

                if ( $data->count() ) {
                    $this->_data = $data->first();

                    return true;
                }
            }

            return false;

        }

        /**
         * @param null $user
         * @return mixed
         */
        public function get_user ( $user = null ) {
            $this->find( $user );

            return $this->data();

        }

        /**
         * @return mixed
         */
        public function get_users() {
            $sql = 'select user_id, email, username, first_name, last_name, active, date_created from ' .
                $this->_table_name . ' order by first_name, last_name';

            return $this->_db->query( $sql )->results();
            
        }

        /**
         * @param null $username
         * @param null $_password
         * @return bool
         */
        public function sign_in ( $username = null, $_password = null ): bool
        {
            $user = $this->find( $username );

            if ( $user ) {
                if ( Hash::verify_password( $_password, $this->data()->user_pass ) ) {
                    Session::put( $this->_session_name, $this->data()->user_id );
                    $this->_is_signed_in = true;

                    return true;

                }
            }

            return false;
        }

        /**
         * @return bool
         */
        public function is_signed_in (): bool
        {
            return $this->_is_signed_in;
        }

        /**
         * delete user session
         */
        public function sign_out() {
            Session::delete( $this->_session_name );
        }

        /**
         * @param array $fields
         * @return bool
         */
        public function insert ( $fields = [] ): bool
        {
            if ( $this->_db->insert( $this->_table_name, $fields ) ) {
                return true;
            }

            return false;

        }

        /**
         * @param null $user_id
         * @param array $fields
         * @return bool
         */
        public function update ( $user_id = null, $fields = [] ): bool {
            $this->_where = [ $this->_primary_key, $user_id ];
            if ( $this->_db->update( $this->_table_name, $this->_where, $fields ) ) {
                return true;
            }

            return false;

        }

    }