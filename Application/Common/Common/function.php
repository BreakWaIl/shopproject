<?php
// 格式化分类
if(!function_exists('get_cate_tree')){
	function get_cate_tree($data,$id=0,$lev=1){
		static $list =array();//保存最后的结果
		foreach ($data as  $value) {			
			if($value['parent_id']==$id){
				$value['lev']=$lev;//标识当前分类的层次
				$list[]=$value;
				get_cate_tree($data,$value['id'],$lev+1);
			}
		}
		return $list;
	}
}
// 生成密码
// $str string 密码的明文
// $salt string 盐
if(!function_exists('md6')){
	function md6($str,$salt='123456'){
		// 通过使用盐进行双重MD5加密提高密码到的安全度
		return md5(md5($str).$salt);
	}
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = ''){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);

    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
	
// 发送短信
if(!function_exists('send_sms')){
    function send_sms($to,$datas,$tempId)
    {
        include_once("../sms/CCPRestSmsSDK.php");
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8aaf0708594f1f0201595d46aee102b0';
        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= '130a522eb0144e0182a54a6eae13e3b0';
        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId='8a216da86010e6900160344703890fd2';

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='sandboxapp.cloopen.com';
        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';
        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';
        $rest = new \REST($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);

        $result = $rest->sendTemplateSMS($to,$datas,$tempId);
        if($result == NULL ) {
            return false;
        }
        if($result->statusCode!=0) {
            return false;
        }
        return true;
    }
}
// 发送邮件
if(!function_exists('send_email')){
    function send_email($to,$Subject,$body){

        require '../PHPMailer/class.phpmailer.php';
        $mail             = new \PHPMailer();
        /*服务器相关信息*/
        $mail->IsSMTP();  //启用smtp方式发送邮件                      
        $mail->SMTPAuth   = true; //控制是否需要进行校验               
        $mail->Host       = 'smtp.163.com'; //smtp服务地址         
        $mail->Username   = 'phpresources'; //邮箱的用户名        
        $mail->Password   = 'qazwsxedc123'; //邮箱第三方授权密码
        /*内容信息*/
        $mail->IsHTML(true);
        $mail->CharSet    ="UTF-8";         
        $mail->From       = 'phpresources@163.com';  //发件箱      
        $mail->FromName   ="商城管理员";   //发件箱的昵称
        $mail->Subject    = $Subject; //发件箱的主题
        $mail->MsgHTML($body);
        $mail->AddAddress($to);//收件人地址  
        return $mail->Send();
    }
}

// if(!function_exists('get_data')){
//     function get_data($url,$postData=array()){
//        $key = str_replace('=','_',str_replace('&','_',http_build_query($postData)));
//        $data = get_redis($key);
//        if($data){
//         return $data;
//        }
//        $res = http_curl($url,$postData,'get');
//        set_redis($key,$res);
//     }
// }

if(!function_exists('http_curl')){
    function http_curl($url,$postData=array(),$method='get',$returnType='json'){
        $ch = curl_init();
        // 设置参数
        if($method=='get'){
            $url .='?'.http_build_query($postData);
        }else{
            //post
            curl_setopt($ch.CURLOPT_POSTFIELDS,$postData);
        }
        // 设置请求地址
        curl_setopt($ch,CURLOPT_URL, $url);
        // 设置结果不直接输出
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $result = curl_exec($ch);
        curl_close($ch);
        if($returnType == 'json'){
            $data=json_decode($result,true);
            if($data['status']==0){
                return false;
            }
            return $data['data'];
        }
        return $result;
    }
}

?>