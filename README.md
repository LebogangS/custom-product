# Evolving Web Custom Product module.

This module performs simple CRUD operations utilizing JSON:API and Drupal

Read below about the two Technologies:

- [JSON:API](https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module/jsonapi)
- [Drupal](https://www.drupal.org/documentation)

## Compatibility

⚠️ This module is compatible with Drupal 9 and 10.
⚠️ We will keep maintaining it to be compatible with next verdions of Drupal.

### Installation, configuration and Usage

Navigate to modules path:

- `/admin/modules`

Search for `Custom Products` and enable the module.

Navigate to `/admin/config/services/jsonapi`, activate "Accept all JSON:API create, read, update, and delete operations." and save configuration.

Use `/admin/content/all-products` to see list of all products. Form this page you can be able to edit products using buttons under "Actions tab" and add new products using "Add Product" button at the top of all returned products.
