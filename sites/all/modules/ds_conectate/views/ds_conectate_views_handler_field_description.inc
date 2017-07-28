<?php

/**
 * @file
 * handler class for the new field 'Benefit description'
 */

/**
 * class definition
 */
class ds_conectate_views_handler_field_description extends views_handler_field {
  /**
   * Render function: return html output
   * Including edit and delete action
   */
  function render($values) {
    module_load_include('inc', 'ds_conectate', 'includes/ds_conectate_callbacks');

    global $user;
    $account = user_load($user->uid);

    if(isset($values->_field_data['nid']['entity'])){
      $node = $values->_field_data['nid']['entity'];
    }else{
      $node = node_load($values->nid);
    }

    if(_ds_conectate_is_surprise_benefit($account, $node)){
      $surprise_description = field_get_items('node', $node, 'field_description_surprise');
      if($surprise_description && isset($surprise_description[0]['value'])){
        return $surprise_description[0]['value'];
      }else{
        return t('Redeem this benefit to know it.');
      }
    }

    return $node->body[LANGUAGE_NONE][0]['safe_value'];
  }
  /**
   *
   */
  function query() {
    // Do nothing, leave query blank, we render the contents
  }
}
