#!/bin/bash

cd /application

npm install

exec /tini -- "$@"