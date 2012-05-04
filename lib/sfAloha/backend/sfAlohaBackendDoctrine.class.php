<?php
class sfAlohaBackendDoctrine extends sfAlohaBackendAbstract
{
  /**
   * {@inheritdoc}
   */
  public function getContentByName($name)
  {
    $doctrineAlohaContent = AlohaContentTable::getInstance()->findOneByName($name);

    if (!$doctrineAlohaContent)
    {
      return null;
    }

    $alohaContent = new sfAlohaContent();
    $alohaContent->setName($doctrineAlohaContent->getName())
                 ->setBody($doctrineAlohaContent->getBody());

    return $alohaContent;
  }

  /**
   * {@inheritdoc}
   */
  public function saveContent(sfAlohaContent $alohaContent)
  {
    // Get the existing content or create a new one
    $doctrineAlohaContent = AlohaContentTable::getInstance()->findOneByName($alohaContent->getName());

    if (!$alohaContent)
    {
      $doctrineAlohaContent = new AlohaContent();
      $doctrineAlohaContent->setName($alohaContent->getName());
    }

    $doctrineAlohaContent->setBody($alohaContent->getBody());
    $doctrineAlohaContent->save();
  }
}
