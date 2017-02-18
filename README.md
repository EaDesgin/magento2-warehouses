# Magento 2 Warehouses Store View Inventory

Module can be used to separate the stock on different store views based on warehouses with grid for fast stock status editing.

# How to use the module

At first only one zone/warehouse is created by default. You can check the newly created zone under the EaDesign menu.

* the default zone cannot be deleted or edited. This zone will will provide the stock status for all the store views that do not have a specific zone allocated;
* when you add a new zone all the items stock statues will be base on the default zone;
* when you delete a zone all the stock data will be deleted for that zone and your stock data will be handled by the default zone.
* when you add/delete zones you will need to re-index the stock to see the fronted values right away;

# Installation. 

You can install the module via composer or manually by adding it to the app/code directory. The module is available on packagist.org

Via composer

- composer config repositories.magento2-warehouses git git@github.com:EaDesgin/magento2-warehouses;
- sudo composer require eadesignro/module-warehouses;
- php bin/magento setup:upgrade;

# Uninstall 

You need to remove the module. 
**If you added products in the time you used EaDesign Warehouses Store View Inventory you will need to import their stocks to create the stock data. Soon we will add a synchronization system for this.**

#Video install and use

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/Or49WH8diT4/0.jpg)](https://www.youtube.com/watch?v=Or49WH8diT4)
