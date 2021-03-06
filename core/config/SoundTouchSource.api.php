<?php
/**
 * Librairie de la gestion des sources dans Jeedom
 */

//namespace Sabinus\SoundTouch;

use \Sabinus\SoundTouch\SoundTouchApi;


class SoundTouchSourceApi extends SoundTouchApi
{

    /**
     * Retourne les sources de l'enceinte valable localement (HDMI, TV, BLUETOOTH)
     */
    public function getSourceLocal($refresh = false)
    {
        $result = array();
        foreach ($this->getSources() as $source) {
            if ( ! $source->getIsLocal() || $source->getSource() == 'QPLAY' ) continue;
            $result[] = $source;
        }

        return $result;
    }

}