#!/usr/bin/env bash

# Append mysql configuration into "res_config_mysql.conf" file.
echo " 
dbhost = ${DB_HOST} 
dbname = ${DB_DATABASE} 
dbuser = ${DB_USERNAME} 
dbpass = ${DB_PASSWORD} 
dbport = ${DB_PORT} 
dbcharset = utf8 
requirements=warn ; or createclose or createchar 
" >> /etc/asterisk/res_config_mysql.conf