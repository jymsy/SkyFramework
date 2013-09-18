<?php
if (isset($_GET['init'])) {
	if (isset($_GET['event']) && isset($_GET['client'])) {
		header('Location: admin/index.php?_path=quickSet&event='.urlencode($_GET['event']).'&client='.urlencode($_GET['client']));
// 		require_once __DIR__.DIRECTORY_SEPARATOR.'cReqDB.php';
// 		$cReqDB = new CReqDB("default",true);
// 		$cReqDB->attachTo("ctest.skyworth-cloud.com");
// 		$eventdata = explode("|", $_GET['event']);
// 		if (count($eventdata)==3) {
// 			$cReqDB->setDefaultEvent($eventdata[0], $eventdata[1], $eventdata[2]);
// 			echo sprintf("Set Default Event->min_sleep: %d, max_sleep: %d, exception_probabilty: %d<br>",$eventdata[0], $eventdata[1], $eventdata[2]);
// 		}

// 		$clients = explode("|", $_GET['client']);
// 		$cReqDB->attachTo($clients);
// 		echo sprintf("Attach Event To Client->%s<br>",$_GET['client']);
// 		$cReqDB->apply();
// 		if (error_get_last()) {
// 			echo "\n<pre>Error:";
// 			print_r(error_get_last());
// 			echo "</pre>\n";
// 		}else echo "Init success!<br>";
	}else{
		header(sprintf("Location: index.php?init&event=%s&client=%s"
				,isset($_GET['event'])?urlencode($_GET['event']):'0|0|0'
				,isset($_GET['client'])?urlencode($_GET['client']):'ctest.skyworth-cloud.com'
		));
	}

}else {
	header("Location: admin");
}
