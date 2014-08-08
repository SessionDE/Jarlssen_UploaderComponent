<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Jarlssen_UploaderComponent_Test_Helper_Uploader extends EcomDev_PHPUnit_Test_Case
{

    /** @var Jarlssen_UploaderComponent_Helper_Uploader */
    protected $_model;

    public function setUp()
    {
        $_FILES = array(
            'thumbnail' => array(
                'name' => 'gnu-test_file.png',
                'type' => 'image/png',
                'size' => 4384,
                'tmp_name' => __DIR__ . '/Uploader/_files/gnu-test_file.png',
                'error' => 0
            )
        );

        $modelMock = $this->getHelperMock('jarlssen_uploader_component/uploader', array('_upload', '_getConfigHelper'));

        $this->_model = $modelMock;
    }

    /**
     * @param string $dataSet
     * @param array $data
     *
     * @dataProvider dataProvider
     */
    public function testProcessFile($dataSet, $data)
    {
        $model = new Jarlssen_UploaderComponent_Test_Objects_Model_Item();
        $config = new Jarlssen_UploaderComponent_Model_Config($data);

        $this->_model->expects($this->any())
            ->method('_upload')
            ->will($this->returnValue(array('file' => 'gnu-test_file.png')));

        $expected = $this->expected($dataSet);

        $this->_model->processFile($model, $config);

        $this->assertEquals($expected->getThumbnail(), $model->getData('thumbnail'));
    }

    /**
     * @param string $dataSet
     * @param array $data
     *
     * @dataProvider dataProvider
     */
    public function testProcessSingleFile($dataSet, $data)
    {
        $model = new Jarlssen_UploaderComponent_Test_Objects_Model_Item();

        $configObject = new Jarlssen_UploaderComponent_Model_Config($data);

        $this->_model->expects($this->any())
            ->method('_upload')
            ->will($this->returnValue(array('file' => 'gnu-test_file.png')));

        $helperMock = $this->getHelperMock('jarlssen_uploader_component/config', array('getModelInputsConfigArray'));

        $helperMock->expects($this->any())
            ->method('getModelInputsConfigArray')
            ->will($this->returnValue(array($configObject)));

        $this->_model->expects($this->any())
            ->method('_getConfigHelper')
            ->will($this->returnValue($helperMock));

        $this->_model->processFiles($model);

        $expected = $this->expected($dataSet);

        $this->assertEquals($expected->getThumbnail(), $model->getData('thumbnail'));
    }

    /**
     * @param $dataSet
     * @param $thumbnailData
     * @param $pdfData
     *
     * @dataProvider dataProvider
     */
    public function test_PostTwoFileInputs_WithoutChosenFile_AlreadyUploadedFiles_FirstImageInput_SecondFileInput($dataSet, $thumbnailData, $pdfData)
    {
        $_FILES = array(
            'thumbnail' => array(
                'name' => null,
                'type' => null,
                'size' => 0,
                'tmp_name' => null,
                'error' => null
            ),
            'file' => array(
                'name' => null,
                'type' => null,
                'tmp_name' => null,
                'error' => 4,
                'size' => 0
            )
        );

        $_POST['thumbnail'] = array('value' => 'test_upload_directory/thumbnail/gnu-test_file.png');

        $model = new Jarlssen_UploaderComponent_Test_Objects_Model_Item();

        $model->setData('thumbnail', $thumbnailData['db_value']);
        $model->setData('file', $pdfData['db_value']);

        $thumbnailConfigObject = new Jarlssen_UploaderComponent_Model_Config($thumbnailData);
        $pdfFileConfigObject = new Jarlssen_UploaderComponent_Model_Config($pdfData);

        $helperMock = $this->getHelperMock('jarlssen_uploader_component/config', array('getModelInputsConfigArray'));

        $helperMock->expects($this->any())
            ->method('getModelInputsConfigArray')
            ->will($this->returnValue(array($thumbnailConfigObject, $pdfFileConfigObject)));

        $this->_model->expects($this->any())
            ->method('_getConfigHelper')
            ->will($this->returnValue($helperMock));

        $this->_model->processFiles($model);

        $expected = $this->expected($dataSet);

        $this->assertEquals($expected->getThumbnail(), $model->getData('thumbnail'));
        $this->assertEquals($expected->getPdf(), $model->getData('file'));
    }

    /**
     * @param $dataSet
     * @param $thumbnailData
     * @param $pdfData
     *
     * @dataProvider dataProvider
     */
    public function test_PostTwoFileInputs_FirstHasChosenFileWithNotValidExtension_AlreadyUploadedFiles_FirstImageInput_SecondFileInput($dataSet, $thumbnailData, $pdfData)
    {
        $_FILES = array(
            'thumbnail' => array(
                'name' => 'gnu-test_file.odp',
                'type' => 'odp',
                'size' => 4384,
                'tmp_name' => __DIR__ . '/Uploader/_files/gnu-test_file.odp',
                'error' => 0
            ),
            'file' => array(
                'name',
                'type',
                'tmp_name',
                'error' => 4,
                'size' => 0
            )
        );

        $_POST['thumbnail'] = array('value' => 'test_upload_directory/thumbnail/gnu-test_file.png');

        $model = new Jarlssen_UploaderComponent_Test_Objects_Model_Item();

        $model->setData('thumbnail', $thumbnailData['db_value']);
        $model->setOrigData('thumbnail', $thumbnailData['db_value']);

        $model->setData('pdf_file', $pdfData['db_value']);
        $model->setOrigData('pdf_file', $pdfData['db_value']);

        $thumbnailConfigObject = new Jarlssen_UploaderComponent_Model_Config($thumbnailData);
        $pdfFileConfigObject = new Jarlssen_UploaderComponent_Model_Config($pdfData);



        $helperMock = $this->getHelperMock('jarlssen_uploader_component/config', array('getModelInputsConfigArray'));

        $helperMock->expects($this->any())
            ->method('getModelInputsConfigArray')
            ->will($this->returnValue(array($thumbnailConfigObject, $pdfFileConfigObject)));

        $this->_model->expects($this->any())
            ->method('_getConfigHelper')
            ->will($this->returnValue($helperMock));

        $this->_model->expects($this->any())
            ->method('_upload')
            ->will($this->throwException(new Exception('Disallowed file type.')));

        try {
            $this->_model->processFiles($model);
            $this->fail("Expected exception not thrown");
        } catch(Exception $e) {
            $expected = $this->expected($dataSet);
            $this->assertEquals($expected->getThumbnail(), $model->getData('thumbnail'));
            $this->assertEquals($expected->getPdfFile(), $model->getData('pdf_file'));
        }

    }

    protected function tearDown()
    {
        unset($_FILES);
        unset($this->_model);
    }



}