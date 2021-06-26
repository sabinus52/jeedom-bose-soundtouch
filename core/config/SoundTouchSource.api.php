<?php
/**
 * Librairie de la gestion des sources dans Jeedom
 */

use \Sabinus\SoundTouch\Constants\Source;
use \Sabinus\SoundTouch\Component\ContentItem;


class SoundTouchSourceApi extends JeedomSoundTouchApi
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


    /**
     * Retourne les données d'une préselection
     * 
     * @param Integer $num : Numéro de la préselection
     * @return Array
     */
    public function getPresetByNum($num, $refresh = false)
    {
        $presets = $this->getPresets($refresh);
        if ( !isset($presets[$num]) ) return null;
        return array(
            'source' => $presets[$num]->getContentItem()->getSource(),
            'name' => $presets[$num]->getContentItem()->getName(),
            'image' => $this->getImageFromContentItem($presets[$num]->getContentItem()),
        );
    }


    /**
     * Selectionne la source
     * 
     * @param String $source  : Source (BLUETOOTH, PRODUCT, TUNEIN)
     * @param String $account : Compte (TV, HDMI)
     */
    public function selectSourceJeedom($source, $account)
    {
        SoundTouchLog::info('EXECUTE', $this->eqLogic, 'Selectionne la source '.$source.' "'.$this->hostname.'"');
        switch ($source) {
            case Source::BLUETOOTH :
                $response = $this->selectBlueTooth();
                $functionLog = 'selectBlueTooth()';
                break;
            
            case Source::PRODUCT :
                $response = $this->selectLocalSource($source, $account);
                $functionLog = 'selectLocalSource('.$source.', '.$account.')';
                break;
            
            default:
                $response = true;
                $functionLog = 'null()';
                break;
        }
        SoundTouchLog::debug('EXECUTE', $this->eqLogic, $functionLog.' -> '.( ($response !== false) ? 'OK' : 'NOK'));
        if ( $response === false ) SoundTouchLog::debug('EXECUTE', $this->eqLogic, $functionLog.' -> '.$this->getMessageError());
    }


    /**
     * Selectionne la source Bluetooth
     */
    private function selectBlueTooth()
    {
        $source = new ContentItem();
        $source->setSource(Source::BLUETOOTH);
        $this->selectSource($source);
    }


    /**
     * Selectionne une source locale
     * 
     * @param String $source  : Source (BLUETOOTH, PRODUCT)
     * @param String $account : Compte (TV, HDMI)
     */
    private function selectLocalSource($source, $account)
    {
        $content = new ContentItem();
        $content->setSource($source)
            ->setAccount($account);
        $this->selectSource($content);
    }

}