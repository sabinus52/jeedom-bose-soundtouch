<?php
/**
 * Librairie de la gestion des zones (MultiRoom) dans Jeedom
 */

use \Sabinus\SoundTouch\Component\Zone;
use \Sabinus\SoundTouch\Component\ZoneSlave;


class SoundTouchZoneApi extends JeedomSoundTouchApi
{

    /**
     * Zone complète du MultiRoom
     * 
     * @var Zone
     */
    private $zone;


    /**
     * Affecte la zone complète (ajout ou suppression)
     * 
     * @param String $action : ADD ou SUB
     * @param String $addressIP : Adresse IP de la zone à ajouter ou supprimer
     * @param String $addressMAC : Adresse MAC de la zone à ajouter ou supprimer
     */
    public function setZoneJeedom($action, $addressIP, $addressMAC)
    {
        $master = $this->isZoneMaster();
        SoundTouchLog::debug('EXECUTE', 'setZoneJeedom -> isMaster '.( ($master !== false) ? 'OK' : 'NOK'));
        if ( $master === false ) {
            // On crée la zone maitre si aucune zone existe
            $this->createZoneMaster();
            $this->setZone($this->zone);
        }

        // Crée la zonne esclave
        $slave = $this->getZoneSlave($addressIP, $addressMAC);
        
        // Ajoute ou supprime la zone
        switch ($action) {
            case 'ADD':
                $result = $this->addZoneSlave($slave);
                SoundTouchLog::debug('EXECUTE', 'setZoneJeedom -> addZoneSlave '.print_r($slave, true).' -> '.( ($result !== false) ? 'OK' : 'NOK'));
                break;
            
            case 'SUB':
                $result = $this->removeZoneSlave($slave);
                SoundTouchLog::debug('EXECUTE', 'setZoneJeedom -> removeZoneSlave '.print_r($slave, true).' -> '.( ($result !== false) ? 'OK' : 'NOK'));
                break;
        }
    }


    /**
     * Retourne si une zone master a été créée
     * 
     * @return Boolean
     */
    private function isZoneMaster()
    {
        if ( empty($zone) ) $this->zone = $this->getZone();
        $master = $this->zone->getMaster();
        return ( empty($master) ) ? false : true;
    }


    /**
     * Créer la zone maitre
     */
    private function createZoneMaster()
    {
        $master = $this->eqLogic->getConfiguration('zone');

        $this->zone = new Zone();
        $this->zone->setMaster($master['mac'])->setSender($master['ip']);
        $slave = new ZoneSlave();
        $slave->setMacAddress($master['mac'])->setIpAddress($master['ip']);
        $this->zone->addSlave($slave);

        SoundTouchLog::debug('EXECUTE', 'setZoneJeedom -> createZoneMaster '.print_r($this->zone, true));
    }


    /**
     * Retourne une zone esclave
     * 
     * @param String $addressIP : Adresse IP de la zone à ajouter ou supprimer
     * @param String $addressMAC : Adresse MAC de la zone à ajouter ou supprimer
     * @return ZoneSlave
     */
    private function getZoneSlave($addressIP, $addressMAC)
    {
        $slave = new ZoneSlave();
        $slave->setMacAddress($addressMAC)->setIpAddress($addressIP);

        return $slave;
    }


    /**
     * Retourne la configuration pour la commande info "MultiRoom"
     */
    public function getConfigurationCommandInfo()
    {
        $result = array('master' => null, 'slaves' => array());

        if ( empty($zone) ) $this->zone = $this->getZone();
        $master = $this->zone->getMaster();
        $result['master'] = ( empty($master) ) ? null : $master;
        foreach ($this->zone->getSlaves() as $slave) {
            $result['slaves'][] = $slave->getMacAddress();
        }

        return $result;
    }
}