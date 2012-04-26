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
    $this->getWidgetSchema()->setNameFormat('sf_aloha_image_upload[%s]');
  }
}
