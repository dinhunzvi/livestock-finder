create database if not exists livestock_finder charset = latin1;

create user 'livestock_finder'@'localhost'
identified by 'sMRgs2CSFm^D95_=';

grant all on livestock_finder.*
to 'livestock_finder'@'localhost';

create table if not exists tbl_users (
    user_id smallint unsigned not null auto_increment,
    first_name varchar( 50 ) not null,
    last_name varchar( 50 ) not null,
    email varchar( 60 ) unique not null,
    username varchar( 16 ) unique not null,
    deleted enum( 'yes', 'no' ) not null default 'no',
    user_pass char( 255 ) not null,
    primary key ( user_id )
);