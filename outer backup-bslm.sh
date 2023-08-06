#! /bin/bash
#####   0 5 * * * /path/to/script.sh   daily###

USER=basalamiha
SERVER=62.60.162.225
DIR=/home/basalamiha/backup
LOGDIR="/var/backup/source/"

echo "backup started at $(date)" > ${LOGDIR}.log

# Local backup directory
BACKUP_DIR=/home/bslmBK/backup

# Connect to remote server and download zips 
scp $USER@$SERVER:$DIR/*.zip $BACKUP_DIR

echo "backup done at $(date)" > ${LOGDIR}.log

# Get list of zips and sort by modified time
zips=($BACKUP_DIR/*.zip)
sorted_zips=($(ls -t $BACKUP_DIR/*.zip))

# Keep only the 6 newest files
keep=6
for zip in ${sorted_zips[@]:$keep}; do
  rm $zip
done
 
msg="Backup Bslmiha Done!"
mobile=09187643303
curl -X POST -F "text=$msg" https://sms.api.pendarino.com/api/v1/client/79A9FB68-7F5B-4A46-8661-27D962E3767B/send/$mobile/