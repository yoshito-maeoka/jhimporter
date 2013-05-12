<?php

/**
 * this is the jewery hunting crawler main 
 * 
 */


///todo 130509
/// timer chk
/// mail text
/// 



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

$result_array = array_merge($result_array,$tw_crawl->tw_search());


///if there is post then send mail.
/*
if(count($result_array)>0){

    if (mb_send_mail(
        "info@ahonda.org", 
        "TEST MAIL", 
        "This is a test message.", 
        "From: info@ahonda.org")) {
        echo "mail sendet";
    } else {
        echo "fail to send mail";
    }
}
*/

///store data to data objects
require_once("PostData.php");


for($i = 0; $i<count($result_array); $i++){
    $postdata_array[$i] = new PostData(array(
        "servicename" => $result_array[$i][0],
        "level" => $result_array[$i][1],
        "id" => $result_array[$i][2],
        "username" => $result_array[$i][3],
        "postdate" => $result_array[$i][4],
        "text" => $result_array[$i][5],
        "mediaurl" => $result_array[$i][6],
        "geox" => $result_array[$i][7],
        "goey" => $result_array[$i][8],
        "cityname" => $result_array[$i][9],
        "cntname" => $result_array[$i][10]
        ));
}


///show result (for debug)
for($i = 0; $i<count($postdata_array); $i++){
    print("-+++");
    print("<br />");
    print($postdata_array[$i]->__get("text"));
    print("<br />");
}



for($i = 0; $i<count($result_array); $i++){
            print("<br />");
            print("-++");
            print("<br />");
            for($j = 0; $j<11; $j++){
            print($result_array[$i][$j]);
            print("-_-");
            }
            
        }

///here comes wordpress export??


?>