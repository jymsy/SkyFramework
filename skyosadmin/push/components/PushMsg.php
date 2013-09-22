<?php
namespace push\components;

use Sky\base\Component;
use Sky\utils\Socket;
use Sky\Sky;
/**
 * 发送推送消息
 * 使用方法：
 * 'curl' => array(
 *           'class' => 'Sky\utils\Curl',
 *       );
 *   'push' => array(
 *           'class' => 'push\components\PushMsg',
 *           'pushServer'=>'121.199.33.199',
 *           'pushPort'=>50034
 *       );
 *       
 *  $push=Sky::$app->push;
 *  $push->initMsg(PushMsg::BROAD,10001);
 *  $push->addUser(1234);
 *  $push->addMsg(time(), rand(0,100000),$str);
 *  $push->push();
 *  
 * @author Jiangyumeng
 *
 */
class PushMsg extends Component{
	const SINGLE='1';
	const GROUP='2';
	const BROAD='3';
	
	public $pushServer='';
	public $pushPort;
	/**
	 * @var int 1.单点消息, 2.组播消息  3.广播消息
	 */
	public $msgType;
	/**
	 * @var int 命令码 2000 表示 PushMsg
	 */
	public $cmd=2000;
	/**
	 * @var string 版本号默认1.0
	 */
	public $ver='1.0';
	/**
	 * @var int 应用id默认0
	 */
	public $appId=0;
	/**
	 * @var string 消息保存时间
	 */
	public $saveTime='0';
	public $seqno;
	/**
	 * @var string 密码
	 */
	public $passWord;
	protected $user=array();
	protected $userNum=0;
	protected $group=array();
	protected $groupNum=0;
	protected $msg=array();
	protected $msgNum=0;
	
	/**
	 * @param unknown $msgType
	 * @param number $appId
	 * @param number $saveTime
	 * @param string $passWord
	 */
	public function initMsg($msgType=self::SINGLE,$appId=0,$saveTime=0,$passWord='')
	{
		$this->msgType=$msgType;
		$this->appId=$appId;
		$this->saveTime=$saveTime;
		$this->seqno=time();
		$this->passWord=$passWord;
	}
	
	/**
	 * 为消息添加一个用户
	 * @param int $userId
	 */
	public function addUser($userId)
	{
		$this->user[]=$userId;
		$this->userNum++;
	}
	
	/**
	 * 为消息添加一个组
	 * @param string $groupIdName
	 */
	public function addGroup($groupIdName)
	{
		$this->group[]=$groupIdName;
		$this->groupNum++;
	}
	
	/**
	 * 添加一个消息
	 * @param int $msgTime
	 * @param int $msgId
	 * @param string $msg
	 */
	public function addMsg($msgTime,$msgId,$msg)
	{
		$this->msg[]=array($msgTime,
				$msgId,
				strlen($msg),
				$msg);
	
		$this->msgNum++;
	}
	
	/**
	 * 发送消息
	 */
	public function push()
	{
		$msgBody='';
	
		foreach ($this->msg as $msg)
		{
			$msgBody.=$this->packMsg($msg,$this->msgType);
		}
	
		$msgBodyLen=11+strlen($msgBody);
	
		$msgBody=$this->packMsgBody($msgBody, $msgBodyLen);
	
		$hexBody=base64_encode($msgBody);
	
		// 		$hexBody=$msgBody;
		$curl=Sky::$app->curl;
		$paramArr=array(
				'cmd'=>$this->cmd,
				'seqno'=>$this->seqno,
				'token'=>md5($this->cmd.$this->passWord.$this->seqno),
				'savetime'=>$this->saveTime,
				'ver'=>$this->ver,
		);
		// 		'msg'=>$hexBody,
		return $curl->post($server.':'.$port,http_build_query($paramArr).'&msg='.$hexBody);
	}
	
	/**
	 * 组包消息体。
	 * @param string $msgBody 消息体
	 * @param integer $msgBodyLen 消息体长度
	 * @return string
	 */
	private function packMsgBody($msgBody,$msgBodyLen)
	{
		return "\x0a".pack('n',$msgBodyLen).pack('N',$this->appId).pack('C',$this->msgType).pack('n',$this->msgNum).$msgBody."\x0b";
	}
	
	/**
	 * 组包消息
	 * @param string $msg 消息
	 * @param unknown $msgType 消息类型
	 * @return string
	 */
	protected function packMsg($msg,$msgType)
	{
		$msgArrHead=array(
				'msgTime'=>array('N',$msg[0]),
				'msgId'=>array('N',$msg[1]),
				'msgLen'=>array('n',$msg[2]),
				'msg'=>array('a*',$msg[3]),
		);
			
			
		$msgStr=Socket::packByArr($msgArrHead);
	
		if($msgType==self::SINGLE)
		{
			$msgStr.=pack('n',$this->userNum);
			foreach ($this->user as $user)
			{
				$msgStr.=pack('N',$user);
			}
	
		}elseif ($msgType==self::GROUP)
		{
			$msgStr.=pack('n',$this->groupNum);
			foreach ($this->group as $groupName)
			{
				$msgArrMid=array(
						'groupLen'=>array('n',strlen($groupName)),
						'groupName'=>array('a*',$groupName),
				);
				$msgStr.=Socket::packByArr($msgArrMid);
			}
		}
			
		return $msgStr;
	}
}