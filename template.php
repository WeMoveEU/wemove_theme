<?php

function wmeu_preprocess_html(&$vars) {
  /*** YOUMOVE PART ***/
  if (_is_youmove_page()) {
      $vars['classes_array'][] = 'you-page';

      drupal_add_css(theme_get_setting('ym_external_site_css_url'),array('type' => 'external'));
      drupal_add_css(path_to_theme() . '/css/style_youmove.css');

      drupal_add_js(array('youmove' => array('campaign_page_class' => 'you-campaign')), 'setting');
      drupal_add_js(drupal_get_path('theme', 'wmeu') . '/js/youmove.js');

      if(_is_youmove_campaign_page()) {
          $vars['classes_array'][] = 'you-campaign';
      }

  }
  /*** NOT YOUMOVE PART ***/
  else {
      drupal_add_css(path_to_theme() . '/css/style.css');
  }
}

function wmeu_preprocess_page(&$vars) {

  /*** YOUMOVE PART ***/
  if (_is_youmove_page())  {
    $vars['youmove_url'] = theme_get_setting('ym_external_site_url');
    $vars['site_slogan'] = t('You move Europe');

    $lang = $GLOBALS['language']->language;
    $alias = drupal_get_path_alias(theme_get_setting('ym_main_page_url'),$lang);
    $alias_path = (language_default()->language == $lang)? base_path().$alias :  base_path().$lang.'/'.$alias;
    $vars['front_page'] = $alias_path;

    $vars['theme_hook_suggestions'][] = 'page__youmove';
  }

  /*** USER PAGE ***/
  if (user_is_logged_in() && arg(0) == 'user' && is_numeric(arg(1)) ) {
    $vars['title'] = t('My account: ') . $vars['user']->name;
    if(_is_youmove_page()) {
        unset($vars['tabs']['#secondary']);
    }
  }
}

function wmeu_preprocess_node(&$vars) {}

function wmeu_preprocess_block(&$vars) {

    /*** YOUMOVE PART***/
     if (_is_youmove_page())  {
        // Language block
        if($vars['block']->module == 'locale' && $vars['block']->delta == 'language') {
                $curr_content = $vars['content'];
                $pos_ul_start = strpos($curr_content,'<ul');
                $pos_ul_end = strpos($curr_content,'>',$pos_ul_start);

                if (is_int($pos_ul_start) && is_int($pos_ul_end) && ($pos_ul_start < $pos_ul_end)) {
                    $new_content = '<ul id="language" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownlanguage">' . substr($curr_content,$pos_ul_end+1);
                    $curr_content = $new_content;
                }
                $vars['content'] =
                        '<div class="dropdown">' .
                        '<button class="btn btn-default dropdown-toggle" type="button" id="dropdownlanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' .
                            t('Other languages') .
                            '<span class="caret"></span>' .
                        '</button>'. $curr_content .'</div>';
        }
    }
}


function wmeu_webform_display_file($variables) {
    $element = $variables['element'];

    // Convert image name to image display on webform (campaign) submission view
    if(_is_youmove_campaign_page() &&
       isset($element['#webform_component']) && isset($element['#value']) &&
       $element['#value']->type === 'image') {

           $file = $element['#value'];
           $url = !empty($file) ? webform_file_url($file->uri) : t('no upload');
           $image_array = array( 'path' => $url);
           $image_array['alt'] = $file->alt;
           $image_array['title'] = $file->title;
           $image_array['width'] = $file->width;
           $image_array['height'] = $file->height;

           return !empty($file) ? ($element['#format'] == 'text' ? $url : theme('image', $image_array) ) : ' ';
    }
    return theme_webform_display_file($variables);
}



function _is_youmove_page() {
    $ym_page_ids = explode(',',theme_get_setting('ym_page_ids'));
    return (
        //YouMove static pages
        arg(0) == 'node' && is_numeric(arg(1)) && in_array((int)arg(1),$ym_page_ids)
        ||
        //YouMove user role pages
        arg(0) == 'user' && user_has_role(theme_get_setting('ym_campaigner_role_id'))
        ||
        //YouMove ym_campaign_webform_id
        _is_youmove_campaign_page()
    );
}

function _is_youmove_campaign_page() {
    return (
        arg(0) == 'node' && is_numeric(arg(1)) && (int)arg(1) == (int)theme_get_setting('ym_campaign_webform_id')
    );
}





function wmeu_status_messages($variables){
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= reset($messages);
    }
    $output .= "</div>\n";
  }
  return $output;
}
