<?php

namespace Drupal\custom_product\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\custom_product\Controller\ProductsController;

/**
 * Configure example settings for this site.
 */
class UpdateProductForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'updateproduct';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update_products';
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
    $prod_id = basename($current_uri = \Drupal::request()->getRequestUri());

    $obj = new ProductsController();
    $product = $obj->getProduct($prod_id);

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#placeholder' => $product->attributes->title,
    ];
    $form['sku'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SKU'),
      '#placeholder' => $product->attributes->field_sku,
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#placeholder' => $product->attributes->field_description->value,
    ];
    $form['price'] = [
      '#type' => 'textfield',
      '#placeholder' => $product->attributes->field_price,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $prod_id = basename($current_uri = \Drupal::request()->getRequestUri());

    $array = [];

    if ($form_state->getValue('title') !== '') {
      $array["title"] = $form_state->getValue('title');
    }

    if ($form_state->getValue('description') !== '') {
      $array["field_description"] = [
        "value" => $form_state->getValue('description'),
      ];
    }

    if ($form_state->getValue('sku') !== '') {
      $array["field_sku"] = $form_state->getValue('sku');
    }

    if ($form_state->getValue('price') !== '') {
      $array["field_price"] = $form_state->getValue('price');
    }
    $data = [
      "type" => "node--product",
      "id" => explode('/', $_SERVER['REQUEST_URI'])[5],
      "attributes" => $array,
    ];

    $postdata = json_encode([
      "data" => $data,
    ]);

    try {
      $ch = curl_init(\Drupal::request()->getSchemeAndHttpHost() . '/jsonapi/node/product/' . $prod_id);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/vnd.api+json',
        'Accept: application/vnd.api+json',
        'Authorization:Basic YXBpOmFwaQ==',
      ]);

      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

      $result = curl_exec($ch);
    }
    catch (Exception $e) {
      throw new Exception("Invalid URL: ", 0, $e);
    }
    finally {
      curl_close($ch);
      \Drupal::messenger()->addMessage('Product Successfully Updated');
      header("Location: /admin/content/all-products", TRUE, 301);
      exit();

      return $result;
    }
  }

}
