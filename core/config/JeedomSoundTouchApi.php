<?php
/**
 * Librairie de base de l'API
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package SoundTouchApi
 */

use \Sabinus\SoundTouch\SoundTouchApi;
use \Sabinus\SoundTouch\Component\ContentItem;


class JeedomSoundTouchApi extends SoundTouchApi
{

    /**
     * Masque de l'emplacement des images de type sources
     */
    const PATH_IMAGE = '/core/template/images/source/%s.png';

    /**
     * Correspondance des images des sources
     */
    static protected $matchImages = [
        'HDMI_1'       => 'HDMI',
        'HDMI_2'       => 'HDMI',
        'HDMI_3'       => 'HDMI',
        'HDMI_4'       => 'HDMI',
        'HDMI_5'       => 'HDMI',
        'HDMI_6'       => 'HDMI',
        'ANALOG_FRONT' => 'ANALOG',
        'ANALOG1'      => 'ANALOG',
        'ANALOG2'      => 'ANALOG',
        'COAX1'        => 'COAX',
        'COAX2'        => 'COAX',
        'OPTICAL1'     => 'OPTICAL',
        'OPTICAL2'     => 'OPTICAL',
    ];


    /**
     * @var BoseSoundTouch
     */
    protected $eqLogic;


    /**
     * Constructeur
     * 
     * @param BoseSoundTouch $eqLogic
     * @param Boolean $init : initialise ou pas le statut de l'enceinte
     */
    public function __construct(BoseSoundTouch $eqLogic, $init = true)
    {
        $this->eqLogic = $eqLogic;
        $host = $this->eqLogic->getConfiguration('hostname');

        parent::__construct($host, true);
        
        if ($init) $this->getNowPlaying();
    }


    /**
     * Retourne l'URL de l'image à partir de l'objet ContentItem
     * 
     * @param ContentItem $item
     * @return String
     */
    protected function getImageFromContentItem(ContentItem $item)
    {
        $image = $item->getImage();

        // SI image non existante alors on prend image prédéfini sur disque
        if ( empty($item->getImage()) ) {

            // Cherche la source pour faire corresponfdre une image possible
            if ( $item->getSource() != 'PRODUCT' ) {
                $source = $item->getSource();
            } elseif ( $item->getAccount() ) {
                $source = $item->getAccount();
            } else {
                $source = $item->getSource();
            }

            // Matche les sources avec les images prédéfinies sur disque
            $source = ( isset(self::$matchImages[$source]) ) ? strtolower(self::$matchImages[$source]) : strtolower($source);
            log::add('BoseSoundTouch', 'debug', "ACTION : source ".$item->getAccount().' --> '.$source." sur l'enceinte");

            // Chemin complet de l'image locale
            $image = realpath(__DIR__ . '/../..').sprintf(self::PATH_IMAGE, $source);
            if ( ! file_exists($image) ) $image = realpath(__DIR__ . '/../..').sprintf(self::PATH_IMAGE, 'aux');
            $image = 'file://' . $image;
        }

        return $image;
    }

}