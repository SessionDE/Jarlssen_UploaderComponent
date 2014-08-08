<?php
/**
 * @author      Tsvetan Stoychev <tsvetan.stoychev@jarlssen.de>
 * @website     http://www.jarlssen.de
 * @license     http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 */

class Jarlssen_UploaderComponent_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{

    public function testModuleDependencies()
    {
        $this->assertModuleDepends('Mage_Core');
    }

    public function testModuleCodePool()
    {
        $this->assertModuleCodePool('local');
    }

    public function testModuleVersion()
    {
        $this->assertModuleVersion('0.0.1');
    }

    public function testModelsAliases()
    {
        $this->assertModelAlias('jarlssen_uploader_component/observer', 'Jarlssen_UploaderComponent_Model_Observer');
    }

    public function testHelpersAliases()
    {
        $this->assertHelperAlias('jarlssen_uploader_component/config', 'Jarlssen_UploaderComponent_Helper_Config');
    }

}