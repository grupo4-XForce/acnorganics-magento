# Install Whatsapp Chat Extension For Magento 2 Site

Method 1: Installation Through Downloaded Package
Step- 1: Download the latest version of package WhatsApp Chat .
Step- 2: Unzip package to MagentoRoot/app/code .
Step- 3: After unzip directory structure will be MagentoRoot/app/code/Wbcom/Whatsapp
Step- 4: Run commands for upgrade and deploy.

php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush

Important Notes:
(a) For each Wbcom extensions, Wbcom Core package is required.
(b) Wbcom core package is added with all extension to related to it. 


