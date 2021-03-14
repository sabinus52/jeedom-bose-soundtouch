<?php
/**
 * Librairie de la gestion des informations en cours de lecture
 */

use \Sabinus\SoundTouch\Constants\PlayStatus;
use \Sabinus\SoundTouch\Constants\StreamStatus;
use \Sabinus\SoundTouch\Component\NowPlaying;



class SoundTouchNowPlayingApi extends JeedomSoundTouchApi
{

    /**
     * Retourne la source sélectionnée
     * 
     * @return String
     */
    public function getCurrentSource($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( !$status->getSource() )
            return null;
        elseif ( $status->getSource() != 'PRODUCT' )
            return strval($status->getSource());
        elseif ( $status->getContentItem() && $status->getContentItem()->getAccount() )
            return strval($status->getContentItem()->getAccount());
        else
            return strval($status->getSource());
    }


    /**
     * Retourne le statut du streaming
     * 
     * @return String
     */
    public function getStatePlay($refresh = null)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        switch ($status->getPlayStatus()) {
            case PlayStatus::PLAY_STATE         : return 'PLAY';
            case PlayStatus::PAUSE_STATE        : return 'PAUSE';
            case PlayStatus::STOP_STATE         : return 'STOP';
            case PlayStatus::BUFFERING_STATE    : return 'BUFFERING';
            default                             : return 'OFF';
        }
    }


    /**
     * Retourne l'état du shuffle
     * 
     * @return Boolean
     */
    public function isShuffle($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return ( $status->getShuffleSetting() == PlayStatus::SHUFFLE_ON );
    }


    /**
     * Retourne l'état du repeat
     * 
     * @return String
     */
    public function getStateRepeat($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        switch ($status->getRepeatSetting()) {
            case PlayStatus::REPEAT_OFF : return 'OFF';
            case PlayStatus::REPEAT_ALL : return 'ALL';
            case PlayStatus::REPEAT_ONE : return 'ONE';
            default                     : return 'OFF';
        }
    }


    /**
     * Retourne l'artiste de la piste en cours
     * 
     * @return String
     */
    public function getTrackArtist($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getStreamType() == StreamStatus::RADIO_STREAMING && $status->getStationName() ) {
            return $status->getStationName();
        } elseif ( $status->getStreamType() == StreamStatus::RADIO_STREAMING && $status->getTrack() ) {
            return $status->getTrack();
        } elseif ( $status->getArtist() ) {
            return $status->getArtist();
        } elseif ( $this->isPowered() ) {
            return $this->getCurrentSource();
        } else {
            return null;
        }
    }


    /**
     * Retourne le titre de la piste en cours
     * 
     * @return String
     */
    public function getTrackTitle($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getStreamType() == StreamStatus::RADIO_STREAMING && $status->getArtist() ) {
            return $status->getArtist();
        } else {
            return $status->getTrack();
        }
    }


    /**
     * Retourne l'album de la piste en cours
     * 
     * @return String
     */
    public function getTrackAlbum($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return $status->getAlbum();
    }


    /**
     * Retourne l'image de la piste en cours
     * 
     * @return String
     */
    public function getTrackImage($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return $status->getImage();
    }


    /**
     * Retourne les données de l'image (chemin + URI)
     * 
     * @return Array[image,uri]
     */
    public function getPreviewArray($oldImage, $refresh = false)
    {
        $newImage = $this->getPreviewImage($refresh);
        return array(
            'image' => $newImage,
            'uri'   => $this->getPreviewUri($newImage, $oldImage),
        );
    }


    /**
     * Retourne l'image de la source courante
     * 
     * @return String
     */
    private function getPreviewImage($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getImage() ) {
            return $status->getImage();
        } elseif ( $status->getContentItem() ) {
            return $this->getImageFromContentItem($status->getContentItem());
        } else {
            return 'file://'.realpath(__DIR__ . '/../..').sprintf(self::PATH_IMAGE, 'invalid_source');;
        }
    }


    /**
     * Retourne l'URI de l'image courante
     * 
     * @param String $newImage : URL de la nouvelle image
     * @param String $oldImage : URL de l'ancienne image
     * @param String
     */
    private function getPreviewUri($newImage, $oldImage)
    {
        // Image local en cache
        $cacheName = 'preview-'.$this->eqLogic->getId();
        $cacheImage = SoundTouchConfig::getFileImageCache($cacheName);

        if ( $newImage ) {
            log::add('BoseSoundTouch', 'debug', 'preview = '.$cacheImage);
            SoundTouchConfig::storeImageCache($cacheImage, $oldImage, $newImage);
            return SoundTouchConfig::getUriImageCache($cacheName, $newImage);
        } else {
            SoundTouchConfig::clearImageCache($cacheImage);
            return null;
        }

    }
}