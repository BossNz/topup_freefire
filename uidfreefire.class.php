<?php
/*
Author | BossNz
Github | https://github.com/bossNzXD
Contact| FB:Teerawat Luesat!
FREE SRC !!!
*/
class topup_freefire{
	function __construct($token_Antirecaptcha = Null){
		$this->token_Antirecaptcha = $token_Antirecaptcha;
	}
	public function login_freefire($uid = Null){
		$recaptcha = $this->get_tokenrecaptcha();
		if ($recaptcha['status'] == "error"){
			$response['status'] = "error";
			$response['message'] = "recaptcha not work";
		}else{
			$result = json_decode($this->callAPI("POST","https://www.termgame.com/api/auth/player_id_login",
				json_encode([
					'app_id'=>100067,
					'captcha_token'=>$recaptcha['Token_Recaptcha'],
					'login_id'=>$uid
				])
			),true);
			if (isset($result['error']) || empty($result['open_id']) || $result['region'] !== "TH") {
				$response['status'] = "error";
				$response['message'] = "uid not correct";
			}else{
				$response['status'] = "success";
				$response['open_id'] = $result['open_id'];
				$response['nickname'] = $result['nickname'];
			}
		}
		return $response;
	}
	public function topup_garenacard($open_id = null,$garena_card = null){
		$tokencaptcha = $this->get_tokenrcaptchaimage();
		if ($tokencaptcha['status'] !== "success") {
			$response['status'] = "error";
			$response['message'] = $tokencaptcha['message'];
		}else{
			$result = json_decode($this->callAPI("POST","https://www.termgame.com/api/shop/pay/init?language=th&region=IN.TH",
				json_encode([
					'app_id'=>100067,
					'captcha'=>$tokencaptcha['text'],
					'captcha_key'=>$tokencaptcha['tokencaptcha'],
					'channel_data'=>[
						'captcha'=>$tokencaptcha['text'],
						'captchaKey'=>$tokencaptcha['tokencaptcha'],
						'card_password'=>$garena_card,
						'friend_username'=>null,
					],
					'channel_id'=>207000,
					'open_id'=>$open_id,
					'packed_role_id'=>0,
					'service'=>'pc'
				])
			),true);
			if ($result['result'] == "success") {
				$response['status'] = "success";
				$response['display_id'] = $result['display_id'];
			}else{
				$response['status'] = "error";
				$response['message'] = $result['result'];
				$response['display_id'] = $result['display_id'];
			}
		}
		return $response;
	}
	//=======================================================================
	private function get_tokenrecaptcha(){
		$createtaskid = json_decode($this->callAPI("POST","https://api.anti-captcha.com/createTask",
			json_encode(
				[
					'clientKey'=>$this->token_Antirecaptcha,
					'task'=>[
						'websiteURL'=>'https://termgame.com',
						'websiteKey'=>'6LfJXvoUAAAAAMrJFTEbBeWygZCCWQWbAZk_z2H0',
						'websiteSToken'=>null,
						'recaptchaDataSValue'=>null,
						'type'=>'NoCaptchaTaskProxyless'
					],
					'softId'=>802
				]
			)
		)
		,true);
		if (empty($createtaskid['taskId'])) {
			$response['status'] = "error";
			$response['message'] = "CAN'T CREATE TASKID";
		}else{
			$taskId = $createtaskid['taskId'];
			do {
				$result = json_decode($this->callAPI(
					"POST",
					"https://api.anti-captcha.com/getTaskResult",
					json_encode([
						'clientKey'=>$this->token_Antirecaptcha,
						'taskId'=>$taskId
					])
				),true);
				sleep(2);
			} while ($result['status'] == "processing");
			$token_recaptcha = $result['solution']['gRecaptchaResponse'];
			$response['status'] = "success";
			$response['Token_Recaptcha'] = $token_recaptcha;
		}
		return $response;
	}
	private function get_tokenrcaptchaimage(){
		$gentoken = $this->generateRandomString(10).'-'.$this->generateRandomString(10).'-'.$this->generateRandomString(5).'-'.$this->generateRandomString(6).'-'.$this->generateRandomString(10);
		$resultbase64 = base64_encode(file_get_contents('https://gop.captcha.garena.com/image?key='.$gentoken));
		$createtaskid = json_decode($this->callAPI("POST","https://api.anti-captcha.com/createTask",
			json_encode(
				[
					'clientKey'=>$this->token_Antirecaptcha,
					'task'=>[
						'type'=>'ImageToTextTask',
						'body'=>$resultbase64,
						'phrase'=>false,
						'case'=>false,
						'numeric'=>false,
						'math'=>0,
						'minLength'=>0,
						'maxLength'=>0
					]
				]
			)
		)
		,true);
		if (empty($createtaskid['taskId'])) {
			$response['status'] = "error";
			$response['message'] = "CAN'T CREATE TASKID";
		}else{
			$taskId = $createtaskid['taskId'];
			do {
				$result = json_decode($this->callAPI(
					"POST",
					"https://api.anti-captcha.com/getTaskResult",
					json_encode([
						'clientKey'=>$this->token_Antirecaptcha,
						'taskId'=>$taskId
					])
				),true);
				sleep(2);
			} while ($result['status'] == "processing");
			if (empty($result['solution']['text'])) {
				$response['status'] = "error";
				$response['message'] = "try again";
			}else{
				$response['status'] = "success";
				$response['tokencaptcha'] = $gentoken;
				$response['text'] = $result['solution']['text'];
			}
		}
		return $response;
	}
	private function callAPI($method, $url, $data){
		$curl = curl_init();
		switch ($method){
			case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
			case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			break;
			default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$result = curl_exec($curl);
		if(!$result){die("Connection Failure");}
		curl_close($curl);
		return $result;
	}
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}