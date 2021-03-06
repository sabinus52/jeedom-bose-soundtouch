<?php
/**
 * Classe de configuration du plugin
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/JeedomSoundTouchApi.php';
require_once __DIR__ . '/SoundTouchSource.api.php';
use \Sabinus\SoundTouch\SoundTouchApi;


class SoundTouchConfig
{

    /**
     * Masque du chemin complet du fichier JSON
     */
    const FILE_CONFIG = '/../config/json/%s.json';

    /**
     * Liste des types de commandes
     */
    const CMD_INFO_STATUS       = 'status.info';
    const CMD_ACTION_KEYTOUCH   = 'keytouch.action';
    const CMD_ACTION_PRESETS    = 'presets.action';


    const POWERED = 'PLAYING';
    const SOURCE = 'SOURCE';
    const VOLUME = 'VOLUME';
    const MUTED = 'MUTED';
    const STATUS = 'STATUS';
    const SHUFFLE = 'SHUFFLE';
    const REPEAT = 'REPEAT';
    const TRACK_IMAGE = 'TRACK_IMAGE';
    const TRACK_ARTIST = 'TRACK_ARTIST';
    const TRACK_TITLE = 'TRACK_TITLE';
    const TRACK_ALBUM = 'TRACK_ALBUM';

    const REFRESH = 'REFRESH';
    const POWER = 'POWER';
    const VOLUME_UP = 'VOLUME_UP';
    const VOLUME_DOWN = 'VOLUME_DOWN';
    const VOLUME_SET = 'VOLUME_SET';
    const MUTE = 'MUTE';
    const PRESET_1 = 'PRESET_1';
    const PRESET_2 = 'PRESET_2';
    const PRESET_3 = 'PRESET_3';
    const PRESET_4 = 'PRESET_4';
    const PRESET_5 = 'PRESET_5';
    const PRESET_6 = 'PRESET_6';
    const PLAY = 'PLAY';
    const PAUSE = 'PAUSE';
    const STOP = 'STOP';
    const PREV_TRACK = 'TRACK_PREV';
    const NEXT_TRACK = 'TRACK_NEXT';
    const PLAY_PAUSE = 'PLAY_PAUSE';
    const SHUFFLE_OFF = 'SHUFFLE_OFF';
    const SHUFFLE_ON = 'SHUFFLE_ON';
    const REPEAT_OFF = 'REPEAT_OFF';
    const REPEAT_ONE = 'REPEAT_ONE';
    const REPEAT_ALL = 'REPEAT_ALL';
    const TV = 'TV';
    const BLUETOOTH = 'BLUETOOTH';
    const AUX_INPUT = 'AUX_INPUT';


    static private $cmdTypes = [ self::CMD_INFO_STATUS, self::CMD_ACTION_KEYTOUCH, self::CMD_ACTION_PRESETS ];


    /**
     * Liste des commandes récupérées dans le fichier JSON
     * 
     * @var Array
     */
    private $commands;

    /**
     * @var SoundTouchApi
     */
    private $api;


    /**
     * Constructeur
     */
    public function __construct(SoundTouchApi $api)
    {
        $this->api = $api;
        $this->commands = [];
    }


    /**
     * Retourne la liste des commandes
     * 
     * @return Array
     */
    public function getListCommands()
    {
        $this->commands = [];

        foreach (self::$cmdTypes as $type) {
            $cmds = $this->getCommands($type);
            if ( is_array($cmds) ) $this->commands = array_merge($this->commands, $cmds);
        }

        // Sources
        $this->commands = array_merge($this->commands, $this->getCommandsSource());
        
        return $this->commands;
    }


    /**
     * Retourne les commandes d'un certain type
     * 
     * @param String $type : infos, action, preset
     * @return Array
     */
    private function getCommands($type)
    {
        $commands = $this->loadJSON($type);
        if ( $commands === false ) log::add('BoseSoundTouch', 'warning', 'SAVE : probleme chargement du fichier json '.$type);
        return $commands;
    }


    /**
     * Retourne les commandes de la découverte des sources
     * 
     * @return Array
     */
    private function getCommandsSource()
    {
        $commands = [];
        foreach ($this->api->getSourceLocal() as $source) {
            log::add('BoseSoundTouch', 'debug', 'add ' . $source->getName().' / '.$source->getSource());
            $commands[] = array(
                'name' => 'Select '.$source->getName(),
                'logicalId' => $source->getName(),
                'type' => 'action',
                'subType' => 'other',
                'isVisible' => true,
                'configuration' => array(
                    'contentItem' => array(
                        'account' => $source->getName(),
                        'source' => $source->getSource(),
                    ),
                ),
            );
        }
        return $commands;
    }


    /**
     * Chargement de la configuration d'un équipement depuis le fichier JSON
     * 
     * @return Array|Boolean si KO
     */
    private function loadJSON($typeCmd)
    {
        // Chargement du fichier
        $content = file_get_contents( sprintf(__DIR__.self::FILE_CONFIG, $typeCmd) );
        if ( ! is_json($content) ) return false;
        $result = json_decode($content, true);

        // Vérification du contenu
        if ( ! isset($result['commands']) ) return false;
        if ( ! is_array($result['commands']) ) return false;

        // Affectation du contenu
        return $result['commands'];
    }

}
