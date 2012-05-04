<?php

class alohaCreateContentTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->addArgument('content_name', sfCommandArgument::REQUIRED, 'Content name to add');

    $this->namespace        = 'aloha';
    $this->name             = 'create-content';
    $this->briefDescription = 'Creates new editable content';
    $this->detailedDescription = <<<EOF
The [aloha:create-content|INFO] creates a new editable content in the database.
Call it with:

  [php symfony aloha:create-content|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $contentName = $arguments['content_name'];

    $content = new AlohaContent();
    $content->setName($contentName)
            ->save();

    printf("Content named %s successfully created.\n", $contentName);
  }
}
