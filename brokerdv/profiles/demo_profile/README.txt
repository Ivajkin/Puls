
-- SUMMARY --

Allows restoration of the Demonstration Site module's database dumps
during site installation.

For a full description visit the project page:
  http://drupal.org/project/demo_profile
Bug reports, feature suggestions and latest developments:
  http://drupal.org/project/issues/demo_profile


-- REQUIREMENTS --

* Demonstration site module
  http://drupal.org/project/demo

* At least one demonstration site snapshot previously created with the module.


-- INSTALLATION --

* Download and extract the Demonstration site profile (this package) into the
  site's profiles directory (next to the "default" profile):

    /profiles/demo_profile

* Download and extract the Demonstration site module into the site's modules
  directory (as usual).

* Copy the previously created snapshot(s) into the site's private files
  directory, for example:

    /sites/default/private/files/demo

* Start the installation by pointing your browser to:

    http://example.com/install.php

* Select the "Demonstration site" profile.

* REQUIRED: Use the same database prefix you were using for the original site.

* Select the snapshot to restore.


-- CONTACT --

Current maintainers:
* Rob Loach - http://drupal.org/user/61114
* Daniel F. Kudwien (sun) - http://drupal.org/user/54136

