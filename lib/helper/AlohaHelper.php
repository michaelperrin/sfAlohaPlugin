<?php
/**
 * Helper for Aloha content
 */

/**
 * Initializes a page with Aloha content
 *
 * @param array $activatedPlugins Aloha Editor plugins to activate
 * @return string HTML elements to initialize the page
 */
function aloha_init_page(array $activatedPlugins = null)
{
  if (!sfAloha::getInstance()->checkAccess())
  {
    // The user doesn't have credentials to edit content.
    // No need to load the Aloha Editor library
    return '';
  }

  if ($activatedPlugins === null)
  {
    // Load default activated plugins
    $activatedPlugins = sfConfig::get('app_aloha_defaultPlugins');
  }

  use_javascript(
    '/sfAlohaPlugin/lib/aloha-editor/lib/aloha.js',
    'last',
    array('data-aloha-plugins' => implode(',', $activatedPlugins))
  );

  use_javascript('/sfAlohaPlugin/js/sfAlohaPlugin.js', 'last');
  use_stylesheet('/sfAlohaPlugin/lib/aloha-editor/css/aloha.css');
  use_stylesheet('/sfAlohaPlugin/css/sfAlohaPlugin.css');

  $result = '<input type="hidden" id="aloha-save-url" value="' . url_for('@aloha_content_save') . '" />';
  $result .= '<script type="text/javascript">AlohaEditor.init();</script>';

  if (array_search('sfAloha/image-upload', $activatedPlugins))
  {
    // Image upload plugin is activated
    $result .= aloha_init_upload_image_plugin();
  }

  return $result;
}

/**
 * Inits image upload plugin
 *
 * @return string
 */
function aloha_init_upload_image_plugin()
{
  static $initializedImageUploadPlugin;

  if (isset($initializedImageUploadPlugin))
  {
    // The image upload plugin has already been initialized. No need to do it twice
    return '';
  }

  $initializedImageUploadPlugin = true;

  $form = new sfAlohaImageUploadForm();

  $result = $form->renderFormTag(
    url_for('aloha_upload_file'),
    array('id' => 'aloha-image-upload-form')
  );

  $result .= $form->render(
    array(
      'action'  => url_for('aloha_upload_file'),
      'enctype' => 'multipart/form-data',
      'id'      => 'aloha-image-upload-form'
    )
  );

  $result .= '</form>';

  return $result;
}

/**
 * Renders Aloha content
 *
 * @param string $name
 * @return string
 */
function aloha_render_element($name)
{
  $aloha = sfAloha::getInstance();
  $alohaContent = $aloha->getContentByName($name);

  if (!$alohaContent)
  {
    $autoAdd = sfConfig::get('app_aloha_autoAdd');

    if ($autoAdd === true)
    {
      $alohaContent = new sfAlohaContent();
      $alohaContent->setName($name);
      $aloha->saveContent($alohaContent);
    }
    else
    {
      // Do not render anything if the element doesn't exist without "auto add" option
      return '';
    }
  }

  $result = sprintf('<div data-name="%s" class="editable">', esc_specialchars($alohaContent->getName()));

  $result .= $alohaContent->getBody();

  $result .= '</div>';

  return $result;
}
