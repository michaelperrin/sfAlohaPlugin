<?php

/**
 * sfAlohaContentActions actions.
 */
class sfAlohaContentActions extends sfActions
{
  public function preExecute()
  {
    $this->_checkSecurity();
  }

  /**
   * Save content action
   *
   * @param sfWebRequest $request
   */
  public function executeSave(sfWebRequest $request)
  {
    $name   = $request->getParameter('name');
    $body   = $request->getParameter('body');

    if (!$name || !$body)
    {
      $this->forward404();
    }

    $aloha = sfAloha::getInstance();
    $alohaContent = $aloha->getContentByName($name);

    $this->forward404Unless($alohaContent);

    $alohaContent->setBody($body);
    $aloha->saveContent($alohaContent);

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

  /**
   * Checks if the current user is allowed to edit content
   */
  protected function _checkSecurity()
  {
    $this->forward404Unless(sfAloha::getInstance()->checkAccess());
  }
}
