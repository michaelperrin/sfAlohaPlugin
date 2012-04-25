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
  if ($activatedPlugins === null)
  {
  	// Load default activated plugins
  	
    $activatedPlugins = sfConfig::get('app_aloha_defaultPlugins');
  }

  // TODO : check for projects installed in subdir
  use_javascript(
    '/sfAlohaPlugin/lib/aloha-editor/lib/aloha.js',
    'last',
    array('data-aloha-plugins' => implode(',', $activatedPlugins))
  );

  use_javascript('/sfAlohaPlugin/js/sfAlohaPlugin.js', 'last');
  use_stylesheet('/sfAlohaPlugin/lib/aloha-editor/css/aloha.css');

  $result = '<input type="hidden" id="aloha-save-url" value="' . url_for('@aloha_content_save') . '" />';
  $result .= '<script type="text/javascript">AlohaEditor.init();</script>';
  
  return $result;
}

/**
 * Renders Aloha content
 *
 * @param string $elementId
 * @return string
 */
function aloha_render_element($elementId)
{
  $content = AlohaContentTable::getInstance()->findOneByElementId($elementId);

  if (!$content)
  {
    $autoAdd = sfConfig::get('app_aloha_autoAdd');
    
    if ($autoAdd === true)
    {
      $content = new AlohaContent();
      $content->setElementId($elementId)
              ->save();
    }
    else
    {
      // Do not render anything if the element doesn't exist without "auto add" option
      return '';
    }
  }

  $result = sprintf('<div id="%s" class="editable">', $content->getId());

  $result .= $content->getBody();

  $result .= '</div>';

  return $result;
}
