<?php

namespace App;
use Cache;

class ApiCron
{
	private $api_url =  "http://cron.curacall.com/api";
	private $username = "jeric@curacall.com";
	private $password = "password";

	public function login()
	{
		return Cache::rememberForever("api_token", function (){
            	$result = $this->post('auth/login',[
					 			"email"		=> $this->username,
					 			"password"	=> $this->password,
					 			"remember"	=> true
					 	]);

            	if($result && isset($result->access_token))
            		return $result->access_token;
            	else
            		return "Invalid credentials";
        });
	}

	public function remind($case_id,$interval,$participants)
	{
		$api_token = $this->login();

		$header_arr = array(
			    'Authorization: Bearer ' . $api_token,
			    'Content-Type: application/json',
			    'Accept: application/json',
			);
		//dd($header_arr);
		//return $api_token;
		 $result = $this->post('reminder/create',json_encode([
		 			"case_id"			=> $case_id,
		 			"interval_minutes"	=> $interval,
		 			"participants"		=> $participants
		 ]),$header_arr);

		 return $result;
		 if($result->response == "ok")
		 {
		 	return json_encode($result);
		 }else{
		 	if($result->message == "Unauthenticated.")
		 	{
		 		Cache::forget('api_token');
		 		$this->send($case_id,$interval,$participants);
		 	}
		 	
		 }
		 	return json_encode($result);
	}

	public function read($case_id)
	{
		$api_token = $this->login();

		$header_arr = array(
			    'Authorization: Bearer ' . $api_token,
			    'Content-Type: application/json',
			    'Accept: application/json',
			);
		//dd($header_arr);
		//return $api_token;
		 $result = $this->post('reminder/read',json_encode([
		 			"case_id"			=> $case_id
		 			
		 ]),$header_arr);

		 return $result;
		 if($result->response == "ok")
		 {
		 	return json_encode($result);
		 }else{
		 	if($result->message == "Unauthenticated.")
		 	{
		 		Cache::forget('api_token');
		 		$this->read($case_id);
		 	}
		 	
		 }
		 	return json_encode($result);
	}

	
	public function post($uri,$param = array(),$header = array())
	{
		  $curl = curl_init();

          curl_setopt_array($curl, array(
              CURLOPT_RETURNTRANSFER => 1,
              CURLOPT_URL => $this->api_url.'/'.$uri,
              CURLOPT_USERAGENT => 'Curacall_V2',
              CURLOPT_POST => 1,
              CURLOPT_POSTFIELDS => $param,
              CURLOPT_HTTPHEADER => $header
          )); 
     
          //Send the request & save response to $resp
          $resp = curl_exec($curl);
          // Close request to clear up some resources
          //$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          curl_close($curl);

          $result = json_decode($resp);

          return $result;
	}

	public function get($uri,$param = array())
	{
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $this->api_url . '/'.$uri.'?'.http_build_query($param),
		    CURLOPT_USERAGENT => '8888 agency'
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		$result = json_decode($resp);

        return $result;
	}

}