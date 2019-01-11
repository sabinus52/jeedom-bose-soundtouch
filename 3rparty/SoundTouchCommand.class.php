<?php
/**
 * Class de commande de l'enceinte
 *  - Récuperation des infos de l'enceinte
 *  - Activation de l'appui d'une touche de la télécommande
 *  - Ajustement du volume
 */

class SoundTouchCommand
{

    /**
     * Base de l'URI de l'intérrogation l'enceinte
     */
    const BASE_URI = 'http://%s:8090';

    /**
     * Base de l'URI
     * 
     * @var String
     */
    private $baseUri;

    /**
     * Contenu de la réponse du /now_playing
     * 
     * @var SimpleXMLElement
     */
    private $nowPlaying;

    /**
     * Erreur éventuelle
     * 
     * @var String
     */
    private $error;


    /**
     * Constructeur
     * 
     * @param String $hostname : Hôte ou IP de l'enceinte sur le réseau
     */
    public function __construct($hostname)
    {
        $this->baseUri = sprintf(self::BASE_URI, $hostname);
        $this->nowPlaying = null;
        $this->error = '';
    }


    /**
     * Intérrogation pour la récupération des infos de l'enceinte
     * 
     * @param String $path : Chemin de la requête
     * @return String au format XML
     */
    private function get($path)
    {
        $curl = curl_init($this->baseUri.$path);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        if ($errno = curl_errno($curl)) {
            $this->error = curl_strerror($errno);
        }
        curl_close($curl);
        return $response;
    }

    /**
     * Retourne la réponse de l'intérrogation d'une info
     * 
     * @param String $command : Commande
     * @param SimpleXMLElement
     */
    private function getResponse($command)
    {
        $response = $this->get('/'.$command);
        return simplexml_load_string($response);
    }


    /**
     * Intérrogation pour l'envoi d'une commande à l'enceinte
     * 
     * @param String $path : Chemin de la requête
     * @param String $body : Contenu de la commande
     * @return String au format XML
     */
    private function post($path, $body)
    {
        $curl = curl_init($this->baseUri.$path);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $response = curl_exec($curl);
        if ($errno = curl_errno($curl)) {
            $this->error = curl_strerror($errno);
        }
        curl_close($curl);
        return $response;
    }


    /**
     * Retourne l'erreur éventuelle
     * 
     * @return String
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Envoi une commande à l'enceinte
     * 
     * @param String $command : Commande
     * @return Boolean
     */
    public function sendCommand($command)
    {
        $this->post('/key', '<key state="press" sender="Gabbo">'.$command.'</key>');
        $response = $this->post('/key', '<key state="release" sender="Gabbo">'.$command.'</key>');
        $result = simplexml_load_string($response);
        return ($result == '/key') ? true : false;
    }


    /**
     * Ajuste le volume
     * 
     * @param Integer $value : Poucentage du volume à affecter
     * @return Boolean
     */
    public function setVolume($value)
    {
        return $this->post('/volume', '<volume>'.intval($value).'</volume>');
    }


    /**
     * Retourne si l'enceinte est allumée ou éteinte
     */
    public function getStatePower()
    {
        if ( !$this->nowPlaying ) $this->nowPlaying = $this->getResponse('now_playing');
        return ($this->nowPlaying->ContentItem['source'] == 'STANDBY') ? false : true;
    }


    /**
     * Retourne le type de la source sélectionnée
     */
    public function getTypeSource()
    {
        if ( !$this->nowPlaying ) $this->nowPlaying = $this->getResponse('now_playing');
        if ( !$this->nowPlaying->ContentItem['source'] )
            return null;
        elseif ( $this->nowPlaying->ContentItem['source'] != 'PRODUCT' )
            return strval($this->nowPlaying->ContentItem['source']);
        elseif ( $this->nowPlaying->ContentItem['sourceAccount'] )
            return strval($this->nowPlaying->ContentItem['sourceAccount']);
        else
            return strval($this->nowPlaying->ContentItem['source']);
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


    /**
     * Retourne la liste des préselections de 1 à 6
     * 
     * @return Array
     */
    public function getPresets()
    {
        $response = $this->getResponse('presets');
        $result = array();
        foreach ($response->preset as $preset) {
            $result[intval($preset['id'])] = array(
                'source'    => strval($preset->ContentItem['source']),
                'name'      => strval($preset->ContentItem->itemName),
                'image'     => strval($preset->ContentItem->containerArt),
            );
        }
        return $result;
    }


    /**
     * Retourne les données de la lecture en cours
     * 
     * @return Array
     */
    public function getNowPlaying()
    {
        if ( !$this->nowPlaying ) $this->nowPlaying = $this->getResponse('now_playing');
        return array(
            'source.name' => $this->_getSourceName(),
            'source.type' => $this->_getSourceType(),
            'source.image' => $this->_getSourceImage(),
        );
    }


    /**
     * Retourne un nom à la source en cours de lecture
     */
    private function _getSourceName()
    {
        if ( $this->nowPlaying->ContentItem->itemName )
            return strval($this->nowPlaying->ContentItem->itemName);
        else
            return $this->_getSourceType();
    }

    /**
     * Retourne le type de la source en cours de lecture
     */
    private function _getSourceType()
    {
        if ( !$this->nowPlaying->ContentItem['source'] )
            return null;
        elseif ( $this->nowPlaying->ContentItem['source'] != 'PRODUCT' )
            return strtolower($this->nowPlaying->ContentItem['source']);
        elseif ( $this->nowPlaying->ContentItem['sourceAccount'] == 'HDMI_1' || $this->nowPlaying->ContentItem['sourceAccount'] == 'HDMI_2' )
            return 'hdmi';
        elseif ( $this->nowPlaying->ContentItem['sourceAccount'] )
            return strtolower($this->nowPlaying->ContentItem['sourceAccount']);
        else
            return strtolower($this->nowPlaying->ContentItem['source']);
    }

    /**
     * Retourne la vignette de la source en cours
     */
    private function _getSourceImage()
    {
        return strval($this->nowPlaying->ContentItem->containerArt);
    }

}