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
  if ($activatedPlugins === null) {
    $activatedPlugins = array(
      'common/format',
      'common/list',
      'common/link',
      'common/highlighteditables',
      'common/block',
      'common/undo',
      'common/contenthandler',
      'common/paste',
      'common/commands',
      'common/table',
      'common/align',
      'sfAloha/save',
    );
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

  if (!$content) {
    return;
  }

  $result = sprintf('<div id="%s" class="editable">', $content->getId());

  if ($content) {
    $result .= $content->getBody();
  }

  $result .= '</div>';

  return $result;
}
