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

      if($node_submission = _check_youmove_campaign_submission_page()) {
          if(_is_webform_submission_ready_to_publish($node_submission['node'],$node_submission['submission'])) {
              $vars['classes_array'][] = 'you-campaign-published';
          }
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
    $vars['title'] = $vars['user']->name;
    if(_is_youmove_page()) {
        unset($vars['tabs']['#secondary']);
    }
  }
}

function wmeu_menu_local_task($variables) {
	$menu_item_options = db_query("SELECT options FROM {menu_links} WHERE link_path = :link_path", array(':link_path' => $variables['element']['#link']['path']))->fetchField();
	if ($menu_item_options) {
		$menu_item_options = unserialize($menu_item_options);
		$variables['element']['#link']['localized_options'] = $menu_item_options;
	}
	return theme_menu_local_task($variables);
}

function wmeu_preprocess_webform_submission(&$vars) {}

function wmeu_preprocess_webform_form(&$vars) {}

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




function wmeu_webform_element($variables) {
  $element = $variables['element'];

  $output = '<div ala 1' . drupal_attributes($element['#wrapper_attributes']) . '>' . "\n";
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . webform_filter_xss($element['#field_prefix']) . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . webform_filter_xss($element['#field_suffix']) . '</span>' : '';

  // Generate description for above or below the field.
  $above = !empty($element['#webform_component']['extra']['description_above']);
  $description = array(
    FALSE => '',
    TRUE => !empty($element['#description']) ? ' <div class="description">' . $element['#description'] . "</div>\n" : '',
  );

  // If #children does not contain an element with a matching @id, do not
  // include @for in the label.
  if (isset($variables['element']['#id']) && strpos($element['#children'], ' id="' . $variables['element']['#id'] . '"') === FALSE) {
    $variables['element']['#id'] = NULL;
  }

  switch ($element['#title_display']) {
    case 'inline':
      $output .= $description[$above];
      $description[$above] = '';
      // FALL THRU.
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $description[$above] . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $description[$above] . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $description[$above] . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  $output .= $description[!$above];
  $output .= "</div>\n";

  return $output;
}

function wmeu_file_upload_help(array $variables) {
    // If popover's are disabled, just theme this normally.
  if (!bootstrap_setting('popover_enabled')) {
    return theme_file_upload_help($variables);
  }

  $build = array();
  if (!empty($variables['description'])) {
    $build['description'] = array(
      '#markup' => $variables['description'] . '<br>',
    );
  }

  $descriptions = array();
  $upload_validators = $variables['upload_validators'];
  if (isset($upload_validators['file_validate_size'])) {
    $descriptions[] = t('Files must be less than !size.', array('!size' => '<strong>' . format_size($upload_validators['file_validate_size'][0]) . '</strong>'));
  }
  if (isset($upload_validators['file_validate_extensions'])) {
    $descriptions[] = t('Allowed file types: !extensions.', array('!extensions' => '<strong>' . check_plain($upload_validators['file_validate_extensions'][0]) . '</strong>'));
  }
  if (isset($upload_validators['file_validate_image_resolution'])) {
    $max = $upload_validators['file_validate_image_resolution'][0];
    $min = $upload_validators['file_validate_image_resolution'][1];
    if ($min && $max && $min == $max) {
      $descriptions[] = t('Images must be exactly !size pixels.', array('!size' => '<strong>' . $max . '</strong>'));
    }
    elseif ($min && $max) {
      $descriptions[] = t('Images must be between !min and !max pixels.', array('!min' => '<strong>' . $min . '</strong>', '!max' => '<strong>' . $max . '</strong>'));
    }
    elseif ($min) {
      $descriptions[] = t('Images must be larger than !min pixels.', array('!min' => '<strong>' . $min . '</strong>'));
    }
    elseif ($max) {
      $descriptions[] = t('Images must be smaller than !max pixels.', array('!max' => '<strong>' . $max . '</strong>'));
    }
  }

  if ($descriptions) {
    $id = drupal_html_id('upload-instructions');
    $build['instructions'] = array(
      '#theme' => 'link__file_upload_requirements',
      // @todo remove space between icon/text and fix via styling.
      '#text' => _bootstrap_icon('question-sign') . ' ' . t('Image requirements'),
      '#path' => '#',
      '#options' => array(
        'attributes' => array(
          'data-toggle' => 'popover',
          'data-target' => "#$id",
          'data-html' => TRUE,
          'data-placement' => 'bottom',
          'data-title' => t('Image requirements'),
        ),
        'html' => TRUE,
        'external' => TRUE,
      ),
    );
    $build['requirements'] = array(
      '#theme_wrappers' => array('container__file_upload_requirements'),
      '#attributes' => array(
        'id' => $id,
        'class' => array('element-invisible', 'help-block'),
      ),
    );
    $build['requirements']['validators'] = array(
      '#theme' => 'item_list__file_upload_requirements',
      '#items' => $descriptions,
    );
  }

  return drupal_render($build);
}


function wmeu_form_user_pass_reset_alter(&$form, &$form_state, $form_id) {
  // Check if this is a proper form state (checking message text - this is our main condition - without checking others)
  /*
  if (strpos($form['message']['#markup'], 'This is a one-time login for') && (count($form_state['build_info']['args']) > 1) ) {
      $uid = $form_state['build_info']['args'][0];
      $timestamp = $form_state['build_info']['args'][1];
      $users = user_load_multiple(array($uid), array( 'status' => '1'));
      $account = reset($users);
      $timeout = variable_get('user_password_reset_timeout', 86400);

      $form['message']['#markup'] =
      t('<p>Have you lost your password or haven\'t set one up yet? Click the button below and you will be able to create a new one for your account %user_name.</p><p>This link can only be used once (and will expire on %expiration_date).</p>',
      array('%user_name' => $account->name,'%expiration_date' => format_date($timestamp + $timeout)));
      unset($form['help']);
  }*/
}


function wmeu_form_alter(&$form, &$form_state, $form_id) {
    /*
  if($form_id === 'user_profile_form') {
      if(array_key_exists('account',$form) && array_key_exists('pass',$form['account'])) {
          $form['account']['pass']['#description'] = t('To change your password, enter the new password in both fields.');
      }
  }*/
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

function _check_youmove_campaign_submission_page() {
    if(_is_youmove_campaign_page() && arg(2) == 'submission' && is_numeric(arg(3))) {
        $nid = (int)arg(1);
        $sid = (int)arg(3);

        $node = node_load($nid);
        $submission = webform_get_submission($nid, $sid);

        return array('node' => $node, 'submission' => $submission);
    }

    return NULL;
}

function _is_webform_submission_ready_to_publish($node,$submission) {
    if(module_exists('drupout') && module_exists('webform_youmove')) {
        $settings = variable_get('webform_youmove__ymw_settings');
        $is_published_field_ID = $settings['published_ID'];
        $is_published_field_positive_value = $settings['published_positive_value'];
        $submission_data = drupout_params($node,$submission);

        return ($submission_data[$is_published_field_ID] == $is_published_field_positive_value);
    }
    return false;
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
