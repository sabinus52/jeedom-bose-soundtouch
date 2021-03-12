<?php
/**
 * Librairie de base de l'API
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package SoundTouchApi
 */

use \Sabinus\SoundTouch\SoundTouchApi;


class JeedomSoundTouchApi extends SoundTouchApi
{

    /**
     * @var BoseSoundTouch
     */
    protected $eqLogic;


    /**
     * Constructeur
     * 
     * @param BoseSoundTouch $eqLogic
     * @param Boolean $init : initialise ou pas le statut de l'enceinte
     */
    public function __construct(BoseSoundTouch $eqLogic, $init = true)
    {
        $this->eqLogic = $eqLogic;
        $host = $this->eqLogic->getConfiguration('hostname');

        parent::__construct($host, true);
        
        if ($init) $this->getNowPlaying();
    }

}