#############################################
# SQL update script for upgrading 
# from VirtueMart Version <= 1.1.7 to VirtueMart 1.1.8
#
#############################################

# Add a new italian provinces
INSERT INTO `jos_vm_state` (`country_id`, `state_name`, `state_3_code`, `state_2_code`) VALUES
(105, 'Barletta Andria Trani', 'BTA', 'BT'),
(105, 'Fermo', 'FMO', 'FM'),
(105, 'Monza e della Brianza', 'MBA', 'MB');
