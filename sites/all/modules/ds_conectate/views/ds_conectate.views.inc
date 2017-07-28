<?php
function ds_conectate_views_data_alter(&$data) {
  // debug($data['node']);
  $data['node']['ds_conectate_redeem_benefit'] = array(
    'title' => t('Redeem'),
    'help' => t('Clickable button to redeem benefits.'),
    'field' => array(
      'handler' => 'ds_conectate_views_handler_field_redeem',
      'group' => 'ds_conectate',
      'click sortable' => FALSE,
    ),
  );

  $data['node']['ds_conectate_benefit_title'] = array(
    'title' => t('Benefit Title'),
    'help' => t('Benefit Title or surprise title.'),
    'field' => array(
      'handler' => 'ds_conectate_views_handler_field_title',
      'group' => 'ds_conectate',
      'click sortable' => TRUE,
    ),
  );

  $data['node']['ds_conectate_benefit_description'] = array(
    'title' => t('Benefit Description'),
    'help' => t('Benefit description or surprise description.'),
    'field' => array(
      'handler' => 'ds_conectate_views_handler_field_description',
      'group' => 'ds_conectate',
      'click sortable' => TRUE,
    ),
  );

  $data['node']['ds_conectate_active_quiz_link'] = array(
    'title' => t('Active Quiz Link'),
    'help' => t('Get the correct URL for viewing trivias.'),
    'field' => array(
      'handler' => 'ds_conectate_views_handler_field_active_quiz_link',
      'group' => 'ds_conectate',
      'click sortable' => TRUE,
    ),
  );
}
