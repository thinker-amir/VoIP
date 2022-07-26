#!/usr/bin/env bash

# Append mysql configuration
echo " 
[mysql_asterisk]
Description = MySQL Asterisk
Driver = MariaDB Unicode
Database = ${DB_DATABASE}
Server = ${DB_HOST} 
User = ${DB_USERNAME}
Password = ${DB_PASSWORD}
Port = ${DB_PORT}
" >> /etc/odbc.ini

echo " 
[mysql_asterisk]
enabled => yes
dsn => mysql_asterisk
username => ${DB_USERNAME}
password => ${DB_PASSWORD}
pre-connect => yes
sanitysql => select 1
max_connections => 20
connect_timeout => 5
negative_connection_cache => 600
" >> /etc/asterisk/res_odbc.conf

# Run migrations
sleep 10        # use sleep to ensure that the database is ready
sh ./custom/scripts/migrate_asterisk_tables.sh