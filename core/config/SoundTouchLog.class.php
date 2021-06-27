<?php
/**
 * Classe de fonctions pour l'affichage des logs
 */



class SoundTouchLog
{

    /**
     * Début d'un traitement
     * 
     * @param String $action
     */
    static public function begin($action)
    {
        self::info($action, null, '=== BEGIN ===================================================');
    }


    /**
     * Fin d'un traitement
     * 
     * @param String $action
     */
    static public function end($action)
    {
        self::info($action, null, '=== END =====================================================');
    }


    /**
     * Log d'une mise à jour d'une données d'une commande
     * 
     * @param String  $command
     * @param Mixed   $value
     * @param Integer $result
     */
    static public function infoUpdateCommand($eqLogic, $command, $value, $result)
    {
        self::info('REFRESH', $eqLogic, 'Update '.$command.' = '.$value.(($result) ? ' (UPD=true)' : ''));
    }


    /**
     * Log des infos d'une commande
     * 
     * @param String $action
     * @param BoseSoundTouchCmd $command
     */
    static public function debugCommand($action, $eqLogic, $command)
    {
        $result = [
            'logicalId' => $command->getLogicalId(),
            'generic_type' => $command->getGeneric_type(),
            'name' => $command->getName(),
            'type' => $command->getType(),
            'subType' => $command->getSubType(),
            'unite' => $command->getUnite(),
            'configuration' => $command->getConfiguration(),
            'template' => $command->getTemplate(),
            'display' => $command->getDisplay(),
            'value' => $command->getValue(),
            'isVisible' => $command->getIsVisible(),
        ];
        self::debug($action, $eqLogic, 'CMD = '.print_r($result, true));
    }


    /**
     * DEBUG
     * 
     * @param String $action
     * @param String $message
     */
    static public function debug($action, $eqLogic, $message)
    {
        self::write('debug', $action, $eqLogic, $message);
    }


    /**
     * INFO
     * 
     * @param String $action
     * @param String $message
     */
    static public function info($action, $eqLogic, $message)
    {
        self::write('info', $action, $eqLogic, $message);
    }


    /**
     * WARNING
     * 
     * @param String $action
     * @param String $message
     */
    static public function warning($action, $eqLogic, $message)
    {
        self::write('warning', $action, $eqLogic, $message);
    }


    /**
     * ERROR
     * 
     * @param String $action
     * @param String $message
     */
    static public function error($action, $eqLogic, $message)
    {
        self::write('error', $action, $eqLogic, $message);
    }


    /**
     * Ecrire le log
     * 
     * @param String $level
     * @param String $action
     * @param String $message
     */
    static private function write($level, $action, $eqLogic, $message)
    {
        log::add('BoseSoundTouch', $level, $action.
        (($eqLogic) ? ' '.$eqLogic->getLogicalId() : '').
        ' : '.$message);
    }

}