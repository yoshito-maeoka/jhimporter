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


///prepare crawler(s)

///declare global parameters for universal usage
///in case for other services,such twitpic,instagram,etc

//$g_hashtag = "#fogindomobon";
$g_hashtag = "#jhdevtest";
$g_get_postcount = 1;

////maybe here chk flag if it must be check, by server error etc.


///prepare for twitter
require_once("TwCrawler.php");
$tw_crawl = new TwCrawler;

///initialize for twitter crawler

$tw_crawl->tw_hashtag = $g_hashtag;
$tw_crawl->tw_get_postcount = $g_get_postcount;


//fire method

//$result_array = $tw_crawl->tw_search();
$result_array = array();

/* TIMER CHECK. DON'T ERASE THIS. NOT GARBAGE!! 
if($tw_crawl->tw_chk_timer() == 1){
    $result_array = array_merge($result_array,$tw_crawl->tw_search());
}
*/

$result_array = array_merge($result_array,$tw_crawl->tw_search());


///if there is post then send mail.

/* SEND NOTIFICATION MAIL.DON'T ERASE THIS. NOT GARBAGE!!
if(count($result_array)>0){
    my_send_mail(
    "mail@mail.com",
    "NEW: ".count($result_array),
    "last: ". $result_array[0]->__get("postdate"));
}
*/


///show result (for debug)
for($i = 0; $i<count($result_array); $i++){
    print("-+++");
    print("<br />");
    print($result_array[$i]->__get("text"));
    print("<br />");
}


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

///here comes wordpress export??



///function

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