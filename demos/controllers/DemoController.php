<?php
namespace demos\controllers;

use Sky\Sky;
use demos\components\PolicyController;
use Sky\base\Controller;
use Sky\base\HttpException;
use demos\components\SkySession;
class DemoController extends PolicyController{
	public function actions(){
		return array(
				'Wsdl'=>array(
						'class'=>'Sky\base\WebServiceAction'
				),
		);
	}

	/**
	 * hello world 测试
	 * @return  string hello epg
	 */
	public function actionHello(){
// 		$ret=$this->getPolicyValue('policy');
// 		var_dump($ret);
		$session=Sky::$app->session;
// 		echo $session->getId();
// 		echo 'mmm'.$session[SkySession::MAC];
// 		echo $session[SkySession::IP];
		echo "fuck";
		var_dump($session->getTVInfo());
// 		$session[SkySession::IP]='45.45.45.45';
// 		$session[SkySession::MAC]='bc83a7c017a1';
// 		$session[SkySession::USERID]=12346579;
// 		$session->destroy();
		return 'demos action hello';
	}
	
	public function actionList(){
// 		echo json_encode('get list');
		return 'get list';
	}
	
	public function actionSession(){
		$session=Sky::$app->session;
// 		$session->open();
		echo $session->getId();
		$session['name']='fuckjym';
		$session['ee']='fuckjym';
		$session['rr']='fuckjym';
		$session['qq']='fuckjym';
		$session['22']='fuckjym';
		echo $session['name'];
	}
	
	public function actionFtp(){
		$ftp=Sky::$app->ftp;
// 		$ftp->putAll('/home/jym/php/Development/CloudService/Trunk/Framework/requirements','/data/htdocs/Test/requirements');
		$ftp->rdelete('/data/htdocs/Test/requirements');	
// 		$ftp->chdir('/data/htdocs/Test/requirements');
// 		$ftp->chdir('messages');
// 		echo $ftp->currentDir();
	}
	
	public function actionUpload(){
		$this->render('upload.php');
	}
	
	public function actionTest(){
		try {
			throw new HttpException(404,"fuck");
		} catch (\Exception $e) {
			echo 'catch';
		}
		
	}
	
	public function actionGearman(){
		$client=Sky::$app->gearman->client();
		$job_handle =$client->doBackground("reverse2", "this is a test");
		echo $job_handle;
	}
	
	public function actionGetSession(){
		$session=Sky::$app->session;
// 		echo $GLOBALS['session'];
		$session->setId('2qv7tmm2c59me9nlc6g3o3p2m6');
// 		$session->open();
// 		echo $session->getId();
// // 		$session['ip']='2121.121.1.121.1';
// 		if(empty($session['name']))
// 			echo 'no session!';
// 		else 
// 			echo $session['name'];

// 		$mem = new \Memcached;
// 		$mem->addServer('10.200.207.122',11211)or die ('Could not add server 12000');
// 		echo $mem->get('5eql9gjcie0ofibjcdcma389v2');    //echo session_id();
	}
}
