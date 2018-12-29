<?php
/**
 * Classe de configuration du plugin
 *  - Infos de l'enceinte
 *  - Action sur l'enceinte
 */

require_once __DIR__  . '/../../3rparty/SoundTouchKey.class.php';
require_once __DIR__  . '/../../3rparty/SoundTouchCommand.class.php';

class SoundTouchConfig
{
    /*const PLAY = "PLAY";
    const PAUSE = "PAUSE";
    const STOP = "STOP";
    const PREV_TRACK = "PREV_TRACK";
    const NEXT_TRACK = "NEXT_TRACK";
    const THUMBS_UP = "THUMBS_UP";
    const THUMBS_DOWN = "THUMBS_DOWN";
    const BOOKMARK = "BOOKMARK";
    const POWER = "POWER";
    const MUTE = "MUTE";
    const VOLUME_UP = "VOLUME_UP";
    const VOLUME_DOWN = "VOLUME_DOWN";
    const PRESET_1 = "PRESET_1";
    const PRESET_2 = "PRESET_2";
    const PRESET_3 = "PRESET_3";
    const PRESET_4 = "PRESET_4";
    const PRESET_5 = "PRESET_5";
    const PRESET_6 = "PRESET_6";
    const AUX_INPUT = "AUX_INPUT";
    const SHUFFLE_OFF = "SHUFFLE_OFF";
    const SHUFFLE_ON = "SHUFFLE_ON";
    const REPEAT_OFF = "REPEAT_OFF";
    const REPEAT_ONE = "REPEAT_ONE";
    const REPEAT_ALL = "REPEAT_ALL";
    const PLAY_PAUSE = "PLAY_PAUSE";
    const ADD_FAVORITE = "ADD_FAVORITE";
    const REMOVE_FAVORITE = "REMOVE_FAVORITE";
    const INVALID_KEY = "INVALID_KEY";*/
    const PLAYING = 'PLAYING';
    const SOURCE = 'SOURCE';
    const VOLUME = 'VOLUME';
    const BASS = 'BASS';

    const REFRESH = 'REFRESH';
    const POWER = 'POWER';
    const VOLUME_UP = 'VOLUME_UP';
    const VOLUME_DOWN = 'VOLUME_DOWN';
    const MUTE = 'MUTE';


    static private $configInfos = array(
    
        array(
            'name' => 'Etat',
            'logicalId' => self::PLAYING,
            'type' => 'info',
            'subType' => 'binary',
            'order' => 1,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Source',
            'logicalId' => self::SOURCE,
            'type' => 'info',
            'subType' => 'string',
            'order' => 2,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_SOURCE',
        ),

        array(
            'name' => 'Volume',
            'logicalId' => self::VOLUME,
            'type' => 'info',
            'subType' => 'numeric',
            'order' => 3,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Bass',
            'logicalId' => self::BASS,
            'type' => 'info',
            'subType' => 'numeric',
            'order' => 4,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_BASS',
        ),
        
    );


    static private $configCommands = array(
    
        array(
            'name' => 'Refresh',
            'logicalId' => self::REFRESH,
            'type' => 'action',
            'subType' => 'other',
            'order' => 10,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),
        
        array(
            'name' => 'Power',
            'logicalId' => self::POWER,
            'type' => 'action',
            'subType' => 'other',
            'order' => 11,
            'codekey' => SoundTouchKey::POWER,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Volume Haut',
            'logicalId' => self::VOLUME_UP,
            'type' => 'action',
            'subType' => 'other',
            'order' => 12,
            'codekey' => SoundTouchKey::VOLUME_UP,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Volume Bas',
            'logicalId' => self::VOLUME_DOWN,
            'type' => 'action',
            'subType' => 'other',
            'order' => 13,
            'codekey' => SoundTouchKey::VOLUME_DOWN,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Mute',
            'logicalId' => self::MUTE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 14,
            'codekey' => SoundTouchKey::MUTE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

    );


    static public function getConfigCmds()
    {
        return self::$configCommands;
    }


    static public function getConfigInfos()
    {
        return self::$configInfos;
    }

}
