<?php

/**
* ErrorReporter
* make custom error log file 
* send alert mail
*/

class ErrorReporter {
    
    /**
     * e_logger
     * log error to file
     * 
     */

    public function e_logger($error_text,$error_code = 0) {

    $fp = fopen("myerrorlog.txt", "a+");
    if(!$fp)
        {
            //print("couldn't open file");
            print("<br />");
        }else{
            $time_stamp = date("Ymd").":".date("HisO");
            $message = $time_stamp.",".$error_code.",".$error_text."\n";
            fwrite($fp, $message);
            fclose($fp);
            //print("last id saved");
            //print("<br />");
        }
    }

    /**
     * error_mail_sender
     * send error mail
     * 
     */
    public function error_mail_sender($error_text,$mail_adresse = "default@mail.com",$error_code = 0){

        mb_language("uni");
        mb_internal_encoding("UTF-8");

        $time_stamp = date("Ymd").":".date("HisO");
        $message = $time_stamp.",".$error_code.",".$error_text."\n";

        $mail_title = $message; 
        $mail_text = $message;

        if (mb_send_mail(
            $mail_adresse, 
            $mail_title, 
            $mail_text
            )) {
            //echo "mail sendet";
        } else {
            //echo "fail to send mail";
        }
    }

}
?>