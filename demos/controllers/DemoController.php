<?php
namespace demos\controllers;

use Sky\Sky;
use demos\components\PolicyController;
use Sky\base\Controller;
use Sky\base\HttpException;
use demos\components\SkySession;
use demos\models\PolicyModel;
use demos\components\RedisSession;
use demos\components\BiLogRoute;
use demos\components\TvInfo;
use Sky\utils\PushMsg;
use Sky\utils\PushServer;
use Sky\utils\Socket;
use Sky\web\Response;
use Sky\utils\mobile\Message;
class DemoController extends PolicyController{
	public function actions(){
		return array(
				'Wsdl'=>array(
						'class'=>'Sky\base\WebServiceAction'
				),
		);
	}
	
	public function filters()
	{
		return array(
				array(
						'demos\components\VerifyFilter',
// 						'unit'=>'second',
				),
		);
	}

	/**
	 * hello world 测试
	 * @return  string hello epg
	 */
	public function actionHello(){
//		$ret=$this->getPolicyValue('0001','tianciSnsGetTvPlayType');
//		var_dump($ret);
// 		$session=Sky::$app->session;
// 		echo $session->getId();
// 		echo 'mmm'.$session[SkySession::MAC];
// 		echo $session[SkySession::IP];
// 		echo "fuck";
// 		var_dump($session->getTVInfo());
// 		$session[SkySession::IP]='45.45.45.45';
// 		$session[SkySession::MAC]='bc83a7c017a1';
// 		$session[SkySession::USERID]=12346579;
// 		$session->destroy();
		return 'demos action hello';
	}
	
	public function actionList($par){
// 		Sky::$app->getResponse()->format=Response::FORMAT_XML;
			$obj=new \stdClass();
			$obj->name='jym';
			$obj->value='dddd';

        $arr = array('title'=>1,'content'=>'test');
//        echo Sky::app()->request->getUserHostAddress();
        return $obj;
//        return $arr;
 		return $par."jdjd";
	}
	
	public function actionMobile()
	{
		$mobile=18642629475;
		$content='test';
		$user_name  = 'H10117';
		/*
		 *  网关密码
		*/
		$password   = '120037';
		
		/*
		 *  子端口号码,详情见开发接口文档
		*/
		$pszSubPort = '*';
		
		/*
		 *  发送服务提交地址,详情见开发接口文档
		*/
		$server_url = 'http://61.145.229.29:7791/MWGate/wmgw.asmx?wsdl';
// 		$server_url = 'http://ws.montnets.com:9002/MWGate/wmgw.asmx?wsdl';
		
		$sms = new Message($server_url,$user_name,$password);
		$sms->pszSubPort = $pszSubPort;
		$sms->setOutgoingEncoding("UTF-8");
		$mobiles = (array)$mobile;
		
		
		$result = $sms->sendSMS($mobiles,$content);
		var_dump($result);
	}
	
	public function actionReg()
	{
		$barcode='40E690U-R000016-Z131221-65F4';
		$regex = "/([0-9]{2}[a-zA-Z0-9]{4,5})-([a-zA-Z0-9]{7})-([a-zA-Z0-9]{7})-([a-zA-Z0-9]*)/";
		if (! preg_match($regex, $barcode, $matches)) {
			return 0;
		}
		return 1;
	}
	
	public function actionSession(){
// 		$redis=Sky::$app->redis;
// 		$redis->tranStart();
// 		$redis->hashSet('fsdkjf88888lskdf',array('name'=>'jydm'));
// 		$redis->setKeyExpire('fsdkjf88888lskdf', 3600);
// 		$redis->tranCommit();
// 		$session=Sky::$app->session;
// 		$session->setId('6fe6d36a-4ac4-11e3-84eb-00163e0e2e32');
// 		$session->setId('sdfsdf:bc83a7df4a37');
// 		$session->open();
// 		$session[TvInfo::USERID]=111114692;
// 		$session[TvInfo::IP]='1.1.1.1';
// 		$session[TvInfo::MAC]='bc83a7df4a37';
		var_dump(Sky::$app->tvinfo->getTVInfo());
// 		echo $session->getId();
// 		$session['name']='fuckjym';
// 		$session['ee']='fuckjym';
// 		$session['rr']='fuckjym';
// 		$session['qq']='fuckjym';
// 		$session['22']='fuckjym';
// 		echo $session['name'];
// 		$session->destroy();
	}
	
	private function packToken()
	{
		$tokenArr=array(
				'cmdLen'=>array('n',strlen('2000')),
				'cmd'=>array('a*','2000'),
				'passwordLen'=>array('n',strlen('TC_2013_Push')),
				'password'=>array('a*','TC_2013_Push'),
				'seqnoLen'=>array('n',strlen('1')),
				'seqno'=>array('a*','1')
		);
		$token=Socket::packByArr($tokenArr);
		return base64_encode("\x0a".pack('n',strlen($token)).$token."\x0b");
	}
	
	
	public function actionDelSession()
	{
		return PolicyModel::delSession();
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
	
	public function actionPush()
	{
		$push=Sky::$app->push;
// 		$appid='10004';
// 		$idc='TC_linux_Push';
		$appid='10001';
		$idc='TC_2013_Push';
		//******************single user
// 		$push->initMsg(PushMsg::SINGLE,10001,0,'TC_2013_Push');
// 		$push->addUser(8888888);
// 		$push->addUser(888);
// 		$push->addMsg(time(), rand(0,100000),'dd');
// 		return $push->push();

		//******************single user mac]
		Sky::beginProfile('push');
		$push->initMsg(PushMsg::SINGLEMAC,10001,0,'TC_2013_Push');
		$msg='dd';
		$push->addMac('00301BBA02DB',8888888);
		$push->addMac('00301BBA02DB',8888888);
		$push->addMsg(time(), rand(0,100000),$msg);
		Sky::endProfile('push');
		return $push->push();

		//*******************get group
// 		$push->initMsg(PushMsg::GROUP,$appid,0,$idc);
// 		return $push->getGroup();

		//******************group intersection交集
// 		$push->initMsg(PushMsg::GROUP,10001,0,'TC_2013_Push');
// 		$push->addGroup('model_8S09');
// 		$push->addGroup('sysVer_2013008210');
// 		$push->addMsg(time(), rand(0,100000),'test intersection');
// 		return $push->push();

		//******************group union并集
// 		$push->initMsg(PushMsg::GROUPUNION,10001,0,'TC_2013_Push');
// 		$push->addGroup('model_8S09');
// 		$push->addGroup('sysVer_2013008210');
// 		$push->addMsg(time(), rand(0,100000),'test union');
// 		return $push->push();
	}
	
	public function actionTest(){
		try {
			throw new HttpException(404,"fuck");
		} catch (\Exception $e) {
			echo 'catch';
		}
		
	}
	
	public function actionXml()
	{
		$arr = array(
				'home'=>'sdfsdf',
				'shop'=>'sdfsdf',
				'error'=>array('dest'=>'sdfsdf'),
		);
		
		return $arr;
	}
	
	public function actionSendMsg()
	{
		$rabbit=Sky::$app->rabbitmq;
		$rabbit->createConnection();
// 		$rabbit->declareExchange('e_linvo', AMQP_DURABLE, AMQP_EX_TYPE_DIRECT);
		$rabbit->exchange->init('e_linvo', AMQP_EX_TYPE_DIRECT, AMQP_DURABLE);
		$rabbit->exchange->send('this is tetst','key_1');
	}
	
	public function actionSendMail()
	{
		$mail=Sky::$app->mail;
		$mail->IsSMTP();
		$mail->AddAddress('jiangyumeng@skyworth.com');
		$mail->SetFrom('skysrt@163.com');
		$mail->AddReplyTo('skysrt@163.com');
		$mail->Subject = 'test';
		$mail->Body = 'jym fuck';
		$mail->Send();
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
	
	public function remRedisRange()
	{
		$redis = Sky::$app->redis;
		if ($redis) {
			$remArr = $redis->setRangeByScore('www.youku.com','-inf', '+inf',true);
			
		}
	}
	
	public function actionStart()
	{
		$curl = Sky::$app->curl;
		$curl->setOption(CURLOPT_TIMEOUT, 600);
		$result = $curl->get('http://srt.skyworth.com/design/compile.php');
		var_dump($result);
	}
}
