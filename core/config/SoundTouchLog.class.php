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
        self::info($action, '=== BEGIN ===================================================');
    }


    /**
     * Fin d'un traitement
     * 
     * @param String $action
     */
    static public function end($action)
    {
        self::info($action, '=== END =====================================================');
    }


    /**
     * Log d'une mise à jour d'une données d'une commande
     * 
     * @param String  $command
     * @param Mixed   $value
     * @param Integer $result
     */
    static public function infoUpdateCommand($command, $value, $result)
    {
        self::info('REFRESH', 'Update '.$command.' = '.$value.(($result) ? ' (UPD=true)' : ''));
    }


    /**
     * Log des infos d'une commande
     * 
     * @param String $action
     * @param BoseSoundTouchCmd $command
     */
    static public function debugCommand($action, $command)
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
        self::debug($action, 'CMD = '.print_r($result, true));
    }


    /**
     * DEBUG
     * 
     * @param String $action
     * @param String $message
     */
    static public function debug($action, $message)
    {
        self::write('debug', $action, $message);
    }


    /**
     * INFO
     * 
     * @param String $action
     * @param String $message
     */
    static public function info($action, $message)
    {
        self::write('info', $action, $message);
    }


    /**
     * WARNING
     * 
     * @param String $action
     * @param String $message
     */
    static public function warning($action, $message)
    {
        self::write('warning', $action, $message);
    }


    /**
     * ERROR
     * 
     * @param String $action
     * @param String $message
     */
    static public function error($action, $message)
    {
        self::write('error', $action, $message);
    }


    /**
     * Ecrire le log
     * 
     * @param String $level
     * @param String $action
     * @param String $message
     */
    static private function write($level, $action, $message)
    {
        log::add('BoseSoundTouch', $level, $action.' : '.$message);
    }

}