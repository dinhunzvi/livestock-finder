<?php
    /*
     * Class Mailer
     * mailer.php
     */

    use PHPMailer\PHPMailer\PHPMailer;

    class Mailer {

        protected PHPMailer $_mailer;

        /**
         * Mailer constructor.
         */
        public function __construct() {
            $this->_mailer = new PHPMailer();
            $this->setup();
        }

        /**
         * @throws Exception
         */
        public function setup() {
            $_email_settings = Config::get_instance()->get( 'email_settings' );
            $this->_mailer->isSMTP();
            $this->_mailer->Mailer = 'smtp';
            $this->_mailer->SMTPAuth = true;
            $this->_mailer->SMTPSecure = 'ssl';
            $this->_mailer->SMTPOptions = array( /** used in cases where server uses self-signed ssl certificate */
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => false

                )
            );
            $this->_mailer->Host = $_email_settings['server'];
            $this->_mailer->Port = $_email_settings['smtp_port'];
            $this->_mailer->Username = $_email_settings['username'];
            $this->_mailer->Password = $_email_settings['password'];
            $this->_mailer->isHTML( true );
            $this->_mailer->FromName = $_email_settings['sender'];
            $this->_mailer->From = $_email_settings['username'];
        }

        /**
         * @param array $data
         * @return bool
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function send( $data = [] ): bool
        {
            $this->_mailer->addAddress( $data['to'], $data['name'] );
            $this->_mailer->Subject = $data['subject'];
            $this->_mailer->Body = make( [ 'data' => $data['body'] ], $data['template'] );

            return $this->_mailer->send();

        }

    }