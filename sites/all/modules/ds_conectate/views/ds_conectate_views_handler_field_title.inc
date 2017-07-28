<?php

/**
 * @file
 * handler class for the new field 'Benefit Title'
 */

/**
 * class definition
 */
class ds_conectate_views_handler_field_title extends views_handler_field {
  /**
   * Render function: return html output
   * Including edit and delete action
   */
  function render($values) {
    module_load_include('inc', 'ds_conectate', 'includes/ds_conectate_callbacks');

    global $user;
    if(isset($values->_field_data['nid']['entity'])){
      $node = $values->_field_data['nid']['entity'];
    }else{
      $node = node_load($values->nid);
    }

    $account = user_load($user->uid);

    if(_ds_conectate_is_surprise_benefit($account, $node)){
      $surprise_title = field_get_items('node', $node, 'field_surprise_title');
      if($surprise_title && isset($surprise_title[0]['value'])){
        return '<div class="surprise-benefit"></div>'.$surprise_title[0]['value'];
      }else{
        return '<a>'.t('Surprise Benefit').'</a>';
      }
    }

    $alias = drupal_get_path_alias('node/'.$node->nid);

    #return '<a href="'.$alias.'">'.$node->title.'</a>';
    return '<a>'.$node->title.'</a>';
  }
  /**
   *
   */
  function query() {
    // Do nothing, leave query blank, we render the contents
  }
}
