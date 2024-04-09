# Simula Levantine Dates for Arabic Sites

![License](https://img.shields.io/badge/license-GPLv2-blue.svg)

## Overview

A WordPress plugin developed by [Simula Lab Ltd](https://simulalab.org) that gives the Arabic site admin the possibility to use Levantine Arabic Month Names in WordPress dates instead of (or in combination with) Modern Standard Arabic month names. It also allows for using Arabic-Indic numerals instead of Arabic numerals in WordPress dates and time.

## Features

- Use Arabic-Indic numeral (١, ٢, ٣) instead of Arabic numerals (1, 2, 3) in WordPress dates and times.
- Use Levantine Arabic months names instead (شباط, آذار) of Modern Standard Arabic months names (فبراير, مارس) in WordPress dates.
- Use a mix of Levantine and Standard Arabic months names (شباط/فبراير, آذار/مارس) or (فبراير/شباط, مارس/آذار) instead of Modern Standard Arabic months names.

## Installation

Login as an admin, navigate to "Plugins" then click the "Add new plugin" button.\
Search for the plugin name in the search bar, then when you find it click on "Install Now". 

or...

Download the plugin folder, compress the folder to a `.zip` file.\
Login as an admin, navigate to "Plugins" then click the "Add new plugin" button.\
Then click on "Upload Plugin", and upload the zip file, then click "Install Now"


Activate the plugin upon successful installation.

## Usage

Make sure the plugin is activated, and that the language of the site is set to "العربية"

Navigate to "الإعدادات", then select "نسق التاريخ في بلاد الشام" from the settings sidebar menu.

Select an option of the available options for "خيارات نسق الشهور"

Check/Uncheck the "استخدم الأرقام الهندية"

Click "Save"

Make sure the desired effect can be observed in the format of dates and times of pages and posts and other site elements.

## Example

Compare the "تم النشر في" of the following screenshots:

Before selecting Levantine Arabic month names and Arabic-Indic numerals:

<img src="assets/screenshot-1.png" alt="example" width="400"/>

After selecting Levantine Arabic month names and Arabic-Indic numberals:

<img src="assets/screenshot-2.png" alt="example" width="400"/>

## Reporting bugs

Please create an issue for the bug, and try to be thourough. 
If possible include screenshots, and other important details such as WordPress version, PHP version, etc.

## Contributing
Contributions are welcome!

When creating a pull request make sure to use the following template:

```
Change Summary
 - item one
 - item two
Related issue number
 - issue a
 - issue b
Checklist
  [ ] code is ready
  [ ] add tests
  [ ] all tests passing
  [ ] test coverage did not drop
  [ ] PR is ready for review
```

## Upgrade on Wordpress.org Directory

- update the plugin code
- test your changes 
- update version number in readme.txt and meta data
- git commit changes to this repository
- use svn to checkout the SVN repository
- add changes to /trunk/ and to /tags/{{major.minor.patch}}/
- svn commit changes to plugins directory

## License
The plugin was developed under the GPL-2.0 license: http://www.gnu.org/licenses/gpl-2.0.html.