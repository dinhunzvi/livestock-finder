<?php

    /**
     * @param string $page
     * redirect user to the specified page
     */
    function redirect( string $page ) {
        if ( $page !== null ) {
            header( "Location: {$page}" );
        }
    }

    /**
     * @param array $data
     * @param string $template
     * @return false|string
     */
    function make( array $data, string $template ) {
        extract( $data ); // extract the variables from $data into current symbol table

        ob_start(); // start output buffering

        include_once EMAILS_DIR . "{$template}.php"; // include the email template

        $contents = ob_get_contents(); // assign contents of output buffering to $contents

        ob_end_clean(); // end output buffering

        return $contents;

    }