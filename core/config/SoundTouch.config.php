<?php
/**
 * Classe de configuration du plugin
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/SoundTouchLog.class.php';
require_once __DIR__ . '/JeedomSoundTouchApi.php';
require_once __DIR__ . '/SoundTouchSource.api.php';
require_once __DIR__ . '/SoundTouchZone.api.php';
require_once __DIR__ . '/SoundTouchCommandKey.api.php';
require_once __DIR__ . '/SoundTouchNowPlaying.api.php';
use \Sabinus\SoundTouch\SoundTouchApi;


class SoundTouchConfig
{

    /**
     * Masque des images à mettre en cache
     */
    const MASK_CACHE = 'plugins/BoseSoundTouch/data/cache-%s.png';

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


    /**
     * Commandes de type "info"
     */
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

    const ZONE = 'ZONE';

    const REFRESH = 'REFRESH';
    const VOLUME_SET = 'VOLUME_SET';



    static private $cmdTypes = [ self::CMD_INFO_STATUS, self::CMD_ACTION_KEYTOUCH ];


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

        // Présélection
        $this->commands = array_merge($this->commands, $this->getCommandsPreset());

        // Sources
        $this->commands = array_merge($this->commands, $this->getCommandsSource());

        // MultiRoom
        $this->commands = array_merge($this->commands, $this->getCommandsZone());
        
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
        if ( $commands === false ) SoundTouchLog::warning('SAVE CMD', $this->api->getEqLogic(), 'Probleme chargement du fichier json '.$type);
        return $commands;
    }


    /**
     * Retourne la liste des commandes de présélection
     * 
     * @return Array
     */
    private function getCommandsPreset()
    {
        $commands = [];
        for ($i = 1; $i <= 6; $i++) {
            $commands[] = array(
                'name' => 'Présélection '.$i,
                'logicalId' => 'PRESET_'.$i,
                'type' => 'action',
                'subType' => 'other',
                'isVisible' => true,
                'configuration' => array(
                    'codekey' => 'PRESET_'.$i,
                    'preset' => $i,
                ),
            );
        }
        return $commands;
    }


    /**
     * Mets à jour la commande en fonction des données de la présélection
     * 
     * @param BoseSoundTouchCmd $command : Commande jeedom
     */
    public function updatePreset(BoseSoundTouchCmd $command)
    {
        // Ancien contenu des données de la présélection
        $oldContent = $command->getConfiguration('content');

        // Récupération du nouveau contenu de la présélection
        $idPreset = $command->getConfiguration('preset');
        $newContent = $this->api->getPresetByNum($idPreset);

        // Image local en cache
        $cacheName = 'preset'.$idPreset.'-'.$command->getEqLogic_id();
        $cacheImage = self::getFileImageCache($cacheName);

        if ( $newContent ) {
            // Présence d'une présélection
            self::storeImageCache($cacheImage, $oldContent['image'], $newContent['image']);
            // Sauvegarde les données de la présélection dans la commande
            $newContent['uri'] = self::getUriImageCache($cacheName, $newContent['image']);
            $command->setConfiguration('content', $newContent);
        } else {
            // Plus de préselection affectée
            self::clearImageCache($cacheImage);
            $command->setConfiguration('content', null);
        }

        SoundTouchLog::info('PRESETS', $this->api->getEqLogic(), $idPreset.' = '.$newContent['name'].' ('.$newContent['source'].')');
        SoundTouchLog::debug('PRESETS', $this->api->getEqLogic(), $command->getLogicalId().' = '.print_r($newContent, true));
        $command->save();
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
            SoundTouchLog::debug('SAVE CMD', $this->api->getEqLogic(), 'Source trouvée : '.$source->getName().' / '.$source->getSource());
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
     * Retourne les commandes pour le MultiRoom
     * 
     * @return Array
     */
    private function getCommandsZone()
    {
        $commands = [];
        SoundTouchLog::debug('SAVE CMD', $this->api->getEqLogic(), 'Zone actuelle : '.$this->api->getEqLogicName().' ('.$this->api->getEqLogicId().')');
        foreach (eqLogic::byType('BoseSoundTouch') as $equipment) {
            // On utilise pas le SoundTouch actuel
            if ( $equipment->getId() == $this->api->getEqLogicId()) continue;
            SoundTouchLog::debug('SAVE CMD', $this->api->getEqLogic(), 'Zone trouvée : '.$equipment->getName().' ('.$equipment->getId().')');
            $zone = $equipment->getconfiguration('zone');
            if ( empty($zone) ) continue;
            $commands[] = array(
                'name' => 'Ajout zone '.$equipment->getName(),
                'logicalId' => '+'.$equipment->getLogicalID(),
                'type' => 'action',
                'subType' => 'other',
                'isVisible' => true,
                'configuration' => array(
                    'zone' => $zone + array('action' => 'ADD'),
                ),
            );
            $commands[] = array(
                'name' => 'Supression zone '.$equipment->getName(),
                'logicalId' => '-'.$equipment->getLogicalID(),
                'type' => 'action',
                'subType' => 'other',
                'isVisible' => true,
                'configuration' => array(
                    'zone' => $zone + array('action' => 'SUB'),
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



    // === FONCTIONS STATIQUES SUR LE CACHE ======================================================================================== //


    /**
     * Stocke l'image dans le dossier de cache
     * 
     * @param String $cacheImage : Nom du fichier complet de cache
     * @param String $oldImage   : Url de l'ancienne image
     * @param String $newImage   : Url de la nouvelle image
     */
    static public function storeImageCache($cacheImage, $oldImage, $newImage)
    {
        // Compare pour voir si un changement a eu lieu sur l'image'
        if ( $oldImage != $newImage || ! file_exists($cacheImage) ) {
            file_put_contents($cacheImage, file_get_contents($newImage));
        }
    }


    /**
     * Efface l'image du cache
     * 
     * @param String $cacheImage : Nom du fichier complet de cache
     */
    static public function clearImageCache($cacheImage)
    {
        if ( file_exists($cacheImage) ) {
            @unlink($cacheImage);
        }
    }


    /**
     * Retourne le chemin + nom de l'image en cahche
     * 
     * @param String $name : Nom court pour identifier le cache
     * @return String
     */
    static public function getFileImageCache($name)
    {
        return realpath(__DIR__ . '/../../../../') .'/'. sprintf(self::MASK_CACHE, $name);
    }


    /**
     * Retourne l'URI de l'image en cache
     * 
     * @param String $name  : Nom court pour identifier le cache
     * @param String $image : Url de l'image
     * @return String
     */
    static public function getUriImageCache($name, $image)
    {
        return sprintf(self::MASK_CACHE, $name) .'?'. substr(md5($image), 0, 5);;
    }

}
