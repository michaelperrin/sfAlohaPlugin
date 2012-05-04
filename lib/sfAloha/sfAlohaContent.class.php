<?php
/**
 * Aloha content entity, independant from how it is stored
 */
class sfAlohaContent
{
  /**
   * Unique internal identifier
   *
   * @var int
   */
  protected $_id;

  /**
   * Unique name
   *
   * @var string
   */
  protected $_name;

  /**
   * Content's body
   *
   * @var string
   */
  protected $_body;

  /**
   * Gets id
   *
   * @return int
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Gets name
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }

  /**
   * Gets body
   *
   * @return string
   */
  public function getBody()
  {
    return $this->_body;
  }

  /**
   * Sets id
   *
   * @param int $id
   * @return sfAlohaContent
   */
  public function setId($id)
  {
    $this->_id = $id;
    return $this;
  }

  /**
   * Sets name
   *
   * @param string $name
   * @return sfAlohaContent
   */
  public function setName($name)
  {
    $this->_name = $name;
    return $this;
  }

  /**
   * Sets body
   *
   * @param string $body
   * @return sfAlohaContent
   */
  public function setBody($body)
  {
    $this->_body = $body;
    return $this;
  }
}
