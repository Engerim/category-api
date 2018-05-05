#!/usr/bin/env bash

if [ -z ${LOCAL_USER_ID+x} ]; then
    echo "Starting as root"
    exec "$@"
else
  echo "Starting with UID : ${LOCAL_USER_ID}"
  usermod -u ${LOCAL_USER_ID} www-data
  chown -R www-data:www-data /app
  if [ $1 != "/usr/bin/supervisord" ]; then
    exec gosu www-data "$@"
  else
    exec "$@"
  fi
fi
