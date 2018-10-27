#!/bin/bash

#set up the service path

ps aux | grep 'upadd_*' | awk '{print $2}' | xargs kill -9

ps aux | grep 'php*' | awk '{print $2}' | xargs kill -9