<?php
/**
 * Librairie de base de l'API
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package SoundTouchApi
 */

//namespace Sabinus\SoundTouch;

use \Sabinus\SoundTouch\SoundTouchApi;
use \Sabinus\SoundTouch\Constants\Key;
use \Sabinus\SoundTouch\Constants\Source;
use \Sabinus\SoundTouch\Request\GetVolumeRequest;
use \Sabinus\SoundTouch\Request\GetBassRequest;
use \Sabinus\SoundTouch\Component\NowPlaying;
use \Sabinus\SoundTouch\Component\Volume;
use \Sabinus\SoundTouch\Component\Bass;
use \Sabinus\SoundTouch\Component\ContentItem;


class JeedomSoundTouchApi extends SoundTouchApi
{

    /**
     * Constructeur
     * 
     * @param String $host
     * @param Boolean $init : initialise ou pas le statut de l'enceinte
     */
    public function __construct($host, $init = true)
    {
        parent::__construct($host, true);
        if ($init) $this->getNowPlaying();
    }


    


    /**
     * Retourne la source sélectionnée
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


    


    public function selectTuneIn($station)
    {
        $source = new ContentItem();
        $source->setSource(Source::TUNEIN)
            ->setType('stationurl')
            ->setLocation($station);
        $this->selectSource($source);
    }

    public function selectRadioInternet($name, $station)
    {
        $source = new ContentItem();
        $source->setSource('LOCAL_INTERNET_RADIO')
            ->setType('stationurl')
            ->setName($name)
            ->setLocation($station);
        $this->selectSource($source);
    }


    /**
     * Retourne le status en cours
     */
    public function getArrayNowPlaying($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return array(
            'source.power' => $this->isPowered(),
            'source.type' => $this->getCurrentSource(),
            'source.name' => $status->getContentItem()->getName(),
            'source.image' => $status->getContentItem()->getImage(),
            'status.playing' => $this->getStatePlay(),
            'status.repeat' => $this->getStateRepeat(),
            'status.shuffle' => $this->isShuffle(),
            'track.artist' => $this->getTrackArtist(),
            'track.title' => $this->getTrackTitle(),
            'track.album' => $this->getTrackAlbum(),
            'track.image' => $this->getTrackImage(),
            'volume.level' => $this->getLevelVolume(),
            'volume.muted' => $this->isMuted(),
        );
    }


    /**
     * Retourne l'image de la source courante
     */
    public function getPreviewImage($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getImage() ) {
            return $status->getImage();
        } elseif ( $status->getContentItem()->getImage() ) {
            return $status->getContentItem()->getImage();
        } elseif ( $this->getCurrentSource() ) {
            $current = $this->getCurrentSource();
            if ( intval(substr($current, -1)) > 0 ) {
                return 'local://'.substr($current, 0, -1);
            } else {
                return 'local://'.$current;
            }
        } else {
            return 'local://null';
        }
    }


    public function isShuffle($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return ( $status->getShuffleSetting() == 'SHUFFLE_ON' );
    }


    public function getStateRepeat($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        switch ($status->getRepeatSetting()) {
            case 'REPEAT_OFF': return 'OFF';
            case 'REPEAT_ALL': return 'ALL';
            case 'REPEAT_ONE': return 'ONE';
            default          : return 'OFF';
        }
    }


    public function getStatePlay($refresh = null)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        switch ($status->getPlayStatus()) {
            case 'PLAY_STATE'       : return 'PLAY';
            case 'PAUSE_STATE'      : return 'PAUSE';
            case 'STOP_STATE'       : return 'STOP';
            case 'BUFFERING_STATE'  : return 'BUFFERING';
            default                 : return 'OFF';
        }
    }


    public function getTrackArtist($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getStreamType() == 'RADIO_STREAMING' && $status->getStationName() ) {
            return $status->getStationName();
        } elseif ( $status->getStreamType() == 'RADIO_STREAMING' && $status->getTrack() ) {
            return $status->getTrack();
        } elseif ( $status->getArtist() ) {
            return $status->getArtist();
        } elseif ( $this->isPowered() ) {
            return $this->getCurrentSource();
        } else {
            return null;
        }
    }

    public function getTrackTitle($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        if ( $status->getStreamType() == 'RADIO_STREAMING' && $status->getArtist() ) {
            return $status->getArtist();
        } else {
            return $status->getTrack();
        }
    }

    public function getTrackAlbum($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return $status->getAlbum();
    }

    public function getTrackImage($refresh = false)
    {
        $status = $this->getNowPlaying($refresh);
        if ( ! ($status instanceof NowPlaying) ) return null;
        return $status->getImage();
    }

}