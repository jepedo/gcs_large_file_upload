<?php

namespace Drupal\gcs_large_file_upload\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class GCSUploadSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['gcs_large_file_upload.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gcs_large_file_upload_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('gcs_large_file_upload.settings');

    // Field for setting the file size limit in MB.
    $form['file_size_limit_mb'] = [
      '#type' => 'number',
      '#title' => $this->t('File size limit (MB)'),
      '#default_value' => $config->get('file_size_limit_mb'),
      '#min' => 1,
      '#required' => TRUE,
    ];

    // Field for setting the Google Cloud Storage project ID.
    $form['gcs_project_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('GCS Project ID'),
      '#default_value' => $config->get('gcs_project_id'),
      '#required' => TRUE,
    ];

    // Field for setting the Google Cloud Storage bucket name.
    $form['gcs_bucket_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('GCS Bucket Name'),
      '#default_value' => $config->get('gcs_bucket_name'),
      '#required' => TRUE,
    ];

    // Field for uploading the Google Cloud service account keyfile.
    $form['gcs_keyfile'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('GCS Keyfile'),
      '#description' => $this->t('Upload the JSON keyfile for your Google Cloud service account.'),
      '#upload_location' => 'private://gcs_keyfiles/',
      '#default_value' => $config->get('gcs_keyfile'),
      '#required' => TRUE,
    ];

    // Field for setting the Pub/Sub message to be sent upon successful file upload.
    $form['pubsub_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Pub/Sub Message'),
      '#description' => $this->t('Enter the message to be sent to Pub/Sub upon successful file upload. Leave empty to disable.'),
      '#default_value' => $config->get('pubsub_message'),
      '#required' => FALSE,
    ];

    // Field for setting the Pub/Sub topic to publish messages to.
    $form['pubsub_topic'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pub/Sub Topic'),
      '#description' => $this->t('Enter the Pub/Sub topic to publish messages to.'),
      '#default_value' => $config->get('pubsub_topic'),
      '#required' => FALSE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Ensure that the GCS keyfile is uploaded.
    if (empty($form_state->getValue('gcs_keyfile'))) {
      $form_state->setErrorByName('gcs_keyfile', $this->t('The GCS Keyfile is required.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Make the uploaded keyfile permanent.
    $keyfile = $form_state->getValue('gcs_keyfile');
    if (!empty($keyfile)) {
      $file = File::load($keyfile[0]);
      $file->setPermanent();
      $file->save();
    }

    // Save the configuration settings.
    $this->config('gcs_large_file_upload.settings')
      ->set('file_size_limit_mb', $form_state->getValue('file_size_limit_mb'))
      ->set('gcs_project_id', $form_state->getValue('gcs_project_id'))
      ->set('gcs_bucket_name', $form_state->getValue('gcs_bucket_name'))
      ->set('gcs_keyfile', $keyfile)
      ->set('pubsub_message', $form_state->getValue('pubsub_message'))
      ->set('pubsub_topic', $form_state->getValue('pubsub_topic'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
