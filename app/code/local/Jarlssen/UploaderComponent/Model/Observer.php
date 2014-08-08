<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Jarlssen_UploaderComponent_Model_Observer
{

    /**
     * Event handler of "model_save_before" event
     *
     * @param Varien_Event_Observer $observer
     * @return Jarlssen_UploaderComponent_Model_Observer
     */
    public function processUploads(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getData('object');

        /** @var Jarlssen_UploaderComponent_Helper_Uploader $validatorHelper */
        $uploaderHelper = Mage::helper('jarlssen_uploader_component/uploader');

        if($uploaderHelper->canUpload($object)) {
            $uploaderHelper->processFiles($object);
        }

        return $this;
    }

}