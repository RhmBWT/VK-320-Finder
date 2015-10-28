<?php
$start = microtime(true);
$access_token = 'eca1eab94f4bd20661f43fe23bf268c663fd27967fe90f57f43525ba1e9f6f28a4c0b63a01e175814c71b';
$count = 25;
$file = 'download.txt';
$listFile = file_get_contents('list.txt');
$listSongs = explode(';', $listFile);
foreach ($listSongs as $song) {
	$query = mb_strtolower($song);
	$url = 'https://api.vk.com/method/audio.search?q='.$query.'&count='.$count.'&access_token='.$access_token.'';
	$json = file_get_contents(str_replace(' ', '%20', $url));
	$out = json_decode($json, true);
	$tries = count($out['response']);
	for ($i=1; $i < $tries; $i++) { 
		$fileurl = $out['response'][$i]['url'];
		if ($fileurl != '') {
			$headers = get_headers($fileurl, 1);			
		} else {
			print $song.' - Failed! :(<br/>';
			break;
		}
		if (is_array($headers['Content-Length'])) {
			print $song.' - Sorry<br/>';
			break;
		}
		$titlee = mb_strtolower($out['response'][$i]['artist'].' - '.$out['response'][$i]['title']);
		$bitrate = ($headers['Content-Length'] * 8 / 1000) / $out['response'][$i]['duration'];
		if (($bitrate > 180) and ($query == $titlee)) {
			$name = $out['response'][$i]['artist'].' - '.$out['response'][$i]['title'].'.mp3';
			$stringToFile = $fileurl.'&/'.str_replace(' ', '%20', $name)."\n";
			print $name.' - '.$bitrate.' kbit/sec - OK!<br/>';
			file_put_contents($file, $stringToFile, FILE_APPEND);
			break;
		}
		if ($i == $tries-1) {
			if ($tries < $count+1) {
				print $song.' - Failed. No such bitrate<br/>';
			} else {
				print $song.' - Failed. Not enough attempts<br/>';
			}
			break;
		}
	}
}
$time = microtime(true) - $start;
print('Finished in '.$time.' secs.');
?>