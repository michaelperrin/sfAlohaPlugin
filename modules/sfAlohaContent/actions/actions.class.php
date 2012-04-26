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
    $error = $_FILES['image']['error'];
    $alohaUploadDir = sfConfig::get('app_aloha_image_upload_dir');

    if ($error == UPLOAD_ERR_OK)
    {
      $fileName = $_FILES['image']['name'];

      $newFilePath = implode(
        DIRECTORY_SEPARATOR,
        array(
          sfConfig::get('sf_upload_dir'),
          $alohaUploadDir,
          $fileName
        )
      );

      move_uploaded_file($_FILES['image']['tmp_name'], $newFilePath);
    }

    $result = array(
      'imageUrl' => sprintf(
        '/uploads/%s/%s',
        $alohaUploadDir,
        $fileName
      )
    );

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($result));
  }
}
