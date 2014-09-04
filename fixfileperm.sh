#!/bin/bash
setfacl -R -m u:apache:rwx -m u:digger:rwx app/cache app/logs 
setfacl -dR -m u:apache:rwx -m u:digger:rwx app/cache app/logs 

