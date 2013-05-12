<?php
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
    echo $e->getMessage();
}


echo '<h2>ブログの基本情報</h2>';
echo '<pre>';
var_dump($infos);
echo '</pre>';
 
// blog ID 基本的に1。
// 3系では複数のブログが管理できるようなので増えるのかも？
$blog_id = $infos[0]['blogid'];
 
// 投稿データの作成
$contents = array(
    'title'             => 'テスト投稿',
    'categories'        => array('インテリア', '建築'),
    'custom_fields'     => array(array('key' => 'field_key1', 'value' => 'field_key1のvalue'),
                                 array('key' => 'field_key2', 'value' => 'field_key2のvalue')
                                ),
    'description'       => 'テスト投稿の本文',
    'dateCreated'       => null,
    'wp_slug'           => 'xml-rpc-testpost',
    'mt_allow_comments' => null,
    'mt_allow_pings'    => null,
    'mt_convert_breaks' => null,
    'mt_text_more'      => null,
    'mt_excerpt'        => 'Wordpress XML-RPCのテスト',
    'mt_keywords'       => array('icon', 'インスピレーション', 'どらえもん'),
    'mt_tb_ping_urls'   => null,
);
 
// 公開設定
$publish = false;
 
// 投稿を実行
$result = $client->call('metaWeblog.newPost',
                        array($blog_id, $user, $passwd, $contents, $publish)
                        );
 
echo '<h2>実行結果 成功するとPost IDが返ってくる</h2>';
echo '<pre>';
echo $result;
echo '</pre>';
 
// 最新の記事を取得
// この構造見るとmetaWeblog.newPostで拡張されてるものがわかる
// 多分wp_author_idとかも指定できるとおもわれ。
$new_post = $client->call('metaWeblog.getRecentPosts',
                           array($blog_id, $user, $passwd, 1)
                          );
 
echo '<h2>最新の投稿データ</h2>';
echo '<pre>';
var_dump($new_post);
echo '</pre>';
?>
</body>
</html>
