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

    const PLAYING = 'PLAYING';
    const SOURCE = 'SOURCE';
    const VOLUME = 'VOLUME';
    const BASS = 'BASS';

    const REFRESH = 'REFRESH';
    const POWER = 'POWER';
    const VOLUME_UP = 'VOLUME_UP';
    const VOLUME_DOWN = 'VOLUME_DOWN';
    const MUTE = 'MUTE';
    const PRESET_1 = "PRESET_1";
    const PRESET_2 = "PRESET_2";
    const PRESET_3 = "PRESET_3";
    const PRESET_4 = "PRESET_4";
    const PRESET_5 = "PRESET_5";
    const PRESET_6 = "PRESET_6";
    const PLAY = "PLAY";
    const PAUSE = "PAUSE";
    const STOP = "STOP";
    const PREV_TRACK = "PREV_TRACK";
    const NEXT_TRACK = "NEXT_TRACK";
    const PLAY_PAUSE = "PLAY_PAUSE";
    const SHUFFLE_OFF = "SHUFFLE_OFF";
    const SHUFFLE_ON = "SHUFFLE_ON";
    const REPEAT_OFF = "REPEAT_OFF";
    const REPEAT_ONE = "REPEAT_ONE";
    const REPEAT_ALL = "REPEAT_ALL";


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
            'display' => array(
                'icon' => 'refresh',
                'template' => 'default',
                'div.width' => 50,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
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
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'power',
                'template' => 'power',
                'div.width' => 50,
                'div.height' => 101,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
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
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'volume-up',
                'template' => 'default',
                'div.width' => 50,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
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
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'volume-down',
                'template' => 'default',
                'div.width' => 50,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
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
            'display' => array(
                'icon' => 'mute',
                'template' => 'default',
                'div.width' => 50,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Présélection 1',
            'logicalId' => self::PRESET_1,
            'type' => 'action',
            'subType' => 'other',
            'order' => 15,
            'codekey' => SoundTouchKey::PRESET_1,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'p1',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Présélection 2',
            'logicalId' => self::PRESET_2,
            'type' => 'action',
            'subType' => 'other',
            'order' => 16,
            'codekey' => SoundTouchKey::PRESET_2,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'p2',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Présélection 3',
            'logicalId' => self::PRESET_3,
            'type' => 'action',
            'subType' => 'other',
            'order' => 17,
            'codekey' => SoundTouchKey::PRESET_3,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'p3',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Présélection 4',
            'logicalId' => self::PRESET_4,
            'type' => 'action',
            'subType' => 'other',
            'order' => 18,
            'codekey' => SoundTouchKey::PRESET_4,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'p4',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Présélection 5',
            'logicalId' => self::PRESET_5,
            'type' => 'action',
            'subType' => 'other',
            'order' => 19,
            'codekey' => SoundTouchKey::PRESET_5,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'p5',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Présélection 6',
            'logicalId' => self::PRESET_6,
            'type' => 'action',
            'subType' => 'other',
            'order' => 20,
            'codekey' => SoundTouchKey::PRESET_6,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
            'display' => array(
                'icon' => 'p6',
                'template' => 'preset',
                'div.width' => 70,
                'div.height' => 70,
                'icon.width' => 48,
                'icon.height' => 48,
            ),
        ),

        array(
            'name' => 'Play',
            'logicalId' => self::PLAY,
            'type' => 'action',
            'subType' => 'other',
            'order' => 21,
            'codekey' => SoundTouchKey::PLAY,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'play',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Pause',
            'logicalId' => self::PAUSE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 22,
            'codekey' => SoundTouchKey::PAUSE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'pause',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),
        
        array(
            'name' => 'Stop',
            'logicalId' => self::STOP,
            'type' => 'action',
            'subType' => 'other',
            'order' => 23,
            'codekey' => SoundTouchKey::STOP,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'stop',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Piste précédente',
            'logicalId' => self::PREV_TRACK,
            'type' => 'action',
            'subType' => 'other',
            'order' => 24,
            'codekey' => SoundTouchKey::PREV_TRACK,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'step-backward',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Piste suivante',
            'logicalId' => self::NEXT_TRACK,
            'type' => 'action',
            'subType' => 'other',
            'order' => 25,
            'codekey' => SoundTouchKey::NEXT_TRACK,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'step-forward',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Play/Pause',
            'logicalId' => self::PLAY_PAUSE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 26,
            'codekey' => SoundTouchKey::PLAY_PAUSE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '1',
            'display' => array(
                'icon' => 'play-pause',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Hasard ON',
            'logicalId' => self::SHUFFLE_ON,
            'type' => 'action',
            'subType' => 'other',
            'order' => 27,
            'codekey' => SoundTouchKey::SHUFFLE_ON,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'shuffle-on',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Hasard OFF',
            'logicalId' => self::SHUFFLE_OFF,
            'type' => 'action',
            'subType' => 'other',
            'order' => 28,
            'codekey' => SoundTouchKey::SHUFFLE_OFF,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'shuffle-off',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Répétition OFF',
            'logicalId' => self::REPEAT_OFF,
            'type' => 'action',
            'subType' => 'other',
            'order' => 29,
            'codekey' => SoundTouchKey::REPEAT_OFF,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'repeat-off',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Répétition Une',
            'logicalId' => self::REPEAT_ONE,
            'type' => 'action',
            'subType' => 'other',
            'order' => 30,
            'codekey' => SoundTouchKey::REPEAT_ONE,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'repeat-one',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
        ),

        array(
            'name' => 'Répétition Tous',
            'logicalId' => self::REPEAT_ALL,
            'type' => 'action',
            'subType' => 'other',
            'order' => 31,
            'codekey' => SoundTouchKey::REPEAT_ALL,
            'isVisible' => true,
            'generic_type' => 'GENERIC_ACTION',
            'forceReturnLineAfter' => '0',
            'display' => array(
                'icon' => 'repeat-all',
                'template' => 'default',
                'div.width' => 60,
                'div.height' => 50,
                'icon.width' => 24,
                'icon.height' => 24,
            ),
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
