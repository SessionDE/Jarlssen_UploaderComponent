<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Jarlssen_UploaderComponent_Helper_Config extends Mage_Core_Helper_Abstract
{

    const JARLSSEN_UPLOADER_GLOBAL_CONFIG_XML_PATH = 'global/jarlssen_uploader_component_config/uploads';

    /** @var array|bool */
    protected $_config = false;

    public function __construct()
    {
        $config = Mage::getConfig()
            ->getNode(self::JARLSSEN_UPLOADER_GLOBAL_CONFIG_XML_PATH);

        if(!empty($config)) {
            $this->_config = $config->asCanonicalArray();
        }
    }

    /**
     * Checks if we have any global configuration,
     * that can be used for the file upload
     *
     * @return bool
     */
    public function hasConfig()
    {
        return (false !== $this->_config) ? true : false;
    }

    /**
     * Check if we have uploader config for model
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    public function hasConfigForModel($object)
    {
        $className = get_class($object);
        return isset($this->_config[$className]) ? true : false;
    }

    /**
     * Get the model inputs config from the global configuration
     *
     * @param Mage_Core_Model_Abstract $object
     * @return array
     */
    public function getModelInputsConfigArray(Mage_Core_Model_Abstract $object)
    {
        $className = get_class($object);

        $inputsConfig = array();

        if(isset($this->_config[$className])) {
            foreach($this->_config[$className] as $inputConfig) {
                $inputsConfig[] = Mage::getModel('jarlssen_uploader_component/config', $inputConfig);
            }
        }

        return $inputsConfig;
    }

}