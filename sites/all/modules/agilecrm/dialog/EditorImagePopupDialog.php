<?php

namespace Drupal\agilecrm_module\Form;

use Drupal\Component\Utility\Bytes;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\Entity\FilterFormat;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\editor\Ajax\EditorDialogSave;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\image_popup\Controller\ImagePopup;

/**
 * Provides an image dialog for text editors.
 */
class EditorImagePopupDialog extends FormBase {

  /**
   * The file storage service.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $fileStorage;

  /**
   * Constructs a form object for image dialog.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $file_storage
   *   The file storage service.
   */
  public function __construct(EntityStorageInterface $file_storage) {
    $this->fileStorage = $file_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('file')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'editor_image_dialog';
  }

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\filter\Entity\FilterFormat $filter_format
   *   The filter format for which this dialog corresponds.
   */
  public function buildForm(array $form, FormStateInterface $form_state, FilterFormat $filter_format = NULL) {
    // This form is special, in that the default values do not come from the
    // server side, but from the client side, from a text editor. We must cache
    // this data in form state, because when the form is rebuilt, we will be
    // receiving values from the form, instead of the values from the text
    // editor. If we don't cache it, this data will be lost.
    $query = \Drupal::database()->select('agilecrm_module', 'u');
    $query->fields('u', ['agilecrm_module_id','setting_domain','setting_email','setting_password','setting_restapikey','setting_jsapikey']);
    $results = $query->execute()->fetchAll();
    foreach ($results as $k => $v) {  
         $domain =  $v->setting_domain;
          $email =  $v->setting_email; 
          $password =  $v->setting_password; 
          $restapi = $v->setting_restapikey;
          $jsapikey = $v->setting_jsapikey;
    }
    define("AGILE_USER_EMAIL",$email);
    define("AGILE_REST_API_KEY",$restapi);
    define("AGILE_DOMAIN",$domain);
    $result = $this->curl_wrap("forms", null, "GET", "application/json");
    if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
    $result = json_decode($result,TRUE, 512, JSON_BIGINT_AS_STRING);
    } else {
    $result = json_decode($result,TRUE);
    }
    $projects = array();
    foreach ($result as $project) {
    $projects[$project['id']] = $project['formName'];

    }  

    $form['#tree'] = TRUE;
    $form['#attached']['library'][] = 'editor/drupal.editor.dialog';
    $form['#prefix'] = '<div id="editor-image-dialog-form">';
    $form['#suffix'] = '</div>';

  
    $form['actions'] = array(
      '#type' => 'actions',
    );
    $form['form_builder'] = array(
      '#title' => t('Form Builder'),
      '#type' => 'select',
      '#options' =>  $projects,
    );

    $form['actions']['save_modal'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      // No regular submit-handler. This form only works via JavaScript.
      '#submit' => array(),
      '#ajax' => array(
        'callback' => '::submitForm',
        'event' => 'click',
      ),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $image_style = $form_state->getValue('form_builder');
    $query = \Drupal::database()->select('agilecrm_module', 'u');
    $query->fields('u', ['agilecrm_module_id','setting_domain','setting_email','setting_password','setting_restapikey','setting_jsapikey']);
    $results = $query->execute()->fetchAll();
    foreach ($results as $k => $v) {  
                $domain =  $v->setting_domain;
                $email =  $v->setting_email; 
                $password =  $v->setting_password; 
                $restapi = $v->setting_restapikey;
                $jsapikey = $v->setting_jsapikey;
       }
      $image_render = '<iframe width="600px" height="600px"  src="https://' . $domain . '.agilecrm.com/forms/' . $image_style . '"  frameborder="0"></iframe>';
      $form_state->setValue('image_render', $image_render);
      $test = $form_state->getValues();
      $response->addCommand(new EditorDialogSave($form_state->getValues()));
      $response->addCommand(new CloseModalDialogCommand());
      return $response;
  }
  function curl_wrap($entity, $data, $method, $content_type) {
    if ($content_type == NULL) {
        $content_type = "application/json";
    }
    
    //$agile_url = "https://" . AGILE_DOMAIN . "-dot-sandbox-dot-agilecrmbeta.appspot.com/dev/api/" . $entity;
    $agile_url = "https://" . AGILE_DOMAIN . ".agilecrm.com/dev/api/" . $entity;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
    switch ($method) {
        case "POST":
            $url = $agile_url;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case "GET":
            $url = $agile_url;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            break;
        case "PUT":
            $url = $agile_url;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case "DELETE":
            $url = $agile_url;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default:
            break;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-type : $content_type;", 'Accept : application/json'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, AGILE_USER_EMAIL . ':' . AGILE_REST_API_KEY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

}
