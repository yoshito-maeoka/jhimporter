<?php
/**
* The class for store result
* 
* 
*/
class PostData {

    /*
    public $post_servicename;
    public $post_level;
    public $post_id;
    public $post_username;
    public $post_date;
    public $post_text;
    public $post_mediaurl;
    public $post_geo_x;
    public $post_geo_y;
    public $post_cityname;
    public $post_countryname;
    */

    private $_var = array(
        "servicename" => null,
        "level" => null,
        "id" => null,
        "username" => null,
        "postdate" => null,
        "text" => null,
        "mediaurl" => null,
        "geox" => null,
        "goey" => null,
        "cityname" => null,
        "cntname" => null
    );

    public function __construct($args){
        $this->_var = $args;
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->_var)) {
            return $this->_var[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_var)) {
            $this->_var[$name] = $value;
        }
    }
}


?>