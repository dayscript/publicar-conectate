<?php

/**
 * @file
 * handler class for the new field 'Benefit Title'
 */

/**
 * class definition
 */
class ds_conectate_views_handler_field_active_quiz_link extends views_handler_field {
  /**
   * Render function: return html output
   * Including edit and delete action
   */
  function render($values) {
    global $user;
    global $base_url;

    if(isset($values->_field_data['nid']['entity'])){
      $node = $values->_field_data['nid']['entity'];
    }else{
      $node = node_load($values->nid);
    }

    $account = user_load($user->uid);

    $sql = 'SELECT result_id
            FROM {quiz_node_results}
            WHERE uid = :uid
              AND nid = :nid
              AND time_end IS NOT NULL
            ORDER BY result_id
            LIMIT 1';

    $args = array(
      ':uid' => $user->uid,
      ':nid' => $node->nid,
    );

    foreach (db_query($sql, $args) as $result) {
      return '<a href="'.$base_url.'/node/'.$node->nid.'/quiz-results/'.$result->result_id.'/view">'.$node->title.'</a>';
    }

    return '<a href="'.$base_url.'/node/'.$node->nid.'/take">'.$node->title.'</a>';
  }
  /**
   *
   */
  function query() {
    // Do nothing, leave query blank, we render the contents
  }

  function count(){
    # porque no sube esto
    $count = 0;
    $prueba = 0;
    return $count;

  }
}

# end
