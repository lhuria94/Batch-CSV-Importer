<?php
/* Authored By: Love Huria
 * Date: Jan 09,2015
 * Project: Anonymous
 */
//load all wordpress libraries
//$root = $_SERVER['DOCUMENT_ROOT'] . '\wp-load.php';
//require_once($root);
//initialise batch
header('Content-Type: text/html; charset=UTF-8');
$last = 0;
$current = 0;
//currently 10 batches at a time
$batch_size = 10;
//define file path

$fh = 'example_data.csv';
if (file_exists("fb-comments.log")) {
	$last = sprintf("%d", file_get_contents("fb-comments.log"));
}
/*Create folder if does not exists
 * No use in current scenario
 * */

/*end*/

//check code
if (!file_exists($fh)) {
	print "ERROR: Missing input.csv\n";
	exit(1);
}
$values = array();
$fh = fopen($fh, "r");
$header = NULL;
while(!feof($fh) && $current < $last+$batch_size) {
	$current++;
	$line = fgets($fh, 8192);
	if ( $current > $last ) {
		$array[] = trim($line);
	}
}
fclose($fh);

foreach($array as $key=>$line){
	$ex = explode(',',$line);
	//fetch records acc to requirement
	echo $ex['0'].'==================='.$ex['1'].'<br/>';
}
$c = count($array);
//print "Current Batch Values: " . implode(", ", $array) . "\n";
if (count($array) > 0) {
	$message = "Finished processing {$current} items. " .
		"Processing next batch momentarily...";
	//put last added record in log file
	file_put_contents("fb-comments.log", $current);
	$refresh = true;
} else {
$message = "Finished processing all items.";
	//delete file once batch completed
	unlink('fb-comments.log');
	$refresh = false;
}
header('HTTP/1.1 200 OK', true, 200);
?> 
<!-- Create Batching Process Refresh --> 
<!doctype html>
<html>
	<head>
		<title>Facebook Comments</title>
		
	</head>
	<body>
		<p><?php echo $message; ?></p>
		<p>Peak memory usage: <?php echo (memory_get_peak_usage()/1024/1024); ?>
		MiB</p>
		<p>Memory limit: <?php echo ini_get('memory_limit'); ?></p>
	</body>
</html>
<!-- End -->