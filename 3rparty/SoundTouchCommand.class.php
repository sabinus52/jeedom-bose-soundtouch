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
        $response = $this->post('key', '<key state="release" sender="Gabbo">'.$command.'</key>');
        $result = simplexml_load_string($response);
        return ($result == '/key') ? true : false;
    }

    public function setVolume($value) {
        return $this->post('volume', '<volume>'.intval($value).'</volume>');
    }

    public function getResponse($command) {
        $response = $this->get($command);
        return simplexml_load_string($response);
    }


    /**
     * Retourne si l'enceinte est allumée ou éteinte
     */
    public function getStatePower()
    {
        $response = $this->getResponse('now_playing');
        return ($response->playStatus == 'PLAY_STATE') ? true : false;
    }


    /**
     * Retourne le type de la source sélectionnée
     */
    public function getTypeSource()
    {
        $response = $this->getResponse('now_playing');
        return ($response->ContentItem['sourceAccount']) ? strval($response->ContentItem['sourceAccount']) : null;
    }


    /**
     * Retourne le pourcentage de volume
     */
    public function getVolume()
    {
        $response = $this->getResponse('volume');
        return ($response->actualvolume) ? intval($response->actualvolume) : null;
    }


    /**
     * Retourne le niveau des basses
     */
    public function getLevelBass()
    {
        $response = $this->getResponse('bass');
        return ($response->actualbass) ? intval($response->actualbass) : null;
    }

}