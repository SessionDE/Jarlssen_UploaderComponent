<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Jarlssen_UploaderComponent_Model_Config
{

    protected $_inputConfig;

    /**
     * @param array $inputConfig
     * @return Jarlssen_UploaderComponent_Model_Config
     */
    public function __construct($inputConfig)
    {
        $this->_inputConfig = $inputConfig;
    }

    /**
     * Gets the form input name
     *
     * @return string
     */
    public function getInputName()
    {
        return $this->_inputConfig['input_name'];
    }

    /**
     * Prepares the allowed extension configuration
     * for Varien_File_Uploader
     *
     * @return array
     */
    public function getAllowedExtensions()
    {
        if(isset($this->_inputConfig['allowed_extensions']) && '*' != $this->_inputConfig['allowed_extensions']) {
            return explode(',', $this->_inputConfig['allowed_extensions']);
        }

        return array();
    }

    /**
     * Gets the absolute upload path including the media folder
     *
     * @return string
     */
    public function getAbsoluteUploadPath()
    {
        return Mage::getBaseDir('media') . DS . $this->_inputConfig['upload_dir'];
    }

    /**
     * @return string
     */
    public function getRelativeUploadPath()
    {
        return $this->_inputConfig['upload_dir'];
    }

}