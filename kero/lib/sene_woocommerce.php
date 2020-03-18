<?php
class SENE_WooCommerce {
	var $__consumer_key;
	var $__consumer_secret;
	
	public function setKey($key,$secret){
		$this->__consumer_key = $key;
		$this->__consumer_secret = $secret;
	}
	public function dothat($url, $cmd, $params, $method = 'GET'){
		$ch = curl_init();
		
		
		$params['oauth_consumer_key'] = $this->__consumer_key;
		$params['oauth_timestamp'] = time();
		$params['oauth_nonce'] = sha1(microtime());
		$params['oauth_signature_method'] = 'HMAC-' . 'SHA256';
		$params['oauth_signature'] = $this->__generate_oauth_signature( $url, $params, $method, $cmd );
		$param_str = '?' . http_build_query( $params );
		
		
		
		curl_setopt( $ch, CURLOPT_URL, $url . $cmd . $param_str );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        
        $res = curl_exec( $ch );
        
        $info = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        if(empty($info)){
        	print_r($info);
        	die();
        }
        return $res;
	}
	private function __generate_oauth_signature( $url, $params, $method, $cmd ) {
		
		$base_request_uri = rawurlencode( $url.$cmd );

		// normalize parameter key/values and sort them
		$params = $this->__param_norm($params);
		uksort($params, 'strcmp');

		// form query string
		$query_params = array();
		foreach ( $params as $param_key => $param_value ) {
			$query_params[] = $param_key . '%3D' . $param_value; // join with equals sign
		}

		$query_string = implode( '%26', $query_params ); // join with ampersand

		// form string to sign (first key)
		$string_to_sign = $method . '&' . $base_request_uri . '&' . $query_string;
		
		//die($string_to_sign);
		return base64_encode( hash_hmac( 'SHA256', $string_to_sign, $this->__consumer_secret, true ) );
	}
	private function __param_norm($params){
		$norm = array();

		foreach ( $params as $key => $value ) {

			$key   = str_replace( '%', '%25', rawurlencode( rawurldecode( $key ) ) );
			$value = str_replace( '%', '%25', rawurlencode( rawurldecode( $value ) ) );

			$norm[$key] = $value;
		}

		return $norm;
	}
}
?>