<?php
/**
 * Form handling client information
 */
class sfAlohaImageUploadForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $this->setWidget('image', new sfWidgetFormInputFile());

    $this->setValidator(
      'image',
      new sfValidatorFile(
        array(
          'required'   => true,
          'path'       => sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . sfConfig::get('app_aloha_image_upload_dir'),
          'mime_types' => 'web_images',
        )
      )
    );

    $this->getWidgetSchema()->setNameFormat('sf_aloha_image_upload[%s]');
  }
}
