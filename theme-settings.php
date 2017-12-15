<?php

function wmeu_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL) {

     /*** YOUMOVE PART ***/
    $form['youmove'] = array(
      '#type' => 'fieldset',
      '#title' => t('YouMove'),
    );

    $form['youmove']['ym_campaign_webform_id'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove webform page ID'),
      '#default_value' => theme_get_setting('ym_campaign_webform_id'),
    );

    $form['youmove']['ym_page_ids'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove page IDs'),
      '#default_value' => theme_get_setting('ym_page_ids'),
      '#description'   => t('YouMove pages IDs seprated by comma'),
    );

    $form['youmove']['ym_main_page_url'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove main page url'),
      '#default_value' => theme_get_setting('ym_main_page_url'),
    );

    $form['youmove']['ym_campaigner_role_id'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove campaigner role ID'),
      '#default_value' => theme_get_setting('ym_campaigner_role_id'),
    );

    $form['youmove']['ym_external_site_url'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove external site absolute url'),
      '#default_value' => theme_get_setting('ym_external_site_url'),
    );

    $form['youmove']['ym_external_site_css_url'] = array(
      '#type' => 'textfield',
      '#title' => t('YouMove external site css absolute url'),
      '#default_value' => theme_get_setting('ym_external_site_css_url'),
    );
}
