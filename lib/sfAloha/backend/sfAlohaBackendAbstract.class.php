<?php
abstract class sfAlohaBackendAbstract
{
  /**
   * Gets content by name
   *
   * @param string $name
   * @return sfAlohaContent
   */
  public abstract function getContentByName($name);

  /**
   * Persists Aloha content
   *
   * @param sfAlohaContent $alohaContent
   * @throws sfAlohaException
   */
  public abstract function saveContent(sfAlohaContent $alohaContent);
}
