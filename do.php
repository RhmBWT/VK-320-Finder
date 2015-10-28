<?php
$access_token = 'babaf06c0dd876a2741e0544beb8833815aeb457da02f03e354164ee93d3e54f8d536d097526454dbe634';

//Файл со списком песен для загрузчика
$file = 'download.txt';

//Данные из формы
$artist = $_POST['artist'];
$title = $_POST['title'];
$count = $_POST['count'];

//Запрос
$query = $artist.' '.$title;
$url = 'https://api.vk.com/method/audio.search?q='.$query.'&count='.$count.'&access_token='.$access_token.'';
$json = file_get_contents(str_replace(' ', '%20', $url));
$out = json_decode($json, true);

//Поиск песни с битрейтом больше 300 и запись ссылки на нее в текстовый файл
for ($i=1; $i < $count+1; $i++) { 
	$fileurl = $out['response'][$i]['url'];
	$headers = get_headers($fileurl, 1);
	$bitrate = ($headers['Content-Length'] * 8 / 1000) / $out['response'][$i]['duration'];
	if (($bitrate > 100) and ($artist == $out['response'][$i]['artist']) and ($title == $out['response'][$i]['title'])) {
		$name = $out['response'][$i]['artist'].' - '.$out['response'][$i]['title'].'.mp3';
		$stringToFile = $fileurl.'&/'.str_replace(' ', '%20', $name)."\n";
		print $name.'<br/>';
		print $bitrate.' kbit/sec<br/>';
		print $stringToFile.'<br/><br/>';
		file_put_contents($file, $stringToFile, FILE_APPEND);
		break;
	}
}
?>