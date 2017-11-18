<?
header("Refresh: 8");

function getUrl() {
  $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
  $url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
  $url .= $_SERVER["REQUEST_URI"];
  return $url;
}  

echo "Пожалуйста, подождите...";

$url=''.getUrl();
file_put_contents('/var/www/podpolkovnyk/data/502.err',$url);

?>
