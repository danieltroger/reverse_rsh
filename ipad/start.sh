#!/bin/sh
while true
do
  mode=$(activator current-mode)
  if [ "$mode" -ne "lockscreen" ]
  then
    reachable=$(sbnetwork vps.natur-kultur.eu)
    if [ "$reachable" -ne "FAILED" ]
    then
      php client.php
    fi
  fi
  sleep 1
done
