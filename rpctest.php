<?php
///test 0

require_once 'jhimporter-config.php';
require 'vendor/autoload.php';

use Zend\XmlRpc\Client;


$uri = JHI_RPC_URI;
$user = JHI_RPC_USER;
$passwd = JHI_RPC_PASSWORD;

?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" dir="ltr" lang="en-US">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" dir="ltr" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" dir="ltr" lang="en-US">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html dir="ltr" lang="en-US">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width" />
<title>rpc test</title>

<?php 
$client = new Client($uri);
 
try {
    $infos = $client->call('wp.getUsersBlogs', array($user, $passwd));
} catch (Zend_Exception $e) {
	echo "<pre>";
    echo $e->getMessage();
	echo "</pre>";
}


echo '<h2>basic info of the blog</h2>';
echo '<pre>';
var_dump($infos);
echo '</pre>';
 
// blog ID, basically '1' is set.
$blog_id = $infos[0]['blogid'];
 
// set posting data
$contents = array(
    'title'             => 'rpc post test',
    'categories'        => array('something category', 'any other category'),
    'custom_fields'     => array(array('key' => 'field_key1', 'value' => 'the value of field_key1'),
                                 array('key' => 'field_key2', 'value' => 'the value of field_key2')
                                ),
    'description'       => 'this is content text',
    'dateCreated'       => null,
    'wp_slug'           => 'xml-rpc-testpost',
    'mt_allow_comments' => null,
    'mt_allow_pings'    => null,
    'mt_convert_breaks' => null,
    'mt_text_more'      => null,
    'mt_excerpt'        => 'Wordpress RPC test, this is excerpt test',
    'mt_keywords'       => array('brabrabra', 'blablabla', 'moinmoin'),
    'mt_tb_ping_urls'   => null,
);

// this post shouldn't be published at first. 
$publish = false;
 
try {
	$result = $client->call('metaWeblog.newPost',
                        array($blog_id, $user, $passwd, $contents, $publish)
                        );
} catch (\Exception $e) {
	echo "<pre>";
    echo $e->getMessage();
	echo "</pre>";
}
 
echo '<h2>post ID, if new post is suceeded</h2>';
echo '<pre>';
echo $result;
echo '</pre>';
 

try {
	$new_post = $client->call('metaWeblog.getRecentPosts',
                           array($blog_id, $user, $passwd, 1)
                          );
} catch (\Exception $e) {
	echo "<pre>";
	echo $e->getMessage();
	echo "</pre>";
}
 
echo '<h2>the newest post</h2>';
echo '<pre>';
var_dump($new_post);
echo '</pre>';
?>
</body>
</html>
