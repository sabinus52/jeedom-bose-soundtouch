<?php
/**
 * Librairie de la gestion des sources dans Jeedom
 */

use \Sabinus\SoundTouch\Constants\Source;
use \Sabinus\SoundTouch\Component\ContentItem;


class SoundTouchSourceApi extends JeedomSoundTouchApi
{

    /**
     * Masque de l'emplacement des images de type sources
     */
    const PATH_IMAGE = '/core/template/images/source/%s.png';


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
            'image' => $this->getImage($presets[$num]->getContentItem()),
        );
    }


    /**
     * Retourne l'image en fonction de la source
     * 
     * @param ContentItem $item : Contenu de la source
     * @return String
     */
    private function getImage(ContentItem $item)
    {
        $image = $item->getImage();

        if ( empty($item->getImage()) ) {
            $image = realpath(__DIR__ . '/../..').sprintf(self::PATH_IMAGE, strtolower($item->getSource()));
            if ( ! file_exists($image) ) $image = realpath(__DIR__ . '/../..').sprintf(self::PATH_IMAGE, 'invalid_source');
            $image = 'file://' . $image;
        }

        return $image;
    }


    /**
     * Selectionne la source
     * 
     * @param String $source  : Source (BLUETOOTH, PRODUCT, TUNEIN)
     * @param String $account : Compte (TV, HDMI)
     */
    public function selectSourceJeedom($source, $account)
    {
        log::add('BoseSoundTouch', 'debug', "ACTION : ".$source." sur l'enceinte '".$this->hostname."' - Touche $account");
        switch ($source) {
            case Source::BLUETOOTH :
                $response = $this->selectBlueTooth();
                break;
            
            case Source::PRODUCT :
                $response = $this->selectLocalSource($source, $account);
                break;
            
            default:
                
                break;
        }
        log::add('BoseSoundTouch', 'debug', "ACTION : ".$source." -> ".( ($response !== false) ? 'OK' : 'NOK'));
        if ( $response === false ) log::add('BoseSoundTouch', 'debug', "ACTION : ".$source." -> ".$this->getMessageError() );
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