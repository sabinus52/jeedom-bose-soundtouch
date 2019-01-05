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


class BoseSoundTouch extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /**
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
    public static function cron() {

        foreach (self::byType('BoseSoundTouch') as $equipment) {
            if ($equipment->getIsEnable() == 1) {
                $cmd = $equipment->getCmd(null, 'Refresh');
                if ( !is_object($cmd) ) continue;
                $cmd->execCmd();
            }
        }

    }
    


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {

        // Ajoute les infos
        foreach (SoundTouchConfig::getConfigInfos() as $config) {
            $this->addCommandSoundTouch($config);
        }

        // Ajoute les actions
        foreach (SoundTouchConfig::getConfigCmds() as $config) {
            $this->addCommandSoundTouch($config);
        }
        
    }

    public function preUpdate() {

        if ($this->getConfiguration('hostname') == '') {
            throw new Exception(__('Merci de renseigner l\'hôte ou l\'IP de l\'enceinte.',__FILE__));	
        }

    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */
    public function toHtml($_version = 'dashboard') {

        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $_version = jeedom::versionAlias($_version);

        $replace['#INFO_STATE#'] = 'null';
        $state = false;
        foreach ($this->getCmd('info') as $info) {
            $cache = $info->getCache();
            $replaceInfo = array(
                '#id#'          => $info->getId(),
                '#version#'     => $_version,
                '#name#'        => $info->getName(),
            );
            switch ($info->getLogicalId()) {
                case SoundTouchConfig::SOURCE:
                    //if ($cache['value'] == 'SHOW_DEFAULT_IMAGE')
                    
                    break;
                
                case SoundTouchConfig::PLAYING:
                    $state = strtolower($cache['value']);
                    $response = $info->getConfiguration('response');
                    if ( $response['source.image'] ) {
                        $replaceInfo['#value#'] = $response['source.image'];
                    } else {
                        $replaceInfo['#value#'] = 'plugins/BoseSoundTouch/images/widget/'.$response['source.type'].'.png';
                    }
                    $replaceInfo['#valueDate#'] = strtolower($cache['valueDate']);
                    $replaceInfo['#valueName#'] = strtoupper($response['source.name']);
                    $replace['#CMD_INFO_PLAYING#'] = template_replace($replaceInfo, getTemplate('core', $_version, 'cmd.info.playing','BoseSoundTouch'));
                    break;
                
                default:
                    break;
            }
        }

        foreach ($this->getCmd('action') as $command) {
            $display = $command->getConfiguration('display');
            $replaceCommand = array(
                '#id#'          => $command->getId(),
                '#version#'     => $_version,
                '#name#'        => $command->getName(),
                '#icon#'        => $display['icon'],
                '#icon.width#'  => $display['icon.width'],
                '#icon.height#' => $display['icon.height'],
                '#div.width#'   => $display['div.width'],
                '#div.height#'  => $display['div.height'],
                '#div.padding#' => (($display['div.height'] - $display['icon.height']) / 2),
            );

            if ($command->getLogicalId() == SoundTouchConfig::POWER) {
                $replaceCommand['#value#'] = (($state) ? 'on' : 'off');
                $replace['#CMD_'.$command->getLogicalId().'#'] = template_replace($replaceCommand, getTemplate('core', $_version, 'cmd.action.power','BoseSoundTouch'));
            } else {
                $replace['#CMD_'.$command->getLogicalId().'#'] = template_replace($replaceCommand, getTemplate('core', $_version, 'cmd.action.default','BoseSoundTouch'));
            }
        }

        return template_replace($replace, getTemplate('core', $_version, 'eqLogic','BoseSoundTouch'));

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
        // Paramètre de l'adresse de l'enceinte
        $hostname = $this->getConfiguration('hostname');
        $command = new SoundTouchCommand($hostname);
        log::add('BoseSoundTouch', 'debug', '=== REFRESH ==================================================');
        log::add('BoseSoundTouch', 'debug', "Rafraichissement des données depuis '$hostname'");

        // Récupération des différentes valeur
        $result = $command->getStatePower();
        log::add('BoseSoundTouch', 'debug', 'Response '.SoundTouchConfig::PLAYING.' = '.$result);
        $this->checkAndUpdateCmd(SoundTouchConfig::PLAYING, $result);
        $result = $command->getTypeSource();
        log::add('BoseSoundTouch', 'debug', 'Response '.SoundTouchConfig::SOURCE.' = '.$result);
        $this->checkAndUpdateCmd(SoundTouchConfig::SOURCE, $result);
        $result = $command->getVolume();
        log::add('BoseSoundTouch', 'debug', 'Response '.SoundTouchConfig::VOLUME.' = '.$result);
        $this->checkAndUpdateCmd(SoundTouchConfig::VOLUME, $result);

        $info = $this->getCmd(null, SoundTouchConfig::PLAYING);
        $info->setConfiguration('response', $command->getNowPlaying());
        $info->save();


        log::add('BoseSoundTouch', 'debug', '--------------------------------------------------------------');
        BoseSoundTouch::refreshWidget();
    }


    /**
     * Ajout des commandes à Jeedom
     * 
     * @param Array $config : Configuration de la commande
     */
    public function addCommandSoundTouch(Array $config)
    {
        $cmdSoundTouch = $this->getCmd(null, $config['logicalId']);
        if ( !is_object($cmdSoundTouch) ) {
            $cmdSoundTouch = new BoseSoundTouchCmd();
        }
        $cmdSoundTouch->setName(__($config['name'], __FILE__));
        $cmdSoundTouch->setLogicalId( $config['logicalId'] );
        $cmdSoundTouch->setEqLogic_id( $this->getId() );
        $cmdSoundTouch->setType( $config['type'] );
        $cmdSoundTouch->setSubType( $config['subType'] );
        $cmdSoundTouch->setOrder( $config['order'] );
        if (isset($config['codekey'])) $cmdSoundTouch->setConfiguration( 'codekey', $config['codekey'] );
        if (isset($config['display'])) $cmdSoundTouch->setConfiguration( 'display', $config['display'] );
        if (isset($config['icon'])) $cmdSoundTouch->setDisplay( 'icon', '<img src="plugins/BoseSoundTouch/images/'.$config['icon'].'.png" style="width:20px;height:20px;">' ); //<i class="fa '.$config['icon'].'"></i>
        if (isset($config['forceReturnLineAfter'])) $cmdSoundTouch->setDisplay( 'forceReturnLineAfter', $config['forceReturnLineAfter'] );
        //$cmdSoundTouch->setDisplay( 'generic_type', $config['generic_type'] ); // ???
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

        $soundTouch = $this->getEqLogic();

        // Paramètre de l'adresse de l'enceinte
        $hostname = $soundTouch->getConfiguration('hostname');
        $idCommand = $this->getLogicalId();

        if ($idCommand == 'REFRESH') {
            $soundTouch->updateInfos();
        } else {
            $codeKey = $this->getConfiguration('codekey');
            if ( $codeKey != '' ) {
                log::add('BoseSoundTouch', 'debug', "ACTION : $idCommand sur l'enceinte '$hostname' - Touche $codeKey");
                $command = new SoundTouchCommand($hostname);
                $response = $command->sendCommand($idCommand);
                log::add('BoseSoundTouch', 'debug', "ACTION : $idCommand -> ".( ($response) ? 'OK' : 'NOK'));
            } else {
                log::add('BoseSoundTouch', 'debug', "ACTION : $idCommand sur l'enceinte '$hostname' - Touche NULL");
            }
            
            $soundTouch->updateInfos();
        }

        return;

        
    }

    /*     * **********************Getteur Setteur*************************** */
}


