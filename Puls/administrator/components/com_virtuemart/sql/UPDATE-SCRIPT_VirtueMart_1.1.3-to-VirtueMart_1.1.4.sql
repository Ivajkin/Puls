#############################################
# SQL update script for upgrading 
# from VirtueMart Version <= 1.1.3 to VirtueMart 1.1.4
#
#############################################

# Allow Tax Rates with more than 5 Decimals
ALTER TABLE `jos_vm_tax_rate` CHANGE `tax_rate` `tax_rate` DECIMAL( 10, 5 ) NULL DEFAULT NULL 