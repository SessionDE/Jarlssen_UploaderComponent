Short info: Jarlssen_UploaderComponent 
=============================================================

This is simple developer's extension that provides file upload functionality for the standard Magento admin forms. To get it to work, the developer just needs to install the module and add some configuration to his own module.

# The configuration is pretty simple and it has three mandatory components:

- Class name of the model, that we want to give file upload functionality to
- The name for the <input type="file"> element
- File upload directory (currently relative to the Magento media folder)

The extension also has the option to validate the uploaded file's extension, but this is not mandatory. If you don't use this configuration, then the module will accept any file type.

The module also works with the standard Magento image form inputs, so once the image is uploaded you can also use the delete checkbox. So far on delete the module just erases the saved file path in the database, but the file still stays on the file system.

# Step by step example how you can make it work:

- Add a dependency in your module:
```
<?xml version="1.0"?>
<config>
    <modules>
        <MyCompany_MyModule>
            <active>true</active>
            <codePool>local</codePool>
            <depends>
                <Jarlssen_UploaderComponent />
            </depends>
        </MyCompany_MyModule>
    </modules>
</config>
```
- Add configuration in your custom module's config.xml. For example:
```
<global>
...
  <jarlssen_uploader_component_config>
      <uploads>
          <MyCompany_MyModule_Model_Item>
              <thumbnail>
                  <upload_dir>my_module/thumbnail</upload_dir>
                  <allowed_extensions>jpg,png,gif</allowed_extensions>
                  <input_name>thumbnail</input_name>
              </thumbnail>
              <pdf>
                  <upload_dir>my_module/pdf</upload_dir>
                  <allowed_extensions>pdf</allowed_extensions>
                  <input_name>pdf_file</input_name>
              </pdf>
          </MyCompany_MyModule_Model_Item>
      </uploads>
  </jarlssen_uploader_component_config>
...
</global>
```
- You should already have the file inputs in your admin form (note that this works with 'type' as 'image' and as 'file')
```
<?php
...
$fieldset->addField('thumbnail', 'image', array(
    'name'      => 'thumbnail',
    'label'     => Mage::helper('my_module')->__('Thumbnail'),
    'title'     => Mage::helper('my_module')->__('Thumbnail'),
    'required'  => true
));

$fieldset->addField('pdf_file', 'file', array(
    'name'      => 'pdf_file',
    'label'     => Mage::helper('my_module')->__('File'),
    'title'     => Mage::helper('my_module')->__('File')
));
```
