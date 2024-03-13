<?php

namespace Drupal\smpl_base\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure smpl_base settings.
 */
class SmplBaseSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   *  @var string
   */
  const SETTINGS = 'smpl_base.settings';

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Drupal state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StateInterface $state) {
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'smpl_base_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    // Error pages.
    $form['error_pages'] = [
      '#type' => 'details',
      '#title' => $this->t('Error Pages Settings'),
      '#open' => FALSE,
    ];

    $form['error_pages']['404_page'] = [
      '#type' => 'details',
      '#title' => $this->t('404 Error Page Settings'),
      '#open' => TRUE,
    ];

    $form['error_pages']['404_page']['smpl_error_404_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('404 Page Title'),
      '#default_value' => $config->get('smpl_error_404_title'),
    ];

    $form['error_pages']['404_page']['smpl_error_404_subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('404 Page Subtitle'),
      '#default_value' => $config->get('smpl_error_404_subtitle'),
    ];

    $form['error_pages']['404_page']['smpl_error_404_content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('404 Page content'),
      '#default_value' => $config->get('smpl_error_404_content'),
      '#format' => 'full_html',
    ];

    $form['error_pages']['403_page'] = [
      '#type' => 'details',
      '#title' => $this->t('403 Error Page Settings'),
      '#open' => TRUE,
    ];

    $form['error_pages']['403_page']['smpl_error_403_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('403 Page Title'),
      '#default_value' => $config->get('smpl_error_403_title'),
    ];

    $form['error_pages']['403_page']['smpl_error_403_subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('403 Page Subtitle'),
      '#default_value' => $config->get('smpl_error_403_subtitle'),
    ];

    $form['error_pages']['403_page']['smpl_error_403_content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('403 Page content'),
      '#default_value' => $config->get('smpl_error_403_content'),
      '#format' => 'full_html',
    ];

    $form['error_pages']['maintenance_page'] = [
      '#type' => 'details',
      '#title' => $this->t('Maintenance Page Settings'),
      '#open' => TRUE,
    ];

    $form['error_pages']['maintenance_page']['smpl_error_maintenance_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Maintenance Page Title'),
      '#default_value' => $config->get('smpl_error_maintenance_title'),
    ];

    $form['error_pages']['maintenance_page']['smpl_error_maintenance_subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Maintenance Page Subtitle'),
      '#default_value' => $config->get('smpl_error_maintenance_subtitle'),
    ];

    $form['error_pages']['maintenance_page']['markup'] = [
      '#type' => 'item',
      '#markup' => $this->t('Fill maintenance page content in <a href=":url">Maintenance Mode settings</a>.', [
        ':url' => Url::fromRoute('system.site_maintenance_mode')
          ->toString(TRUE)
          ->getGeneratedUrl(),
      ]),
    ];

    $privacy_policy_entity = NULL;
    if ($this->state->get('smpl_privacy_policy_nid')) {
      $privacy_policy_entity = $this->entityTypeManager->getStorage('node')->load($this->state->get('smpl_privacy_policy_nid'));
    }

    $form['smpl_privacy_policy_nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Privacy Policy Page'),
      '#description' => $this->t('The current privacy policy.'),
      '#target_type' => 'node',
      '#selection_settings' => ['target_bundles' => ['page']],
      '#required' => FALSE,
      '#default_value' => $privacy_policy_entity,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config(static::SETTINGS);

    // Error pages.
    $config->set('smpl_error_404_title', $form_state->getValue('smpl_error_404_title'));
    $config->set('smpl_error_404_subtitle', $form_state->getValue('smpl_error_404_subtitle'));
    $config->set('smpl_error_404_content', $form_state->getValue('smpl_error_404_content')['value']);
    $config->set('smpl_error_403_title', $form_state->getValue('smpl_error_403_title'));
    $config->set('smpl_error_403_subtitle', $form_state->getValue('smpl_error_403_subtitle'));
    $config->set('smpl_error_403_content', $form_state->getValue('smpl_error_403_content')['value']);
    $config->set('smpl_error_maintenance_title', $form_state->getValue('smpl_error_maintenance_title'));
    $config->set('smpl_error_maintenance_subtitle', $form_state->getValue('smpl_error_maintenance_subtitle'));

    // General settings.
    $this->state->set('smpl_privacy_policy_nid', $form_state->getValue('smpl_privacy_policy_nid'));

    $config->save();
  }

}
