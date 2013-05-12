<?php
class SearchResult {
    private $_var = array(
    	"twitter" => null,
		"twlevel" => null,
		"postid" => null,
		"srcname" => null,
		"postdate" => null,
		"text" => null,
		"mediaurl" => null,
		"geox" => null,
		"goey" => null,
		"cityname" => null,
		"cntname" => null
	);

    public function __construct($args)　{
		private $_var = $args;
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

/*
 * here comes test part...
 */
$result = new SearchResult(array(
 	"twitter" => ...,
	"twlevel" => ...,
	"postid" => ...,
	"srcname" => ...,
	"postdate" => ...,
	"text" => ...,
	"mediaurl" => ...,
	"geox" => ...,
	"goey" => ...,
	"cityname" => ...,
	"cntname" => ...
));

?>