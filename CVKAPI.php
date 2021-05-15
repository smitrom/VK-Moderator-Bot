<?php 

	class CVKAPI {
		private $token;
		private $version;

		public function __construct($token, $version = NULL) {
			$this->token = $token;
			$this->version = $version ? $version : '5.120';
		}

		public function UID() {
			$s = explode(' ', microtime());
			$a = $s[0] * 1000000;
			$c = $s[1].$a;
			return $c;
		}

		public function Call($method, $data = NULL) {
			$default = ['v' => $this->version, 'access_token' => $this->token];
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_SSL_VERIFYPEER => FALSE,
				CURLOPT_SSL_VERIFYHOST => FALSE,
				CURLOPT_POSTFIELDS => isset($data) ? array_merge($default, $data) : $default,
				CURLOPT_URL => sprintf('https://api.vk.com/method/%s', $method),
			));
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}

		public function SendMessage($userID, $text) {
			$randomID = $this->UID();
			$data = [
				'peer_id' => $userID,
				'random_id' => $randomID,
				'message' => $text,
			];
			$call = $this->Call('messages.send', $data);
		}
	}

?>