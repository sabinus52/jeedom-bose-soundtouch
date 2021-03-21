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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function BoseSoundTouch_install() {

    $cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('BoseSoundTouch');
        $cron->setFunction('pull');
        $cron->setEnable(1);
        $cron->setDeamon(1);
        $cron->setDeamonSleepTime(7);
        $cron->setSchedule('* * * * *');
        $cron->setTimeout(1440);
        $cron->save();
    }

}

function BoseSoundTouch_update() {
    
    $cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
    if (!is_object($cron)) {
        $cron = new cron();
    }
    $cron->setClass('BoseSoundTouch');
    $cron->setFunction('pull');
    $cron->setEnable(1);
    $cron->setDeamon(1);
    $cron->setDeamonSleepTime(7);
    $cron->setTimeout(1440);
    $cron->setSchedule('* * * * *');
    $cron->save();
    $cron->stop();

    $convertLogicalId = array(
        'TRACK_NEXT' => 'NEXT_TRACK',
        'TRACK_PREV' => 'PREV_TRACK',
    );
    foreach (eqLogic::byType('BoseSoundTouch') as $equipment) {
		// Mets par défaut le widget 'remote'
        if ( !$equipment->getConfiguration('format') ) {
            $equipment->setConfiguration('format', 'remote');
        }
		// Remplace les logicalID de certaines commandes
        foreach ($equipment->getCmd() as $cmd) {
			try {
            	$save = false;
            	if ( isset($convertLogicalId[$cmd->getLogicalId()]) ) {
                	$cmd->setLogicalId($convertLogicalId[$cmd->getLogicalId()]);
                	$save = true;
            	}
            	if( $save ) $cmd->save();
            } catch (\Exception $e) { }
		}
        $equipment->save();
    }

}


function BoseSoundTouch_remove() {
    
    $cron = cron::byClassAndFunction('BoseSoundTouch', 'pull');
    if (is_object($cron)) {
        $cron->remove();
    }

}

?>
