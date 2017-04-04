# Creatuity Custom Maintenance for Magento 2 
Customer Maintenance mode allows you to set message to your customer or display dowtime counter (and many more). 

### How to install ###

#### Composer ####
1. Type
`composer require creatuity/magento2-custom-maintenance`
2. Run `bin/magento setup:upgrade`

#### Manual ####
1. Put code from master branch under app/etc/Creatuity/CustomMaintenance
2. Copy content of pub directory to /pub directory of your Magento project
3. Run `bin/magento setup:upgrade`