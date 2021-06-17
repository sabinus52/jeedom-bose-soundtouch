<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';
require_once __DIR__  . '/../config/SoundTouch.config.php';
//use Sabinus\SoundTouch\JeedomSoundTouchApi;


class BoseSoundTouch extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */


    public static function deamon_info() {
		$return = array();
		$return['log'] = 'BoseSoundTouch';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->halt();
    }

    public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}
    
    public static function pull($_eqLogic_id = null)
    {
        SoundTouchLog::begin('PULL');
		foreach (self::byType('BoseSoundTouch') as $eqLogic) {
            SoundTouchLog::debug('PULL', 'eqLogic ID -> '.$eqLogic->getId().' , enabled = '.$eqLogic->getIsEnable());
			if ($_eqLogic_id != null && $_eqLogic_id != $eqLogic->getId()) {
				continue;
            }
			if ($eqLogic->getIsEnable() == 0) {
				continue;
            }

            $eqLogic->updateInfos();
            if ( intval(date('i')) == 52 && (intval(date('s')) >= 0 && intval(date('s') <= 10) )) {
                $eqLogic->updatePresets();
            }
        }
        SoundTouchLog::end('PULL');
    }

    /**
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
    /*public static function cron() {

        foreach (self::byType('BoseSoundTouch') as $equipment) {
            if ($equipment->getIsEnable() == 1) {
                $equipment->updateInfos();
            }
        }

    }*/
    


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
     */
    public static function cronDaily() {
        self::deamon_start();
    }
    

    /*     * *********************Méthodes d'upgrade************************** */


    /**
     * UPGRADE du plugin
     * 
     * @param Integer $version : Version à upgrader
     */
    public static function upgradeEqLogics($version)
    {
        SoundTouchLog::begin('UPGRADE PLUGIN');
        SoundTouchLog::debug('UPGRADE PLUGIN', 'Version à upgrader '.$version);

        foreach (eqLogic::byType('BoseSoundTouch') as $eqLogic) {

            $versionEqLogic = $eqLogic->getConfiguration('version', 0);
            SoundTouchLog::debug('UPGRADE PLUGIN', 'Version eqLogic = '.$versionEqLogic);

            // Déjà à jour
            if ($versionEqLogic >= $version) continue;

            if ($versionEqLogic <= 1 && $version >= 1) self::_upgradeVersion01($eqLogic);
            if ($versionEqLogic <= 2 && $version >= 2) self::_upgradeVersion02($eqLogic);
            if ($versionEqLogic <= 3 && $version >= 3) self::_upgradeVersion03($eqLogic);

            // Mise à jour de la version
            $eqLogic->setConfiguration('version', $version);
            SoundTouchLog::debug('UPGRADE PLUGIN', 'Version eqLogic = '.$version);
            $eqLogic->save();
        }

        SoundTouchLog::end('UPGRADE PLUGIN');
    }


    /**
     * UPGRADE V1 : Mets par défaut le widget 'remote'
     * 
     * @param BoseSoundTouch $eqLogic
     */
    private static function _upgradeVersion01($eqLogic)
    {
        SoundTouchLog::debug('UPGRADE PLUGIN', 'Version 0 -> 1'); return;
        if ( !$eqLogic->getConfiguration('format') ) {
            $eqLogic->setConfiguration('format', 'player');
        }
    }


    /**
     * UPGRADE V2 : Remplace les logicalID de certaines commandes
     * 
     * @param BoseSoundTouch $eqLogic
     */
    private static function _upgradeVersion02($eqLogic)
    {
        SoundTouchLog::debug('UPGRADE PLUGIN', 'Version 1 -> 2'); return;
        $convertLogicalId = array(
            'TRACK_NEXT' => 'NEXT_TRACK',
            'TRACK_PREV' => 'PREV_TRACK',
        );
        foreach ($eqLogic->getCmd() as $cmd) {
          	$save = false;
           	if ( isset($convertLogicalId[$cmd->getLogicalId()]) ) {
               	$cmd->setLogicalId($convertLogicalId[$cmd->getLogicalId()]);
               	$save = true;
           	}
           	if( $save ) $cmd->save();
		}
    }


    /**
     * UPGRADE V2 : Affecte le logicalID et la zone master de l'eqLogic
     * 
     * @param BoseSoundTouch $eqLogic
     */
    private static function _upgradeVersion03($eqLogic)
    {
        SoundTouchLog::debug('UPGRADE PLUGIN', 'Version 2 -> 3'); return;
        
        // Logical ID
        // $eqLogic->setLogicalID(XXXXXXXXXXXX);
        // Configuration MAC et IP adresse
        // $eqLogic->setConfiguration('zone', [ 'name', 'ip', 'mac' ]);
    }


    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {

        $this->setCategory('multimedia', 1);
    
    }

    public function postSave() {

        $this->updateCommandSoundTouch();
        
    }

    public function preUpdate() {

        if ($this->getConfiguration('hostname') == '') {
            throw new Exception(__('Merci de renseigner l\'hôte ou l\'IP de l\'enceinte.', __FILE__));
        }

        $api = new JeedomSoundTouchApi($this);
        $infos = $api->getInfo();
        if ( empty($infos) ) {
            SoundTouchLog::warning('PRE UPDATE', 'Impossible de joindre l\'enceinte '.$api->getHostname());
            throw new Exception(__('Impossible de joindre l\'enceinte '.$api->getHostname(), __FILE__));
        }

        // Logical ID
        $logicalID = $infos->getDeviceID();
        if ( ! empty($logicalID) ) {
            SoundTouchLog::debug('PRE UPDATE', 'setLogicalID = '.$logicalID);
            $this->setLogicalID($logicalID);
        }

        // Configuration MAC et IP adresse
        $this->setConfiguration('zone', array(
            'name' => $infos->getName(),
            'ip'   => $infos->getNetwork()->getIpAddress(),
            'mac'  => $infos->getNetwork()->getMacAddress(),
        ));
        SoundTouchLog::debug('PRE UPDATE', 'name = '.$infos->getName());
        SoundTouchLog::debug('PRE UPDATE', 'addressIP = '.$infos->getNetwork()->getIpAddress());
        SoundTouchLog::debug('PRE UPDATE', 'addressMAC = '.$infos->getNetwork()->getMacAddress());

    }

    public function postUpdate() {

        $this->updatePresets();

    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */
    public function toHtml($_version = 'dashboard')
    {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $_version = jeedom::versionAlias($_version);

        // Type du widget
        $typeWidget = $this->getConfiguration('format');
        $hostname = $this->getConfiguration('hostname');

        SoundTouchLog::begin('TOHTML');
        SoundTouchLog::debug('TOHTML', 'Affichage du widget au format "'.$typeWidget.'" de l\'enceinte "'.$hostname.'"');

        // Traitement des infos
        foreach ($this->getCmd('info') as $info) {
            $value = $info->execCmd();
            $replace['#'.$info->getLogicalId().'_ID#'] = $info->getId();
            $replace['#'.$info->getLogicalId().'_VALUE#'] = $value;
            switch ($info->getLogicalId()) {
                case SoundTouchConfig::POWERED :
                    $replace['#'.$info->getLogicalId().'_VALUE#'] = ($value) ? 'power-on' : 'power-off';
                    break;
                case SoundTouchConfig::MUTED :
                    $replace['#'.$info->getLogicalId().'_VALUE#'] = ($value) ? 'mute-on' : 'mute';
                    break;
                case SoundTouchConfig::SOURCE :
                    $preview = $info->getConfiguration('preview');
                    $replace['#PREVIEW#'] = $preview['uri'];
                    if ($value == 'UPDATE') {
                        $replace['#PREVIEW#'] = 'plugins/BoseSoundTouch/core/template/images/loader.gif';
                    }
                    break;
                case SoundTouchConfig::TRACK_ARTIST :
                case SoundTouchConfig::TRACK_TITLE :
                    $replace['#'.$info->getLogicalId().'_VALUE#'] = ($value) ? $value : '&nbsp;';
                    break;
                case SoundTouchConfig::SHUFFLE :
                    $replace['#'.$info->getLogicalId().'_VALUE#'] = ($value) ? 'shuffle-on' : 'shuffle-off';
                    break;
                case SoundTouchConfig::REPEAT :
                    $replace['#'.$info->getLogicalId().'_VALUE#'] = 'repeat-'.strtolower($value);
                    break;
                case SoundTouchConfig::STATUS :
                    switch ($value) {
                        case 'PLAY'      : $replace['#PLAY_PAUSE_VALUE#'] = 'pause'; break;
                        case 'PAUSE'     : $replace['#PLAY_PAUSE_VALUE#'] = 'play'; break;
                        case 'STOP'      : $replace['#PLAY_PAUSE_VALUE#'] = 'play'; break;
                        case 'BUFFERING' : $replace['#PREVIEW#'] = 'plugins/BoseSoundTouch/core/template/images/loader.gif';
                        default          : $replace['#PLAY_PAUSE_VALUE#'] = 'play'; break;
                    }
                    break;
            }
            SoundTouchLog::debug('TOHTML', '#'.$info->getLogicalId().'# [ID] = '.$replace['#'.$info->getLogicalId().'_ID#'].', [VALUE] = '.$replace['#'.$info->getLogicalId().'_VALUE#']);
        }


        // Traitement des commandes
        $replace['#SOURCES_LIST#'] = '';
        foreach ($this->getCmd('action') as $command) {
            //$display = $command->getConfiguration('display');
            $replace['#'.$command->getLogicalId().'_ID#'] = $command->getId();

            if ( $presetNumber = $command->getConfiguration('preset') ) {

                // PRESELECTION
                if ( $presetContent = $command->getConfiguration('content') ) {
                    // Présélection avec un contenu
                    $replace['#'.$command->getLogicalId().'_NAME#'] = $presetContent['name'];
                    $replace['#'.$command->getLogicalId().'_ICON#'] = $presetContent['uri'];
                } else {
                    // Préselection vide
                    $replace['#'.$command->getLogicalId().'_NAME#'] = 'Preset '.$presetNumber;
                    $replace['#'.$command->getLogicalId().'_ICON#'] = 'plugins/BoseSoundTouch/core/template/images/keytouch/preset_'.$presetNumber.'.png';
                }
                SoundTouchLog::debug('TOHTML', '#'.$command->getLogicalId().'# [ID] = '.$replace['#'.$command->getLogicalId().'_ID#'].', [NAME] = '.$replace['#'.$command->getLogicalId().'_NAME#'].', [ICON] => '.$replace['#'.$command->getLogicalId().'_ICON#']);

            } elseif ( $contentItem = $command->getConfiguration('contentItem') ) {

                // Liste des sources
                if ( intval(substr($contentItem['account'], -1)) > 0 ) {
                    $image = 'plugins/BoseSoundTouch/core/template/images/source/'.strtolower(substr($contentItem['account'], 0, -1)).'.png';
                } else {
                    $image = 'plugins/BoseSoundTouch/core/template/images/source/'.strtolower($contentItem['account']).'.png';
                }
                if ( ! file_exists(realpath(__DIR__ . '/../../../../').'/'.$image) ) {
                    $image = 'plugins/BoseSoundTouch/core/template/images/source/aux.png';
                }
                $cacheImg = realpath(__DIR__ . '/../../images').'/cache-preview-'.$this->getId().'.png';
                $replace['#SOURCES_LIST#'] .= '<li><img data-cmd_id="'.$command->getId().'" src="'.$image.'" title="'.$contentItem['account'].'" onclick="jeedom.cmd.execute({id: \''.$command->getId().'\'});"></li>';
                SoundTouchLog::debug('TOHTML', '#'.$command->getLogicalId().'# [ID] = '.$replace['#'.$command->getLogicalId().'_ID#'].', [NAME] = '.$contentItem['account'].', [ICON] => '.$image);

            } else {

                // Toutes les autres commandes
                SoundTouchLog::debug('TOHTML', '#'.$command->getLogicalId().'# [ID] = '.$replace['#'.$command->getLogicalId().'_ID#']);

            }
            
        }

        SoundTouchLog::end('TOHTML');
        return template_replace($replace, getTemplate('core', $_version, $typeWidget.'.eqLogic', 'BoseSoundTouch'));
    }
     

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */

    /**
     * Rafraichissement des infos de l'enceinte
     */
    public function updateInfos()
    {
        if ( $this->getIsEnable() == 0 ) return false;
        $update = false;

        $api = new SoundTouchNowPlayingApi($this, true);
        SoundTouchLog::begin('REFRESH');
        SoundTouchLog::info('REFRESH', 'Rafraichissement des données depuis "'.$api->getHostname().'"');

        // Récupération des différentes valeur
        if ($err = $api->getMessageError()) {
            SoundTouchLog::warning('REFRESH', 'Interrogation de l\'enceinte "'.$api->getHostname().'" : '.$err);
            return;
        }
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::POWERED,      $api->isPowered() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::SOURCE,       $api->getCurrentSource() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::VOLUME,       $api->getLevelVolume() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::MUTED,        $api->isMuted() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::STATUS,       $api->getStatePlay() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::SHUFFLE,      $api->isShuffle() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::REPEAT,       $api->getStateRepeat() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::TRACK_ARTIST, $api->getTrackArtist() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::TRACK_TITLE,  $api->getTrackTitle() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::TRACK_ALBUM,  $api->getTrackAlbum() );
        $update |= $this->checkAndUpdateCommand( SoundTouchConfig::TRACK_IMAGE,  $api->getTrackImage() );

        // Image de Preview à stoker dans la commande infos SOURCE
        $sourceInfo = $this->getCmd(null, SoundTouchConfig::SOURCE);
        if ( is_object($sourceInfo) ) {
            $oldPreview = $sourceInfo->getConfiguration('preview');
            $oldImage = ( isset($oldPreview['image']) ) ? $oldPreview['image'] : '';
            $preview = $api->getPreviewArray($oldImage);
            $sourceInfo->setConfiguration('preview', $preview);
            SoundTouchLog::debug('REFRESH', 'Preview Image = '.print_r($preview, true));
            $sourceInfo->save();
        }

        if ($update) {
            BoseSoundTouch::refreshWidget();
            SoundTouchLog::info('REFRESH', 'WIDGET rafraîchit...');
        }
        SoundTouchLog::end('REFRESH');
    }


    /**
     * Met à jour une valeur d'une commande info et retourne si elle a été changé ou pas
     * 
     * @param $cmdLogicalId : ID logique de la commande à modifier
     * @param $value : Valeur à modifier
     * @return Boolean
     */
    private function checkAndUpdateCommand($cmdLogicalId, $value)
    {
		$cmd = $this->getCmd('info', $cmdLogicalId);
		if ( !is_object($cmd) ) return false;

        // Compare si la valeur à changer
		$oldValue = $cmd->execCmd();
		if ( $oldValue !== $cmd->formatValue($value) ) {
			$cmd->event($value);
            SoundTouchLog::infoUpdateCommand($cmdLogicalId, $value, true);
			return true;
		}
        SoundTouchLog::infoUpdateCommand($cmdLogicalId, $value, false);
		return false;
    }


    /**
     * Rafraichissement des présélections
     */
    public function updatePresets()
    {
        // Déclaration de l'API avec l'adresse de l'enceinte
        $api = new SoundTouchSourceApi($this);
        $configs = new SoundTouchConfig($api);

        SoundTouchLog::begin('PRESETS');
        SoundTouchLog::info('PRESETS', 'Rafraichissement des présélections depuis "'.$api->getHostname().'"');

        foreach (cmd::searchConfigurationEqLogic($this->getId(), '"preset"', 'action') as $command) {
            $configs->updatePreset($command);
        }

        SoundTouchLog::end('PRESETS');
        BoseSoundTouch::refreshWidget();
    }


    /**
     * Met à jour les commandes du Plugin
     */
    public function updateCommandSoundTouch()
    {
        // Déclaration de l'API avec l'adresse de l'enceinte
        $api = new SoundTouchSourceApi($this);
        $configs = new SoundTouchConfig($api);

        SoundTouchLog::begin('SAVE CMD');
        SoundTouchLog::info('SAVE CMD', 'Rafraichissement des commandes depuis "'.$api->getHostname().'"');

        foreach ($configs->getListCommands() as $command) {
            $this->addCommand($command);
        }

        SoundTouchLog::end('SAVE CMD');
    }


    /**
     * Met à jour les commandes du Plugin
     */
    public function reCreateCommandSoundTouch()
    {
        // Déclaration de l'API avec l'adresse de l'enceinte
        $api = new SoundTouchSourceApi($this);
        $configs = new SoundTouchConfig($api);

        SoundTouchLog::begin('RECREATE CMD');
        SoundTouchLog::info('RECREATE CMD', 'Rafraichissement des commandes depuis "'.$api->getHostname().'"');

        foreach ($configs->getListCommands() as $command) {
            $this->addCommand($command, true);
        }

        SoundTouchLog::end('RECREATE CMD');
    }


    /**
     * Ajout des commandes à Jeedom
     * 
     * @param Array $config : Configuration de la commande
     * @param Boolean $force : Force la recréation de la commande
     */
    public function addCommand(Array $config, $force = false)
    {
        $cmdSoundTouch = $this->getCmd(null, $config['logicalId']);
        if ( !is_object($cmdSoundTouch) ) {

            $cmdSoundTouch = new SmartLifeCmd();
            $cmdSoundTouch->setName(__($config['name'], __FILE__));
            $cmdSoundTouch->setLogicalId( $config['logicalId'] );
            $cmdSoundTouch->setEqLogic_id( $this->getId() );

            if ( isset($config['display']) ) {
                foreach ($config['display'] as $key => $value) {
                    $cmdSoundTouch->setDisplay($key, $value);
                }
                unset($config['display']);
            }

            // Assigne les paramètres du JSON à chaque fonction de l'eqLogic
            utils::a2o($cmdSoundTouch, $config);
            SoundTouchLog::info('SAVE CMD', 'ADD '.$config['logicalId']);
        }

        if ( $force ) {
            utils::a2o($cmdSoundTouch, $config);
            SoundTouchLog::info('RECREATE CMD', 'ADD '.$config['logicalId']);
        }

        // Ne doit pas être changé
        $cmdSoundTouch->setType( $config['type'] );
        $cmdSoundTouch->setSubType( $config['subType'] );
        if (isset($config['value'])) {
            foreach ($this->getCmd() as $eqLogic_cmd) {
				if ($config['value'] == $eqLogic_cmd->getLogicalId()) {
					$cmdSoundTouch->setValue($eqLogic_cmd->getId());
				}
			}
        }
        SoundTouchLog::debug('SAVE CMD', $config['logicalId'].' : '.print_r($config, true));
        SoundTouchLog::debugCommand('SAVE CMD', $cmdSoundTouch);

        // Sauvegarde
        $cmdSoundTouch->save();
    }

}

class BoseSoundTouchCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {

        $idCommand = $this->getLogicalId();

        if ($idCommand == 'REFRESH') {

            // Rafraichissement des données
            SoundTouchLog::debug('EXECUTE', 'updatePresets() | updateInfos()');
            $this->getEqLogic()->updatePresets();
            $this->getEqLogic()->updateInfos();

        } elseif ( $codeKey = $this->getConfiguration('codekey') ) {

            // Action sur sur touche
            SoundTouchLog::debug('EXECUTE', 'sendCommandJeedom('.$codeKey.')');
            $api = new SoundTouchCommandKeyApi($this->getEqLogic());
            $api->sendCommandJeedom($this);

        } elseif ( $idCommand == 'VOLUME_SET' ) {

            // Ajuste le volume
            SoundTouchLog::debug('EXECUTE', 'setVolumeJeedom('.$_options['slider'].')');
            $api = new SoundTouchCommandKeyApi($this->getEqLogic());
            $api->setVolumeJeedom($_options['slider']);

        } elseif ( $content = $this->getConfiguration('contentItem') ) {

            // Sélectionne une source
            SoundTouchLog::debug('EXECUTE', 'selectSourceJeedom('.$content['source'].', '.$content['account'].')');
            $api = new SoundTouchSourceApi($this->getEqLogic());
            $api->selectSourceJeedom($content['source'], $content['account']);
        
        } else {
            SoundTouchLog::debug('EXECUTE', 'NULL : pas de commande à exécuter');
        }

    }

    /*     * **********************Getteur Setteur*************************** */
}


