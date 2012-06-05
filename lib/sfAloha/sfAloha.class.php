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

  /**
   * Checks if the current user has rights to edit content
   *
   * @return boolean false if user doesn't have rights, true otherwise
   */
  public function checkAccess()
  {
    $user = sfContext::getInstance()->getUser();

    $securityConf = sfConfig::get('app_aloha_security');

    // If no configuration is found regarding security, do not allow any action
    if (empty($securityConf) || !isset($securityConf['edit']))
    {
      return false;
    }

    $editSecurity = $securityConf['edit'];

    // Check authentication
    if (!array_key_exists('authenticated', $editSecurity))
    {
      return false;
    }

    if ($editSecurity['authenticated'] != false && !$user->isAuthenticated())
    {
      return false;
    }

    // Check credentials
    if (!array_key_exists('credentials', $editSecurity))
    {
      return false;
    }

    if ($editSecurity['credentials'] != false && !$user->hasCredential($editSecurity['credentials']))
    {
      return false;
    }

    return true;
  }
}