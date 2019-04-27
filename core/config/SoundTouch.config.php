<?php
/**
 * Classe de configuration du plugin
 *  - Infos de l'enceinte
 *  - Action sur l'enceinte
 */

//require_once __DIR__  . '/../../3rparty/SoundTouchKey.class.php';
//require_once __DIR__  . '/../../3rparty/SoundTouchCommand.class.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use Sabinus\SoundTouch\Constants\Key as SoundTouchKey;


class SoundTouchConfig
{

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



    static private $configInfos = array(
    
        array(
            'name' => 'Etat',
            'logicalId' => self::POWERED,
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
            'name' => 'Etat Volume',
            'logicalId' => self::MUTED,
            'type' => 'info',
            'subType' => 'binary',
            'order' => 4,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Etat de lecture',
            'logicalId' => self::STATUS,
            'type' => 'info',
            'subType' => 'string',
            'order' => 5,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Etat Shuffle',
            'logicalId' => self::SHUFFLE,
            'type' => 'info',
            'subType' => 'binary',
            'order' => 6,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Etat Repeat',
            'logicalId' => self::REPEAT,
            'type' => 'info',
            'subType' => 'string',
            'order' => 7,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Track Image',
            'logicalId' => self::TRACK_IMAGE,
            'type' => 'info',
            'subType' => 'string',
            'order' => 8,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Track Artiste',
            'logicalId' => self::TRACK_ARTIST,
            'type' => 'info',
            'subType' => 'string',
            'order' => 9,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Track Titre',
            'logicalId' => self::TRACK_TITLE,
            'type' => 'info',
            'subType' => 'string',
            'order' => 11,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

        array(
            'name' => 'Track Album',
            'logicalId' => self::TRACK_ALBUM,
            'type' => 'info',
            'subType' => 'string',
            'order' => 11,
            'isVisible' => true,
            'generic_type' => 'SPEAKER_STATE',
        ),

    );


    static private $configCommands = array(
    
        array(
            'name' => 'Refresh',
            'logicalId' => self::REFRESH,
            'type' => 'action',
            'subType' => 'other',
            'order' => 20,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),
        
        array(
            'name' => 'Power',
            'logicalId' => self::POWER,
            'type' => 'action',
            'subType' => 'other',
            'order' => 21,
            'codekey' => SoundTouchKey::POWER,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Volume Haut',
            'logicalId' => self::VOLUME_UP,
            'type' => 'action',
            'subType' => 'other',
            'order' => 22,
            'codekey' => SoundTouchKey::VOLUME_UP,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Volume Bas',
            'logicalId' => self::VOLUME_DOWN,
            'type' => 'action',
            'subType' => 'other',
            'order' => 23,
            'codekey' => SoundTouchKey::VOLUME_DOWN,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Mute',
            'logicalId' => self::MUTE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 24,
            'codekey' => SoundTouchKey::MUTE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Set Volume',
            'logicalId' => self::VOLUME_SET,
            'type' => 'action',
            'subType' => 'slider',
            'order' => 25,
            'unity' => '%',
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Présélection 1',
            'logicalId' => self::PRESET_1,
            'type' => 'action',
            'subType' => 'other',
            'order' => 26,
            'codekey' => SoundTouchKey::PRESET_1,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Présélection 2',
            'logicalId' => self::PRESET_2,
            'type' => 'action',
            'subType' => 'other',
            'order' => 27,
            'codekey' => SoundTouchKey::PRESET_2,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Présélection 3',
            'logicalId' => self::PRESET_3,
            'type' => 'action',
            'subType' => 'other',
            'order' => 28,
            'codekey' => SoundTouchKey::PRESET_3,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Présélection 4',
            'logicalId' => self::PRESET_4,
            'type' => 'action',
            'subType' => 'other',
            'order' => 29,
            'codekey' => SoundTouchKey::PRESET_4,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Présélection 5',
            'logicalId' => self::PRESET_5,
            'type' => 'action',
            'subType' => 'other',
            'order' => 40,
            'codekey' => SoundTouchKey::PRESET_5,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Présélection 6',
            'logicalId' => self::PRESET_6,
            'type' => 'action',
            'subType' => 'other',
            'order' => 41,
            'codekey' => SoundTouchKey::PRESET_6,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Play',
            'logicalId' => self::PLAY,
            'type' => 'action',
            'subType' => 'other',
            'order' => 42,
            'codekey' => SoundTouchKey::PLAY,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Pause',
            'logicalId' => self::PAUSE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 43,
            'codekey' => SoundTouchKey::PAUSE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),
        
        array(
            'name' => 'Stop',
            'logicalId' => self::STOP,
            'type' => 'action',
            'subType' => 'other',
            'order' => 44,
            'codekey' => SoundTouchKey::STOP,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Piste précédente',
            'logicalId' => self::PREV_TRACK,
            'type' => 'action',
            'subType' => 'other',
            'order' => 45,
            'codekey' => SoundTouchKey::PREV_TRACK,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Piste suivante',
            'logicalId' => self::NEXT_TRACK,
            'type' => 'action',
            'subType' => 'other',
            'order' => 46,
            'codekey' => SoundTouchKey::NEXT_TRACK,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Play/Pause',
            'logicalId' => self::PLAY_PAUSE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 47,
            'codekey' => SoundTouchKey::PLAY_PAUSE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
        ),

        array(
            'name' => 'Hasard ON',
            'logicalId' => self::SHUFFLE_ON,
            'type' => 'action',
            'subType' => 'other',
            'order' => 48,
            'codekey' => SoundTouchKey::SHUFFLE_ON,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Hasard OFF',
            'logicalId' => self::SHUFFLE_OFF,
            'type' => 'action',
            'subType' => 'other',
            'order' => 49,
            'codekey' => SoundTouchKey::SHUFFLE_OFF,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Répétition OFF',
            'logicalId' => self::REPEAT_OFF,
            'type' => 'action',
            'subType' => 'other',
            'order' => 50,
            'codekey' => SoundTouchKey::REPEAT_OFF,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Répétition Une',
            'logicalId' => self::REPEAT_ONE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 51,
            'codekey' => SoundTouchKey::REPEAT_ONE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Répétition Tous',
            'logicalId' => self::REPEAT_ALL,
            'type' => 'action',
            'subType' => 'other',
            'order' => 52,
            'codekey' => SoundTouchKey::REPEAT_ALL,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Select TV',
            'logicalId' => self::TV,
            'type' => 'action',
            'subType' => 'other',
            'order' => 53,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
        ),

        array(
            'name' => 'Select BLUETOOTH',
            'logicalId' => self::BLUETOOTH,
            'type' => 'action',
            'subType' => 'other',
            'order' => 54,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
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
