#############################################
# SQL update script for upgrading 
# from VirtueMart Version <= 1.1.6 to VirtueMart 1.1.7
#
#############################################

# Modify default parameters for PayPal API payment type
UPDATE `#__{vm}_payment_method` SET `enable_processor` = 'Y', `is_creditcard` = '1' WHERE `payment_class` = 'ps_paypal_api';
