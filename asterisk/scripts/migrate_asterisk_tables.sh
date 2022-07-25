#!/usr/bin/env bash

cd ../../contrib/ast-db-manage

ALEMBIC_MYSQL_URL="sqlalchemy.url = mysql://${DB_USERNAME}:${DB_PASSWORD}@${DB_HOST}/${DB_DATABASE}"

sed "s|^sqlalchemy.url.*|${ALEMBIC_MYSQL_URL}|" config.ini.sample > config.ini

alembic -c config.ini upgrade head