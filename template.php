<?php

$GLOBALS['you_page_ids'] = array(109,125,301);
$GLOBALS['you_page_class'] = 'you-page';
$GLOBALS['you_page_url'] = 'node/125';

function wmeu_preprocess_html(&$vars) {
  if (arg(0) == 'node' && is_numeric(arg(1)) && in_array((int)arg(1),$GLOBALS['you_page_ids'])) {
      $vars['classes_array'][] = $GLOBALS['you_page_class'];
  }
}

function wmeu_preprocess_page(&$vars) {
  if (arg(0) == 'node' && is_numeric(arg(1)) && in_array((int)arg(1),$GLOBALS['you_page_ids']))  {
    $vars['site_slogan'] = t('You move Europe');

    $lang = $GLOBALS['language']->language;
    $alias = drupal_get_path_alias($GLOBALS['you_page_url'],$lang);
    $alias_path = (language_default()->language == $lang)? base_path().$alias :  base_path().$lang.'/'.$alias;
    $vars['front_page'] = $alias_path;
  }
}
