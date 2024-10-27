<?php

namespace Drupal\custom_product\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class CreateProductForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'createProduct';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'create_product';
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

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
    ];
    $form['sku'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SKU'),
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
    ];
    $form['price'] = [
      '#type' => 'number',
      '#min' => 0,
      '#step' => 0.01,
      '#title' => $this->t('Price'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * Overrrides form submit function.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = [
      "type" => "node--product",
      "attributes" => [
        "title" => $form_state->getValue('title'),
        "field_description" => [
          "value" => $form_state->getValue('description'),
        ],
        "field_price" => $form_state->getValue('price'),
        "field_sku" => $form_state->getValue('sku'),
      ]
    ];

    $postdata = json_encode([
      "data" => $data,
    ]);

    $ch = curl_init(\Drupal::request()->getSchemeAndHttpHost() . '/jsonapi/node/product/');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/vnd.api+json',
      'Accept: application/vnd.api+json',
    ]);

    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    $result = curl_exec($ch);
    \Drupal::messenger()->addMessage('Product Successfully created');
    header("Location: /admin/content/all-products", TRUE, 301);
    exit();

    return $result;

  }

}
