<?php

/**
 * @file
 * handler class for the new field 'Redeem'
 */

/**
 * class definition
 */
class ds_conectate_views_handler_field_redeem extends views_handler_field {
  /**
   * Render function: return html output
   * Including edit and delete action
   */
  function render($values) {
    module_load_include('inc', 'ds_conectate', 'includes/ds_conectate_callbacks');

    global $user;
    global $base_url;

    if(isset($values->_field_data['nid']['entity'])){
      $node = $values->_field_data['nid']['entity'];
    }else{
      $node = node_load($values->nid);
    }

    $account = user_load($user->uid);
    $authorize = _ds_conectate_authorize_redemption($account, $node);

    if($authorize){
      $query = new EntityFieldQuery();
      $query->entityCondition('entity_type', 'node')
          ->entityCondition('bundle', 'redemption')
          ->propertyCondition('uid', $user->uid)
          ->fieldCondition('field_benefits', 'target_id', $node->nid);

      $result = $query->execute();

      $text = t("Redeem");
      $url = $base_url."/ds_conectate/redimir/".$node->nid.'/'.$user->uid;
      $mark_up = '<a class="button" href="'.$url.'">'.$text.'</a>';

      if (isset($result['node'])) {
        $text = t("Redeemed");
        $mark_up = '<a class="button disabled">'.$text.'</a>';
      }

      $sco = field_get_items('node',$node, 'field_sco_reference');

      if(!empty($sco) && isset($result['node'])){
        // $text = t("Go to Course");
        $text = "Ir al Curso";
        $url = $base_url."/node/".$sco[0]['target_id'].'/play';
        $mark_up = '<a class="button" href="'.$url.'">'.$text.'</a>';
      }

      return $mark_up;
    }

    return '';
  }
  /**
   *
   */
  function query() {
    // Do nothing, leave query blank, we render the contents
  }
}
