<?php

/**
 *  Zapier Class
 *
 *  Handle API request and connect with Zapier
 */
class Zapier
{
    private $webhook_url;

    /**
     * Create a new instance
     * @param string $webhook_url Your Zapier 
     */
    function __construct($webhook_url)
    {
        $this->webhook_url = $webhook_url;
    }

    /**
     * @param  string $url The webhook url
     * @param  jeson  $jeson   An array of arguments to pass to the method. Will be json-encoded for you.
     */
    public function call($json)
    {
        $url = $this->webhook_url;
        $headers = array('Accept: application/json', 'Content-Type: application/json');
        return $this->makeRequest($url, $json, $headers);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting
     * @param  string $method The API method to be called
     * @param  array  $args   Assoc array of parameters to be passed
     * @return array          Assoc array of decoded result
     */
    private  function makeRequest($url, $json, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        echo $output;
        curl_close($ch);
    }
}
