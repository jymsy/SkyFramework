<?php
namespace demos\autorun;

use Sky\console\ConsoleCommand;
use Sky\Sky;
class TestCommand extends ConsoleCommand{
	public function actionRun()
	{
		$redis=Sky::$app->redis;
		$index=0;

		if ($redis) {
			$redis->tranStart();
			$remArr = $redis->setRangeByScore('www.youku.com','-inf', '+inf',true);
			$redis->delete('www.youku.com');
			var_dump($remArr);
			foreach ($remArr as $key=>$score)
			{
				$redis->delete($key);
			}
			$redis->tranCommit();
		}
	}
}