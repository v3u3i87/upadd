#!/bin/bash

#set up the service path

ps aux | grep 'upadd_http' | awk '{print $2}' | xargs kill -9
