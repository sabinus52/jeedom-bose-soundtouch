<?php
/**
 * 
 */

class SoundTouchCommand
{

    const BASE_URI = 'http://%s:8090/';

    private $baseUri;

    public function __construct($hostname) {
        $this->baseUri = sprintf(self::BASE_URI, $hostname);
    }

    private function get($path) {
        $curl = curl_init($this->baseUri.$path);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function post($path, $body) {
        $curl = curl_init($this->baseUri.$path);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendCommand($command) {
        $this->post('key', '<key state="press" sender="Gabbo">'.$command.'</key>');
        return $this->post('key', '<key state="release" sender="Gabbo">'.$command.'</key>');
    }

    public function setVolume($value) {
        return $this->post('volume', '<volume>'.intval($value).'</volume>');
    }

    public function getResponse($command) {
        $response = $this->get($command);
        return simplexml_load_string($response);
    }


}