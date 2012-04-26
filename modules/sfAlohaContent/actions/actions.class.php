<?php

/**
 * sfAlohaContentActions actions.
 */
class sfAlohaContentActions extends sfActions
{
  /**
   * Save content action
   *
   * @param sfWebRequest $request
   */
  public function executeSave(sfWebRequest $request)
  {
    $id     = $request->getParameter('id');
    $body   = $request->getParameter('body');

    if (!$id || !$body)
    {
      $this->forward404();
    }

    // Get the existing content or create a new one
    $alohaContent = AlohaContentTable::getInstance()->findOneById($id);

    $this->forward404Unless($alohaContent);

    $alohaContent->setBody($body);
    $alohaContent->save();

    return $this->renderText('');
  }

  /**
   * Upload file action
   *
   * @param sfWebRequest $request
   */
  public function executeUploadFile(sfWebRequest $request)
  {
    $imageUploadForm = new sfAlohaImageUploadForm();

    $values = $request->getParameter('sf_aloha_image_upload');
    $files = $request->getFiles('sf_aloha_image_upload');

    $result = array();

    $imageUploadForm->bind($values, $files);

    if ($imageUploadForm->isValid()) {
      $image = $imageUploadForm->getValue('image');
      $imageName = $image->save();

      $imagePath = sprintf(
          '%s/uploads/%s/%s',
          $request->getRelativeUrlRoot(),
          sfConfig::get('app_aloha_image_upload_dir'),
          $imageName
      );

      $result = array('imageUrl' => $imagePath);
    }

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($result));
  }
}
