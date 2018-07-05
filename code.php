<?php

include('Curl.php');

$links = json_decode(file_get_contents("data/links.txt"), true); // mang json danh sach link
$total = count($links); // dem tong so link trong mang
$count = file_get_contents("data/count.txt"); // lay so dem
$config = file_get_contents("data/config.txt"); // lay thong so config
$configs = explode('|', $config); //tach config


if ($count <= $total) {

	$time_current = ($total-$count)*0.3; // 1 giay
	$percent = round(($count/$total) * 100);

	/*curl*/
	$curl = new Curl();

	$link = $links[$count];
	$html_single = $curl->getSingle($link);

		preg_match('#<a class="chapter-title"(.*?)<span>(.*?)</a>#is', $html_single, $chapter); // chapter[2]


	// them 1 cho count
	$file = fopen("data/count.txt", "w");
	fwrite($file, ($count+1));
	fclose($file);

	if (isset($error)) {
		echo "<p>$error</p>";
	}
	echo "$count là $percent% của $total - Thời gian còn: " . formatMinuteSeconds($time_current);
	echo "<br><br>";
	echo $chapter[2];

	?>
	<div class="outter">
		<div class="inner"><?php echo $percent ?>%</div>
	</div>
	<style>
		.outter{
			line-height: 1.5;
			max-width: 500px;
			background-color: #f1f1f1;
		}
		.inner{
			line-height: 1.5;
			width: <?php echo $percent ?>%;
			background-color: lightblue;
		}
	</style>

<?php } else {
	die('hello');

}

function formatMinuteSeconds($value)
{
	$minutes = floor($value/60);
	$seconds = $value % 60;
	return sprintf('%d:%02d', $minutes, $seconds);
}
