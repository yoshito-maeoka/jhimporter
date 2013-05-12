<?php

/**
* The class for searching in Twitter.(Twitter API 1.1)
* Using codebird-php Library
* https://github.com/mynetx/codebird-php
*/

///prepare secret file
require_once("keyconfig.php");

require_once("codebird.php");

///store data to data objects
require_once("PostData.php");

class TwCrawler
{
    //hashtag
    public $tw_hashtag = "hashtagg";
    public $tw_get_postcount = 1;

    //lastpostid. check for if new post available
    //it must be saved and loaded from file.
    public $tw_lastpostid = 12345;

    ///arrays for search result
    ///maybe 2d array is better??
    /*
    (
        (twitter,twlevel,postid,srcname,postdate,text,mediaurl,geox,goey,cityname,cntname),
        (),,,,
    )
    */
    public $tw_crawler_result = array();
    public $tw_crawler_result_sum = array();
    public $postdata_array = array();

    /*
    public $twpostids = array(0);
    public $twsrcename = array("twitter");
    public $twusrscreennames = array("scrname");
    public $twposteddatesUnix = array(0);
    public $twposttexts = array("text");    
    public $twmediaurls = array("url");
    public $twgeocodes = array(
            array(0),
            array(0)
            );
    public $twcntnames = array("countryname");
    public $twcitynames = array("cityname");

    //set level tags for importing to wordpress. 
    //0:geo + media
    //1.post has no geo or no media 
    public $twlvtags = array(0);
    */

    //public $cb;

    
    /**
     * constructor
     * actually ... the constructor of php class should be ___construct() ...
     */


    public function TwCrawler()
    {
        ///maybe here chk last post id?
        //lastidchk();
    }

    /**
     * lastid_chk
     * check latest checked ID from file
     * 
     */

    public function lastid_chk(){

            $fp = fopen("twlastid.txt", "r");
            if(!$fp)
            {
                print("couldn't open file");
                print("<br />");
                
            }else{
                while ($line = fgets($fp)) {
                    if($line != NULL){
                    print("line");    
                    $this->twlastpostid = $line;        
                    print("<br />");
                    print($line);
                    print("<br />");
                    }
                }
                fclose($fp);
            }
    }

    /**
     * lastid_save
     * save latest checked ID to file
     * 
     */

    public function lastid_save($wrstring){

            $fp = fopen("twlastid.txt", "w");
            if(!$fp)
            {
                print("couldn't open file");
                print("<br />");
            }else{
                fwrite($fp, $wrstring);
                fclose($fp);
                print("last id saved");
                print("<br />");
            }

    }

    /**
     * tw_search
     * search query to twitter search API
     * 
     * @return Array tw_crawler_result_sum
     */

    public function tw_search() 
    {   
        ///check last checked ID
        //$this->lastidchk();
       
        Codebird::setConsumerKey(TW_CONSUMER_KEY, TW_CONSUMER_SECRET);
        //Codebird::setConsumerKey("TW_CONSUMER_KEY", TW_CONSUMER_SECRET);
        $cb = Codebird::getInstance();
        $cb->setToken(TW_ACCESS_TOKEN, TW_ACCESS_TOKEN_SECRET);

        /*
        var_dump($cb); 
        print("<br />");
        print("<br />");
        print("<br />");
        */

        /*
        $params=array(
        'q' => $this->myhashtag,
        'since_id' => $this->twlastpostids  327387577923534848  327385430343430144
        );*/
        
        //$this->twlastpostid = 330070226500718592;

        ///set search query parameter
        $params=array(
        'q' => $this->tw_hashtag,
        //'q' => "#fogindomobon",
        //'q' => "#jhdevtest",

        //$g_hashtag = "#fogindomobon";
        //$g_hashtag = "#jhdevtest";
        
        //'count' => $this->twgetpostcount
        //'since_id' => 1,
        //'since_id' => $this->twlastpostid,
        'count' => 50
        );

        ///get tweets
        $tweets = (array) $cb->search_tweets($params);
        ///here must catch error from tw server
        ///https://dev.twitter.com/docs/error-codes-responses


        if($tweets["httpstatus"] != 200){
            print("Something wrong with Twitter: ");
            print($tweets["httpstatus"]);
            print("<br />");
            print("<br />");


        }else{
            echo count($tweets);


            print("<pre>");
            var_dump($tweets);  
            print("</pre>");
            array_pop($tweets);//cut last (status code)

            $tw_lvtag = 0;

            $tw_statuses_0 = $tweets["statuses"];
            $post_numid = 1;

            ///count if there is new post
            if(count($tw_statuses_0)>0){
                $last_id_str = $tw_statuses_0[0]->id;
                $this->lastid_save($last_id_str);
                }
            ///extract individual information from twitter search result
            for ($i = 0; $i<count($tw_statuses_0); $i++){
                $post_numid = $i;
            
                $tw_statuses_1 = $tw_statuses_0[$post_numid]->entities->media;
                $tw_mediaurl = $tw_statuses_1[0]->media_url;

                $tw_text = $tw_statuses_0[$post_numid]->text;

                $tw_posteddate_str = $tw_statuses_0[$post_numid]->created_at;
                $tw_posteddate = strtotime($twposteddatestr);

                $tw_username = $tw_statuses_0[$post_numid]->user->screen_name;

                $tw_postid = $tw_statuses_0[$post_numid]->id;

                //$twplace = $tw_statuses_0[0]->user->place;

                $tw_cityname = $tw_statuses_0[$post_numid]->place->name;
                $tw_cntname = $tw_statuses_0[$post_numid]->place->country;

                $tw_geocoord = $tw_statuses_0[$post_numid]->coordinates->coordinates;

                /*
                print("geo");
                print("<br />");
                print("<pre>");
                var_dump($tw_geocoord);  
                print("</pre>");
                */

                if($tw_geocoord != NULL){
                    $tw_geo0coord_x = $tw_geocoord[0];
                    $tw_geo0coord_y = $tw_geocoord[1];

                }else{
                    $tw_geocoord = $tw_statuses_0[$post_numid]->place->bounding_box->coordinates;
                    if($tw_geocoord != NULL){
                        $tw_geo0coord_x = ($tw_geocoord[0][0][0]+$tw_geocoord[0][1][0]+$tw_geocoord[0][2][0]+$tw_geocoord[0][3][0])*0.25;
                        $tw_geo0coord_y = ($tw_geocoord[0][0][1]+$tw_geocoord[0][1][1]+$tw_geocoord[0][2][1]+$tw_geocoord[0][3][1])*0.25;
                    }
                /*
                print("geo");
                print("<br />");
                print("<pre>");
                var_dump($tw_geocoord);  
                print("</pre>");
                */

                }

                ///check if a tweet has photo and geo data
                if($twmediaurl == NULL){
                    $tw_lvtag = 1;
                }else if($twgeocoord == NULL){
                    $tw_lvtag = 1;
                }else{
                    $tw_lvtag = 0;
                }
        
                
                $this->postdata_array[$i] = new PostData(array(
                "servicename" => "twitter",
                "level" => $tw_lvtag,
                "id" => $tw_postid,
                "username" => $tw_username,
                "postdate" => $tw_posteddate_str,
                "text" => $tw_text,
                "mediaurl" => $tw_mediaurl,
                "geox" => $tw_geo0coord_x,
                "goey" => $tw_geo0coord_y,
                "cityname" => $tw_cityname,
                "cntname" => $tw_cntname
                ));

                /*
                ///add information to array
                array_push($this->tw_crawler_result, "twitter");
                array_push($this->tw_crawler_result, $tw_lvtag);
                array_push($this->tw_crawler_result, $tw_postid);
                array_push($this->tw_crawler_result, $tw_posteddate_str);
                array_push($this->tw_crawler_result, $tw_username);
                array_push($this->tw_crawler_result, $tw_text);
                array_push($this->tw_crawler_result, $tw_mediaurl);
                array_push($this->tw_crawler_result, $tw_geo0coord_x); //long WE
                array_push($this->tw_crawler_result, $tw_geo0coord_y); //lat NS
                array_push($this->tw_crawler_result, $tw_cityname);
                array_push($this->tw_crawler_result, $tw_cntname);
                
                array_push($this->tw_crawler_result_sum,$this->tw_crawler_result);

                ///clear array
                $this->tw_crawler_result = array();
                */

        }
        
        /*
        for($i = 0; $i<count($tw_statuses_0); $i++){
            print("<br />");
            print("-++");
            print("<br />");
            for($j = 0; $j<11; $j++){
            print($this->twcrawlerresultsum[$i][$j]);
            print("-_-");
            }
            
        }
        */
    return $this->postdata_array;
        }

        
    }
}

?>