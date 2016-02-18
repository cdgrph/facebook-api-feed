<?php
/**
 * Facebook Graph API 2.5 Feed Sample
 */

//Create Access URL
define('APP_ID', ""); //App ID
define('APP_TOKEN', ""); //App Token

$url = "https://graph.facebook.com/v2.5/".APP_ID."/feed?access_token=".APP_TOKEN; //Graph API 2.5

//Get json data
$ch = curl_init();
curl_setopt_array($ch,
	array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER =>false,
		CURLOPT_RETURNTRANSFER =>true,
		)
	);
$res = curl_exec($ch);
curl_close($ch);

$array = json_decode($res, TRUE);

$message = array();
$date = array();

//URL strings to HTML Links
function replaceToLink($text){
	$pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
	$replacement = '<a href="\1">\1</a>';
	$text= preg_replace($pattern,$replacement,$text);
	return $text;
}

for($i=0;$i<3;$i++){
	$cnt = $i+1;
	$urls = $array['data'][$i]['id'];
	$url_array = explode("_", $urls);
	$date[$i] = '<a href="https://www.facebook.com/'.$url_array[0].'/posts/'.$url_array[1].'" target="_blank">' . substr($array['data'][$i]['created_time'], 0, strcspn($array['data'][$i]['created_time'],'T')) . '</a>';
	$message[$i] = replaceToLink(mb_strimwidth($array['data'][$i]["message"], 0, 110, "...", utf8));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>Facebook Graph API 2.5 Feed Sample</title>
</head>
<body>
	<h1>Facebook Graph API 2.5 Feed Sample</h1>
	<ul>
		<?php for($i=0;$i<3;$i++): ?>
		<li>
			<?php echo $date[$i]; ?><br>
			<?php echo $message[$i]; ?>
		</li>
		<?php endfor; ?>
	</ul>
	<h2>Object data</h2>
	<pre>
		<?php print_r($array);?>
	</pre>
</body>
</html>
