<?php
$url = 'https://api.vk.com/method/audio.search?q=Asian%20Dub%20Foundation%20-%20Fortress%20Europe&count=10&access_token=eca1eab94f4bd20661f43fe23bf268c663fd27967fe90f57f43525ba1e9f6f28a4c0b63a01e175814c71b';
$out = json_decode($json, true);
print_r($out);
$tries = count($out['response']);
for ($i=1; $i < $tries; $i++) { 
	$fileurl = $out['response'][$i]['url'];
	$headers = get_headers($fileurl, 1);			
	print $headers['Content-Length'];
}
?>