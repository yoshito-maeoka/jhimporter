<?php

/**
 * this is the jewery hunting crawler main 
 * 
 */


///todo 130630
/// // key config
/// key config merge
/// key config load at main
/// // indent
/// //rpc load at main
/// //rpc test

///import key config file
require_once("keyconfig.php");


///prepare crawler(s)

///declare global parameters for universal usage
///in case for other services,such twitpic,instagram,etc

//$g_hashtag = "#fogindomobon";
$g_hashtag = "#jhdevtest";  
//$g_hashtag = "#berlin";
$g_get_postcount = 50;

////maybe here chk flag if it must be check, by server error etc.

///for debug
require_once("ErrorReporter.php");
$ep = new ErrorReporter;

///prepare for twitter
require_once("TwCrawler.php");
$tw_crawl = new TwCrawler;

///initialize for twitter crawler
/// keys for Oauth
$tw_crawl->TW_CONSUMER_KEY = TW_CONSUMER_KEY;
$tw_crawl->TW_CONSUMER_SECRET = TW_CONSUMER_SECRET;
$tw_crawl->TW_ACCESS_TOKEN = TW_ACCESS_TOKEN;
$tw_crawl->TW_ACCESS_TOKEN_SECRET = TW_ACCESS_TOKEN_SECRET;

///for serch query 
$tw_crawl->tw_hashtag = $g_hashtag;
$tw_crawl->tw_get_postcount = $g_get_postcount;



//fire the crawler(s)

$result_array = array();
   
    ///debug mail
    //$ep->error_mail_sender("[jhtest]:jhmailn call",REPO_MAIL_ADDRESS);
    /*
    my_send_mail(
    "info@ahonda.org",
    "[jhimporter]:inchk: ",
    "try chk"
    );
    */

//TIMER CHECK. DON'T ERASE THIS. NOT GARBAGE!! 
/*
if($tw_crawl->tw_chk_timer() == 1){

    ///debug mail
    my_send_mail(
    "mail@mail.mail",
    "[jhimporter]:inchk:1 ",
    "in chk"
    );

    my_merge_array($result_array,$tw_crawl->tw_search());
}
*/


///do once. comment out if using timerchk.
/// merge to result_array with result from other service crawler(for future option)

my_merge_array($result_array,$tw_crawl->tw_search());


///if there is post then send mail.

//SEND NOTIFICATION MAIL.DON'T ERASE THIS. NOT GARBAGE!!

///for debug make texts text
if(count($result_array)>0){

    for($i = 0; $i<count($result_array); $i++){
        $ml_text =$ml_text.$result_array[$i]->__get("postdate").":".$result_array[$i]->__get("text")."\n";
    }

    ///for debug. then send mail
    if(count($result_array)>0){
        my_send_mail(
        REPO_MAIL_ADDRESS,
        "[jhimporter]:NEW: ".count($result_array),
        //"last: ". $result_array[0]->__get("postdate"));
        $ml_text
        );
    }

    ///show result (for debug)
    /*
    for($i = 0; $i<count($result_array); $i++){
    print("-+++");
    print("<br />");
    print($result_array[$i]->__get("text"));
    print("<br />");
    }
    */

    /*
    for($i = 0; $i<count($result_array); $i++){
            print("<br />");
            print("-++");
            print("<br />");
            for($j = 0; $j<11; $j++){
            print($result_array[$i][$j]);
            print("-_-");
            }
            
        }
    */
}

///here comes wordpress export??


/////////functions

/**
* merge_array
* chk array,if not NULL then merge it to result array
* 
* @param array  $array_to    array to merge
* @param array  $array_from  add this array
* 
* 
*/

function my_merge_array($array_to, $array_from){

    if ($array_from != NULL){
    $result_array = array_merge($array_to,$array_from);    
        }
    }

/**
* my_send_mail
* send alert mail
* 
* @param String $mail_adress    email adress to send
* @param String $mail_title     email title
* @param String $mail_text      email text
* 
*/

function my_send_mail($mail_adresse, $mail_title, $mail_text){
    mb_language("uni");
    mb_internal_encoding("UTF-8");

    if (mb_send_mail(
        $mail_adresse, 
        $mail_title, 
        $mail_text
        )) {
        echo "mail sendet";
        } else {
        echo "fail to send mail";
        }
    }


?>