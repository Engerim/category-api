#!/usr/bin/env bash

set -xe

if  grep -q "export LOCAL_USER_ID=" ~/.zshrc; then
    true
else
    echo 'export LOCAL_USER_ID=$(id -u)' >> ~/.zshrc
fi

if grep -q "export LOCAL_USER_ID=" ~/.profile; then
    true
else
    echo 'export LOCAL_USER_ID=$(id -u)' >> ~/.profile
fi

if [[ $(uname -s) == "Darwin" ]]; then
    if grep -q "export LOCAL_USER_ID=" ~/.bash_profile; then
        true
    else
        echo 'export LOCAL_USER_ID=$(id -u)' >> ~/.bash_profile
    fi
fi