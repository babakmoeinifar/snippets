#!/usr/bin/php -q
<?php

        /****************************************************************************
        /                                                                                                                                            *
        /       All the logs of this program will be saved in Sibsib file if it is              *
        /       run automatically by the machine.                                                                               *
        /                                                                                                                                            *
        /       It has also been tried to record all operational steps including                *
        /       connecting to Basalamiha server,        downloading files, creating a           *
        /       special folder, moving to a special folder, deleting old folders                *
        /       if needed, all in the database named Basalamiha and the backup table.   *
        /                                                                                                                                            *
        *///////////////////////////////////////////////////////////////////////////*

include("sqli.php");
include("sendsms.php");
include ("mail/send_email.php");

date_default_timezone_set("Asia/Tehran");
$td = date("Y-m-d");

$bk_dir = "/home/bslmBK/backup/";
$threshold = 75; //This variable specifies the minimum percentage that the occupied disk space can have.

update_data('insert');
$lnid = update_data('lnid');//new record on database and getting last nid from it.

$host = '31.214.175.11';        $user= "backup@basalamiha.com"; $psw = "+Qf[VmY.W)~G";
$start = microtime(true);

//***********  STAGE 1   **************//
shell_exec ("ftp -inv >.STDOUT 2>.ERROUT $host <<EOF
user $user $psw
get $td-db.zip
get $td-source.zip
get $td-source-public.zip
bye
EOF");

        $ftp_std_out = shell_exec("cat .STDOUT");
        $ftp_err_out = shell_exec("cat .ERROUT");       shell_exec("rm -f .ERROUT");

        //update database
        $disk_usage = shell_exec("df -H | grep -iw sda1 | cut -d ' ' -f 16");
        update_data('update',$lnid,'diskUsage',$disk_usage);

        $duration = calc_duration($start);
        update_data('update',$lnid,'duration',$duration);

        $fileCount = shell_exec("ls -l $td* 2>/dev/null | wc -l");
        $totalSize = shell_exec("du -ch $td* 2>/dev/null | grep -iw total | cut -f 1");
        update_data('update',$lnid,'fileCount',$fileCount);
        update_data('update',$lnid,'totalSize',$totalSize);

        //check to whether error occuring  to ftp connection or not.
        if ($ftp_err_out) {
                //update database
                update_data('update',$lnid,'operation',"backup failed!");
                update_data('update',$lnid,'details',"$ftp_err_out");
                //send sms
                $msg = send_sms("09356165157","[Bslmiha Backup Failed!] \n $ftp_err_out");
                send_sms("09191518987","[Bslmiha Backup Failed!] \n $ftp_err_out");
                update_data('update',$lnid,'sms',$msg);
                //send email
                send_email("[Bslmiha Backup Failed!]","<h1 style='background-color:Tomato;'>Bslmiha Backup Failed!</h1><br><b>Details:</b><br>".$ftp_err_out);

                echo ("[".date("H:i:s")."] [Bslmiha Backup Failed!]\n $ftp_err_out\n");         exit();

//***********  STAGE 2   **************//
        } else {
                //this state means backup downloading has been done successfully.
                shell_exec ("mkdir $bk_dir"."BK-$td 2>.ERR_MKDIR");
                $ERR_MKDIR = shell_exec("cat .ERR_MKDIR");
                shell_exec("rm -f .ERR_MKDIR");

                if ($ERR_MKDIR){
                        //update database
                        update_data('update',$lnid,'operation',"backup problem!");
                        update_data('update',$lnid,'details',"$ERR_MKDIR");
                        //send sms
                        $msg = send_sms("09356165157","[Bslmiha Backup Problem!] \n $ERR_MKDIR");
                        send_sms("09191518987","[Bslmiha Backup Problem!] \n $ERR_MKDIR");
                        update_data('update',$lnid,'sms',$msg);

                        send_email("[Bslmiha Backup Problem!]","<h1 style='background-color:Orange;'>Bslmiha Backup Problem!</h1><br><b>Details:</b><br>".$ERR_MKDIR);

                        echo ("[".date("H:i:s")."] [Bslmiha Backup Problem!]\n $ERR_MKDIR\n");          exit();
                } else {

//***********  STAGE 3   **************//
                        shell_exec ("mv $td* $bk_dir"."BK-$td 2>.ERR_MV");
                        $ERR_MV = shell_exec("cat .ERR_MV");
                        shell_exec("rm -f .ERR_MV");

                        if ($ERR_MV){
                                //update database
                                update_data('update',$lnid,'operation',"backup problem!");
                                update_data('update',$lnid,'details',"$ERR_MV");
                                //send sms
                                $msg = send_sms("09356165157","[Bslmiha Backup Problem!] \n $ERR_MV");
                                send_sms("09191518987","[Bslmiha Backup Problem!] \n $ERR_MV");
                                update_data('update',$lnid,'sms',$msg);

                                send_email("[Bslmiha Backup Problem!]","<h1 style='background-color:Orange;'>Bslmiha Backup Problem!</h1><br><b>Details:</b><br>".$ERR_MV);

                                echo ("[".date("H:i:s")."] [Bslmiha Backup Problem!]\n $ERR_MV\n");             exit();

//***********  STAGE 4   **************//
                        } else {
                                //in this section backup downloading and transfering to own directory finished without any problems.
                                update_data('update',$lnid,'operation',"backup done.");
                                update_data('update',$lnid,'details',"$ftp_std_out");

                                $del_status = leave_oldest_files($disk_usage,$threshold,$bk_dir,$lnid);
                                //send sms
                                if ($del_status == "No-Del"){
                                        $msg = send_sms("09356165157","Bslmiha Backup Done. \n [BK-$td]");
                                        send_sms("09191518987","Bslmiha Backup Done. \n [BK-$td]");
                                        $email_info_delete = "";
                                } else {
                                        $msg = send_sms("09356165157","Bslmiha BK Done($del_status)\n [BK-$td]");
                                        send_sms("09191518987","Bslmiha BK Done($del_status)\n [BK-$td]");
                                        $email_info_delete = "<b>Disk Usage After Delete: </b>".shell_exec("df -H | grep -iw sda1 | cut -d ' ' -f 16")."<br>";
                                }
                                //update database
                                update_data('update',$lnid,'sms',$msg);

                                $ftp_std_out = str_replace("\n","<br>",shell_exec("cat .STDOUT"));//preparing to send email and approperaite to show in email body!
                                        shell_exec("rm -f .STDOUT");

                                //send email
                                send_email("[Bslmiha Backup Done.]","<h1 style='background-color:MediumSeaGreen;'>Bslmiha Backup Done.</h1>
                                <br>[BK-$td]<br>
                                <b>File Count: </b>$fileCount<br>
                                <b>Total Size: </b>$totalSize<br>
                                <b>Duration: </b>$duration<br>
                                <b>Disk Usage: </b>$disk_usage<br>
                                <b>Info Delete: </b>$del_status<br>
                                $email_info_delete
                                <b>SMS: </b>$msg<br><br>
                                <b>Details: </b><br>$ftp_std_out");

                                echo ("\n[".date("H:i:s")."] [Bslmiha Backup Done!] [BK-$td] [$del_status]\n");

                                weekly_report();

                                exit();
                        }
                }
        }


function calc_duration($start){

        $time_elapsed_secs = microtime(true) - $start;
        $hours = (int)($time_elapsed_secs/60/60);
        $minutes = (int)($time_elapsed_secs/60)-$hours*60;
        $seconds = (int)$time_elapsed_secs-$hours*60*60-$minutes*60;
        $duration = $hours.':'.$minutes.':'.$seconds;

        return $duration;
}

function weekly_report(){

        if (date('w') == 5){
                $failed_count = update_data('failed');
                $problem_count= update_data('problem');
                $done_count = update_data('success');

                $msg = "Bslmiha Weekly Backup Summary\n
                        Successfull:$done_count\n
                        Problem:$problem_count\n
                        Failed:$failed_count";

                $s1 = send_sms('09356165157',$msg);
                $s2 = send_sms('09191518987',$msg);

                echo ("\n$s1\n$s2\n$msg\n");
        }
}

function leave_oldest_files($disk_usage,$threshold,$bk_dir,$lnid){

        $result = "No-Del";             $dirs_name = "";

        while((substr($disk_usage,0,2)) > $threshold){

                $oldest_dir = shell_exec("cd $bk_dir && ls -td BK* | tail -1");
                shell_exec("rm -fr $bk_dir$oldest_dir 2>ERR_DEL");

                $err_del = shell_exec("cat ERR_DEL");
                shell_exec("rm -rf ERR_DEL");

                if ($err_del){
                        update_data('update',$lnid,'infoDelete',$err_del);
                        send_sms("09356165157","[Bslmiha Backup Deleting Failed] \n $err_del");
                        //we have to break loop while
                        $disk_usage = $threshold;
                        $result = "Err-Del!";

                } else {
                        $disk_usage = shell_exec("df -H | grep -iw sda1 | cut -d ' ' -f 16");
                        $dirs_name = $oldest_dir.' '.$dirs_name;

                        update_data('update',$lnid,'infoDelete',"$dirs_name REMOVED.");
                        update_data('update',$lnid,'afterDelete',$disk_usage);
                        $result = "Del-OK!";
                }
        }
        return $result;
}

?>
