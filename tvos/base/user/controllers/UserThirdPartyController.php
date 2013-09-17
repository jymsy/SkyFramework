<?php
/*
 *  用户第三方登录绑定账号使用
 */

namespace base\user\controllers; 
use Sky\base\Controller;
use base\user\models\UserModel;
use base\user\models\BaseDevice;
use base\user\models\ThirdPartyAccoutMap;
use Sky\db\DBCommand;
 

class UserThirdPartyController extends Controller {
      /*
       *  生成描述用的
       */	
	public function actions(){
		return array(
                        "Wsdl"=>array(
                                        "class"=>"Sky\\base\\WebServiceAction"
                                        ),
                        );
	}
         
        /**
	 * 传uid,type,检查登录
	 * @param String $uid  第三方唯一的用户id
	 * @param String $type 第三方类型，如1是qq,2是sina
         * http://g/trunk/Framework/tvos/index.php/base/user/UserThirdParty/UidLogin/uid/20121221/type/2/
	 * @param String    $extParam为登录后产生的json字符串
	 */
        public function actionUidLogin( $uid,$type,$extParam='' ){
             $type = intval($type);
             
            /*
             * 是否绑定
             */
               $userid = ThirdPartyAccoutMap::queryThirdParty($uid,$type);
              
              if(!$userid){
                 
                  /*
                   * 注册成功后返回 userid
                   */
                  $userid = UserModel::userRegister(1100, '',$uid, 0); 
                  /*
                   * 添加绑定用户
                   * $third_party_accout,$third_party_type,$user_id
                   */
                  $user = ThirdPartyAccoutMap::addThirdParty($uid,$type,$userid);
                   
              }
          
            
              if(isset($userid) && $userid!=0 ){
                 /*
                  * 执行登录操作
                  */ 
                  return $this->forward('base/user/userAction/UserLogin',$userid,''); 
              }else{
                  /*
                   *  登录不成功返回为空
                   */
                  return '';
              }

        }
        
}