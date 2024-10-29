# Description

The BBH Booking Form plugin create a simple, customizable and clean booking form to connect your [BeBetterHotels](https://www.bebetterhotels.com/) customer id with our search engine.

The plugin sends the catched fields trought your webform to the website https://clickandbook.net without share any aditional information.

BeBetterHotels [terms](https://www.bebetterhotels.com/terms/) and [privacy policy](https://www.bebetterhotels.com/politicas-de-privacidad/)

## Requirements
> * PHP 7.4 or higher
> * WordPress 5.2.0 or higher
> * A BeBetterHotels customer id

## Installation

1. Upload the plugin to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage
1. Configure the UI aspects of the plugin in submenu: Tools > BeBetterHotels
2. Availables Shortcodes: [bebetterhotels]
  - Parameters
    > * customer: : BeBetterHotels Customer ID
    > * adults_field: Determine if the field is required or optional - allow values: 'required' or blank
    > * childrens_field: Determine if the field is required or optional - allow values: 'required' or blank
    > * show_childrens: Show or Hide Childrens Dropdown field - allow values: 'yes' or 'no'
  - Example:
      ```[bebetterhotels customer="dummyhotel" adults_field="required" childrens_field="required" show_childrens="yes"]```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[GPLv3](http://www.gnu.org/licenses/gpl-3.0.html)