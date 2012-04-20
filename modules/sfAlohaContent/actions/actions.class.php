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
}
