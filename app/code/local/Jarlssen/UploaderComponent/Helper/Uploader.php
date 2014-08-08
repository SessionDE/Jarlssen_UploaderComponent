<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Jarlssen_UploaderComponent_Helper_Uploader extends Mage_Core_Helper_Abstract
{

    /**
     * Processes the file upload|delete process
     *
     * @param Mage_Core_Model_Abstract $model
     */
    public function processFiles(Mage_Core_Model_Abstract $model)
    {
        $configArray = $this->_getConfigHelper()->getModelInputsConfigArray($model);

        $postData = $this->_getRequest()->getPost();

        /** @var Jarlssen_UploaderComponent_Model_Config $config */
        foreach($configArray as $config) {
            $inputName = $config->getInputName();

            if (isset($_FILES[$inputName]['name']) && $_FILES[$inputName]['name'] != '') {
                $this->processFile($model, $config);
            } else {
                if(isset($postData[$inputName]['delete']) && $postData[$inputName]['delete'] == 1) {
                    $model->setData($inputName, '');
                } else {
                    if(isset($postData[$inputName])) {
                        if(isset($postData[$inputName]['value'])) {
                            $model->setData($inputName, $postData[$inputName]['value']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Uploads single file and set the relative file path in the model
     *
     * @param Mage_Core_Model_Abstract $object
     * @param Jarlssen_UploaderComponent_Model_Config $config
     *
     * @throws Exception
     */
    public function processFile(Mage_Core_Model_Abstract $object, Jarlssen_UploaderComponent_Model_Config $config)
    {
        $inputName = $config->getInputName();
        $uploadPath = $config->getAbsoluteUploadPath();

        try {
            if($result = $this->_upload($config, $uploadPath, $_FILES[$inputName]['name'])) {
                $fileName = $result['file'];
                $relativeFilePath = $config->getRelativeUploadPath() . DS . $fileName;
                $object->setData($inputName, $relativeFilePath);
            }
        } catch(Exception $e) {
            $object->setData($inputName, $object->getOrigData($inputName));
            Mage::logException($e);
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check if the object can be used for the uploader
     * Basically checks some config nodes
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    public function canUpload(Mage_Core_Model_Abstract $object)
    {
        if($this->_getConfigHelper()->hasConfig() && $this->_getConfigHelper()->hasConfigForModel($object)) {
            return true;
        }

        return false;
    }


    /**
     * Wrapper of the actual upload function
     * Also makes my code more testable
     *
     * @param Jarlssen_UploaderComponent_Model_Config $config
     * @param string $uploadPath
     * @param string $newFileName
     *
     * @return bool
     */
    protected  function _upload($config, $uploadPath, $newFileName)
    {
        $varienUploader = $this->_spawnUploader($config);

        return $varienUploader->save($uploadPath, $newFileName);
    }

    /**
     * @return Jarlssen_UploaderComponent_Helper_Config
     */
    protected function _getConfigHelper()
    {
        return Mage::helper('jarlssen_uploader_component/config');
    }

    /**
     * Creates Varien_File_Uploader uploader for me
     * and makes my code more testable
     *
     * @param Jarlssen_UploaderComponent_Model_Config $config
     * @return Varien_File_Uploader
     */
    protected function _spawnUploader(Jarlssen_UploaderComponent_Model_Config $config)
    {
        $varienUploader = new Varien_File_Uploader($config->getInputName());

        $varienUploader->setAllowRenameFiles(true);
        $varienUploader->setFilesDispersion(false);
        $varienUploader->setAllowedExtensions($config->getAllowedExtensions());

        return $varienUploader;
    }

}