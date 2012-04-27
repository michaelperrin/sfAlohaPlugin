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

      $fileName = $this->_generateImageName(
          $imageUploadForm->getValidator('image')->getOption('path'),
          $image->getOriginalName()
      );

      $image->save($fileName);

      $imageUrl = sprintf(
        '%s/uploads/%s/%s',
        $request->getRelativeUrlRoot(),
        sfConfig::get('app_aloha_image_upload_dir'),
        $fileName
      );

      $result = array('imageUrl' => $imageUrl);
    }

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($result));
  }

  /**
   * Generates image name
   *
   * @param string $path
   * @param string $originalName
   * @return string
   */
  protected function _generateImageName($path, $originalName)
  {
    if (!file_exists($path . DIRECTORY_SEPARATOR . $originalName))
    {
      // No overwrite risk: keep the original image name
      return $originalName;
    }

    $extensionPosition = strrpos($originalName, '.');

    if ($extensionPosition === false)
    {
      $extension = '';
    }
    else
    {
      $extension = mb_substr($originalName, $extensionPosition);
    }

    $nameWithoutExtension = mb_substr($originalName, 0, strlen($originalName) - strlen($extension));

    $newFileName = '';
    $i = 1;

    do
    {
      $i++;

      $newFileName = sprintf('%s-%d%s', $nameWithoutExtension, $i, $extension);
      $newFilePath = sprintf($path . DIRECTORY_SEPARATOR . $newFileName);
    } while (file_exists($newFilePath));

    return $newFileName;
  }
}
