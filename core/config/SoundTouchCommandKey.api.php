<?php
/**
 * Librairie de la gestion commandes de touches dans Jeedom
 */

use \Sabinus\SoundTouch\Constants\Key;
use \Sabinus\SoundTouch\Constants\Source;
use \Sabinus\SoundTouch\Component\NowPlaying;



class SoundTouchCommandKeyApi extends JeedomSoundTouchApi
{


    /**
     * Envoie une commande de touche
     * 
     * @param BoseSoundTouchCmd $command : Commande Jeedom
     */
    public function sendCommandJeedom(BoseSoundTouchCmd $command)
    {
        $key = $command->getConfiguration('codekey');
        if ( empty($key) ) {
            SoundTouchLog::debug('EXECUTE', 'Touche vide');
            return false;
        }

        $this->sendCommandAndLog($key);

        // Vérifie en fonction d'un certain contexte si le son est coupé alors on le rallumme si appuie de du son ou de play
        if ( $key == Key::PLAY_PAUSE || $key == Key::VOLUME_DOWN ) {
            $this->turnOnVolumeIsMuted();
        }
    }


    /**
     * Envoi la commande de touche et journalise
     * 
     * @param String $key : Clé de la touche
     */
    private function sendCommandAndLog($key)
    {
        SoundTouchLog::info('EXECUTE', 'Touche "'.$key.'" sur l\'enceinte "'.$this->hostname.'"');
        $response = $this->setKey($key);
        SoundTouchLog::debug('EXECUTE', 'setKey('.$key.') -> '.( ($response !== false) ? 'OK' : 'NOK'));
        if ( $response === false ) SoundTouchLog::debug('EXECUTE', 'setKey('.$key.') -> '.$this->getMessageError());
    }


    /**
     * Ajuste le volume de l'enceinte
     * 
     * @param Integer $volume
     */
    public function setVolumeJeedom($volume)
    {
        $this->turnOnVolumeIsMuted();
        SoundTouchLog::info('EXECUTE', 'Ajuste volume sur l\'enceinte "'.$this->hostname.'"');
        $response = $this->setVolume($volume);
        SoundTouchLog::debug('EXECUTE', 'setVolume('.$volume.') -> '.( ($response !== false) ? 'OK' : 'NOK'));
        if ( $response === false ) SoundTouchLog::debug('EXECUTE', 'setVolume('.$volume.') -> '.$this->getMessageError());
    }


    /**
     * Vérifie en fonction d'un certain contexte si le son est coupé alors on le rallumme
     */
    public function turnOnVolumeIsMuted()
    {
        //$isMuted = $this->isMuted(true);
        $isMuted = $this->eqLogic->getCmd(null, 'MUTED')->execCmd();
        SoundTouchLog::debug('EXECUTE', 'Check isMuted = '.$isMuted);
        if ($isMuted) {
            $this->sendCommandAndLog(Key::MUTE);
        }
    }

}