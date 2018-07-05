<?php

if (isset($_POST['config'])) {

	extract($_POST);

	if ($link == '') {
		$error[] = "Nhập link";
	}
	// kiểm tra xem có phải số và lớn hơn 0
	if (!ctype_digit($story_parent) || (int) $story_parent == 0) {
		$error[] = "Nhập số";
	}

	if(!isset($error)){
		// insert thiet lap vao config.txt
		$fopen = fopen("data/config.txt", "w");
		fwrite($fopen, $link.'|'.$story_parent);
		fclose($fopen);
		header('Location: ?post');
	}

} elseif (isset($_GET['post'])) {

	// doc config.txt
	$data_config = file_get_contents('data/config.txt');
	$data_configs = explode('|', $data_config);
	// $data_configs['link'] va $data_configs['story_parent']
	$data_configs = array('link' => $data_configs[0], 'story_parent' => $data_configs[1]);

	//print_r($data_configs);

	include('Curl.php');
	$curl = new Curl();
	$html_single = $curl->getSingle($data_configs['link']);

	// tong
	if(!preg_match('#<ul class="pagination pagination-sm">#is', $html_single)){
		$last = '1';
	}else{
		if(preg_match('#Trang ([0-9]{1,3})">Cuối#is', $html_single)){
			preg_match('#Trang ([0-9]{1,3})">Cuối#is', $html_single, $tong);
			$last = $tong[1];
		}else{
			preg_match('#(.*)>([0-9]{1,3})</a></li><li>#is', $html_single, $tong);
			$last = $tong[2];
		}
	}

	#1 GỘP VÀ LẤY TẤT CẢ URL CÁC TRANG
	$urls = array();
	for ($i = 1; $i <= $last; $i++) { 
		$urls[] = $data_configs['link'] . 'trang-' . $i . '/';
	}

	$html_multi = $curl->getMulti($urls);

	preg_match_all('#<ul class="list-chapter">(.*?)</ul>#is', $html_multi, $list_chapter);

	preg_match_all('#href="(.*?)"#is', print_r($list_chapter[1], true), $links);

	//print_r($links[1]); danh sach link

	file_put_contents("data/links.txt",  json_encode($links[1]));

	echo "<p>0kie " . count($links[1]) . "</p>";
	echo "<p><a href='run.php'>Run now</a></p>";
	echo "<p><a href='reset.php'>Reset 0</a></p>";


	exit();

} /*end if post*/



if(isset($error)){
	foreach ($error as $error) {
		echo "<p>$error</p>";
	}
}

?>
<form action="" method="POST">
	link:<br>
	<input type="text" name="link" value=""><br>
	story_parent:<br>
	<input type="text" name="story_parent" value=""><br><br>
	<input type="submit" name="config" value="Config">
</form>