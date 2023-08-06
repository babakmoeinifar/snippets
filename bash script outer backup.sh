#! /bin/bash

 

LOGDIR="/var/backup/source/"
BKDIR="/var/backup/bkdir/"
SRVID="root@voip.basalam.com"
echo "backup started at $(date)" > ${LOGDIR}.log

 

rsync ${SRVID}:/etc/ ${BKDIR}etc/ -acvh -e 'ssh -p 2215' >> ${LOGDIR}.log 2> ${LOGDIR}.err
rsync ${SRVID}:/var/spool/cron/ ${BKDIR}cron/ -acvh -e 'ssh -p 2215' >> ${LOGDIR}.log 2> ${LOGDIR}.err
rsync ${SRVID}:/var/lib/asterisk/ ${BKDIR}asterisk/ -acvh -e 'ssh -p 2215' >> ${LOGDIR}.log 2>> ${LOGDIR}.err
rsync ${SRVID}:/var/www/html/dialer/ ${BKDIR}dialer/ -acvh -e 'ssh -p 2215' >> ${LOGDIR}.log 2>> ${LOGDIR}.err
mkdir -p ${BKDIR}monitor/$(date -d "1 day ago" '+%Y/%m/%d');

 

if [ $? -eq 0 ]; then
        rsync ${SRVID}:/var/spool/asterisk/monitor/$(date -d "1 day ago" '+%Y/%m/%d') ${BKDIR}monitor/$(date -d "1 day ago" '+%Y/%m') -acvh -e 'ssh -p 2215' >> ${LOGDIR}.log 2>>${LOGDIR}.err
fi
 

echo $(du -sh ${BKDIR}monitor/$(date -d "1 day ago" '+%Y-%m-%d')) >> ${LOGDIR}.datausage
echo "backup finished at $(date)" >> ${LOGDIR}.log
 

msg="Backup VoIP Done!"
mobile=09356165157
 

curl -X POST -F "text=$msg" https://sms.api.pendarino.com/api/v1/client/79A9FB68-7F5B-4A46-8661-27D962E3767B/send/$mobile/