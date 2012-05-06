#############################################
# SQL update script for upgrading 
# from VirtueMart Version <= 1.1.4 to VirtueMart 1.1.5
#
#############################################

# Add new PayPal API payment method
INSERT INTO `#__{vm}_payment_method` VALUES( 4, 1, 'PayPal (new API)', 'ps_paypal_api', 5, '0.00', 0, '0.00', '0.00', 0, 'PP_API', 'Y', 1, 'N', '', '', '')
INSERT INTO `#__{vm}_payment_method` VALUES (19, 1, 'MerchantWarrior', 'ps_merchantwarrior', 5, '0.00', 0, '0.00', '0.00', 1, 'MW', 'Y', 1, 'Y', '1,2,3,5,7,', '', '');
