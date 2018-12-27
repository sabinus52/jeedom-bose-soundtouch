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
require_once __DIR__  . '/../../3rparty/SoundTouchCommand.class.php';


class BoseSoundTouch extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


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

        $state = $this->getCmd(null, 'PLAYING');
        if (!is_object($state)) {
            $state = new BoseSoundTouchCmd();
            $state->setName(__('etat', __FILE__));
        }
        $state->setLogicalId('PLAYING');
        $state->setEqLogic_id($this->getId());
        $state->setType('info');
        $state->setSubType('binary');
        $state->save();

        $state = $this->getCmd(null, 'SOURCE');
        if (!is_object($state)) {
            $state = new BoseSoundTouchCmd();
            $state->setName(__('source', __FILE__));
        }
        $state->setLogicalId('SOURCE');
        $state->setEqLogic_id($this->getId());
        $state->setType('info');
        $state->setSubType('string');
        $state->save();

        $state = $this->getCmd(null, 'VOLUME');
        if (!is_object($state)) {
            $state = new BoseSoundTouchCmd();
            $state->setName(__('volume', __FILE__));
        }
        $state->setLogicalId('VOLUME');
        $state->setEqLogic_id($this->getId());
        $state->setType('info');
        $state->setSubType('numeric');
        $state->save();

        $state = $this->getCmd(null, 'BASS');
        if (!is_object($state)) {
            $state = new BoseSoundTouchCmd();
            $state->setName(__('bass', __FILE__));
        }
        $state->setLogicalId('BASS');
        $state->setEqLogic_id($this->getId());
        $state->setType('info');
        $state->setSubType('numeric');
        $state->save();

        $power = $this->getCmd(null, 'refresh');
        if ( !is_object($power) ) {
            $power = new BoseSoundTouchCmd();
            $power->setName(__('refresh', __FILE__));
        }
        $power->setEqLogic_id($this->getId());
        $power->setLogicalId('refresh');
        $power->setType('action');
        $power->setSubType('other');
        $power->save();

    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

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

        // Récupération des différentes valeur
        $result = $command->getStatePower();
        log::add('BoseSoundTouch', 'debug', "Response PLAYING = $result");
        $this->checkAndUpdateCmd('PLAYING', $result);
        $result = $command->getTypeSource();
        log::add('BoseSoundTouch', 'debug', "Response SOURCE = $result");
        $this->checkAndUpdateCmd('SOURCE', $result);
        $result = $command->getVolume();
        log::add('BoseSoundTouch', 'debug', "Response VOLUME = $result");
        $this->checkAndUpdateCmd('VOLUME', $result);
        $result = $command->getLevelBass();
        log::add('BoseSoundTouch', 'debug', "Response BASS = $result");
        $this->checkAndUpdateCmd('BASS', $result);
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

        log::add('BoseSoundTouch', 'debug', "Exécution de la commande");
        log::add('BoseSoundTouch', 'debug', "HOST = $hostname");
        log::add('BoseSoundTouch', 'debug', "Commande = $idCommand");

        if ($idCommand == 'refresh') {
            $soundTouch->updateInfos();
        }

        return;

        
    }

    /*     * **********************Getteur Setteur*************************** */
}


