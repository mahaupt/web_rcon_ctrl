<?php
	
if ($_SITE_INCLUDED !== true) exit();



class OAuth
{	
	private $oauth_name;
	private $client_id;
	private $secret_key;
	private $base_url;
	
	private $access_token;
	private $refresh_token;
	private $expiration_time; 
	private $last_validation;
	
	private $login;
	private $user_id;
	
	private $validation_refresh_interval = 3600;
	
	
	public function __construct($oauth_name, $base_url, $client_id, $secret_key)
	{
		$this->oauth_name = $oauth_name;
		$this->base_url = $base_url;
		$this->client_id = $client_id;
		$this->secret_key = $secret_key;
		
		$this->loadFromSession();
		
		
		//revalidate data
		if (strlen($this->login) > 1)
		{
			if ($this->last_validation + $this->validation_refresh_interval < time())
			{
				if (!$this->validateToken()) return;
			}
			
			if ($this->expiration_time < time())
			{
				if (!$this->refreshToken()) return;
			}
		}
	}
	
	
	public function getAuthUrl($redirect_uri, $additional_params)
	{
		$state = generateRandomString();
		$_SESSION["oauth_" . $this->oauth_name . '_state'] = $state;
		
		$uri = $this->base_url . "authorize" . 
			"?client_id=" . $this->client_id . 
			"&redirect_uri=" . urlencode($redirect_uri) . 
			"&state=" . $state;
			
		foreach($additional_params as $k=>$p)
		{
			$uri = $uri . "&" . $k . "=" . $p;
		}
		
		return $uri;
	}
	
	
	public function getAccessToken($auth_code, $redirect_uri, $state)
	{
		//state check - fail auth
		if ($state != $_SESSION["oauth_" . $this->oauth_name . '_state'])
		{
			return false;
		}
		
		
		$url = $this->base_url . "token";
		$data = array(
			'client_id' => $this->client_id, 
			'client_secret' => $this->secret_key, 
			'code' => urlencode($auth_code), 
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirect_uri
		);
		
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) 
		{ 
			$this->eraseSession();
			return false; 
		}
		
		$result = json_decode($result, false);
		
		$this->last_validation = time();
		$this->expiration_time = time() + $result->expires_in;
		$this->access_token = $result->access_token;
		$this->refresh_token = $result->refresh_token;
		
		
		if ($this->validateToken())
		{
			$this->saveToSession();
			return true;	
		} else {
			return false;
		}
	}
	
	
	private function validateToken()
	{
		$url = $this->base_url . "validate";
		
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . 
		        	"Authorization: OAuth " . $this->access_token,
		        'method'  => 'GET'
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) 
		{ 
			$this->refreshToken();
			return false; 
		}
		
		$result = json_decode($result, false);
		
		$this->last_validation = time();
		$this->expiration_time = time() + $result->expires_in;
		$this->login = $result->login;
		$this->user_id = $result->user_id;
		return true;
	}
	
	
	private function refreshToken()
	{
		$url = $this->base_url . "token";
		$data = array(
			'client_id' => $this->client_id, 
			'client_secret' => $this->secret_key, 
			'refresh_token' => $this->refresh_token, 
			'grant_type' => 'refresh_token'
		);
		
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) 
		{
			$this->eraseSession();
			return false; 
		}
		
		$result = json_decode($result, false);
		
		$this->last_validation = time();
		$this->expiration_time = time() + $result->expires_in;
		$this->access_token = $result->access_token;
		$this->refresh_token = $result->refresh_token;
		
		if ($this->validateToken())
		{
			$this->saveToSession();
			return true;	
		} else {
			return false;
		}
	}
	
	
	public function logout()
	{
		$url = $this->base_url . "revoke";
		$data = array(
			'client_id' => $this->client_id, 
			'token' => $this->access_token
		);
		
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) 
		{	}
		
		$this->eraseSession();
	}
	
	
	private function loadFromSession()
	{
		$prefix = "oauth_" . $this->oauth_name . '_';
		if (array_key_exists($prefix . 'saved', $_SESSION))
		{
			$this->access_token = $_SESSION[$prefix . 'access_token'];
			$this->refresh_token = $_SESSION[$prefix . 'refresh_token'];
			$this->expiration_time = $_SESSION[$prefix . 'expiration_time'];
			$this->last_validation = $_SESSION[$prefix . 'last_validation'];
			
			$this->login = $_SESSION[$prefix . 'login'];
			$this->user_id = $_SESSION[$prefix . 'user_id'];
		}
	}
	
	private function saveToSession()
	{
		$prefix = "oauth_" . $this->oauth_name . '_';

		$_SESSION[$prefix . 'saved'] = true;
		$_SESSION[$prefix . 'access_token'] = $this->access_token;
		$_SESSION[$prefix . 'refresh_token'] = $this->refresh_token;
		$_SESSION[$prefix . 'expiration_time'] = $this->expiration_time;
		$_SESSION[$prefix . 'last_validation'] = $this->last_validation;
		
		$_SESSION[$prefix . 'login'] = $this->login;
		$_SESSION[$prefix . 'user_id'] = $this->user_id;
	}
	
	private function eraseSession()
	{
		$this->access_token = "";
		$this->refresh_token = "";
		$this->expiration_time = 0;
		$this->last_validation = 0;
		$this->login = "";
		$this->user_id = "";
		
		$this->saveToSession();
	}
	
	public function isAuthenticated()
	{
		if ($this->expiration_time < time())
		{
			return false;	
		}
		
		if ($this->last_validation + $this->validation_refresh_interval < time())
		{
			return false;
		}
		
		if (strlen($this->login) > 1)
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function getUsername()
	{
		return $this->login;
	}
	
	public function getUserId()
	{
		return $this->user_id;
	}
}

?>