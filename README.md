# M2Commerce: Magento 2 Guest Wishlist

## Description

This module provides wishlist functionality for guest users. The default Magento functionality allows only for logged-in users. The wish list will persist until they return, the conversion rate will also benefit.

### Features
- Allow Guest Users to Create Wishlists
- Preserve Guest Wishlists for a specific time
- Retain or Remove Wishlist items after Add to Cart
- Enable users to add items back to the wishlists
- Combine guest’s wishlists with his or her account


## Installation
### Magento® Marketplace

This extension will also be available on the Magento® Marketplace when approved.

1. Go to Magento® 2 root folder
2. Require/Download this extension:

   Enter following commands to install extension.

   ```
   composer require m2commerce/guest-wishlist"
   ```

   Wait while composer is updated.

   #### OR

   You can also download code from this repo under Magento® 2 following directory:

    ```
    app/code/M2Commerce/GuestWishlist
    ```    

3. Enter following commands to enable the module:

   ```
   php bin/magento module:enable M2Commerce_GuestWishlist
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:clean
   php bin/magento cache:flush
   ```

4. If Magento® is running in production mode, deploy static content:

   ```
   php bin/magento setup:static-content:deploy
   ```
