<?php
/**
 * Utility methods
 */
class sfAloha
{
  static protected $_instance;
  static protected $_backend;

  protected function __construct()
  {
  }

  /**
   * Retrieves the singleton instance of this class.
   *
   * @return sfAloha A sfAloha implementation instance.
   */
  static public function getInstance()
  {
    if (!isset(self::$_instance))
    {
      self::$_instance = new self();

      $backendClassName = sfConfig::get('app_aloha_backend', 'sfAlohaBackendDoctrine');
      $backend = new $backendClassName();

      self::$_instance->initialize();
    }

    return self::$_instance;
  }

  /**
   * Initializes instance
   */
  public function initialize()
  {
    $backendClassName = sfConfig::get('app_aloha_backend', 'sfAlohaBackendDoctrine');
    self::$_backend = new $backendClassName();
  }

  /**
   * Gets content by name
   *
   * @param string $name
   * @return sfAlohaContent
   */
  public function getContentByName($name)
  {
    $content = $this->getBackend()->getContentByName($name);
    return $content;
  }

  /**
   * Persists Aloha content
   *
   * @param sfAlohaContent $content
   * @throws sfAlohaException
   */
  public function saveContent(sfAlohaContent $content)
  {
    $this->getBackend()->saveContent($content);
  }

  /**
   * Gets content persistence backend
   *
   * @return sfAlohaBackendAbstract
   */
  protected function getBackend()
  {
    return self::$_backend;
  }
}