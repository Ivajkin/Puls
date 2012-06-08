#############################################
# SQL update script for upgrading 
# from VirtueMart Version <= 1.1.5 to VirtueMart 1.1.6
#
#############################################

# Modify default values for some text and textarea fields
UPDATE `#__{vm}_userfield` SET `value` = 'NULL', `default` = 'NULL' WHERE `#__{vm}_userfield`.`title` = 'REGISTER_UNAME' or `#__{vm}_userfield`.`title` = 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE';
