# Grav reCAPTCHA Contact Plugin

`reCAPTCHA Contact` is a [Grav](http://github.com/getgrav/grav) v0.9.33+ plugin based in the [Simple Contact](https://github.com/nunopress/grav-plugin-simple_contact) plugin from NunoPress LLC that adds a contact form in Grav pages with [Google reCAPTCHA](https://www.google.com/recaptcha/) validation to filter Spam Robots and multilang support. Currently both Spanish (es) and English (en) are supported by default. 

# Installation

Installing the plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## GPM Installation (Currently not available)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install recaptcha-contact

This will install the `reCAPTCHA Contact` plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/recaptcha-contact`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `recaptcha-contact`. You can find these files either on [GetGrav.org](http://getgrav.org/downloads/plugins#extras) or the [reCAPTCHA Contact GitHub repo](https://github.com/aradianoff/recaptcha-contact).

You should now have all the plugin files under

    /your/site/grav/user/plugins/recaptcha-contact

>> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav), the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) plugins, and a theme to be installed in order to operate. It also requires having at least an outgoing mailserver on your server side (to send the emails) and a [reCAPTCHA API key](www.google.com/recaptcha/) for your site.

# Configuration

The plugin comes with some sensible default configuration that you can see in the `recaptcha-contact.yaml` and `languages.yaml` files of the plugin, that are pretty self explanatory:

# Options in `recaptcha-contact.yaml`

    enabled: (true|false)               // Enables or Disables the entire plugin for all pages.
    default_lang: en                    // default_lang in case there is no multilang support in the installation

grecaptcha_sitekey: "your reCAPTCHA site key" // override in your /user/config/plugins/recaptcha-contact.yaml
grecaptcha_secret: "secret-g-recaptcha-key" // override in your /user/config/plugins/recaptcha-contact.yaml and remember not to keep it in a public repository


# Options in `languages.yaml` 

    FORM_LEGEND: "Contact me"                       // Form Legend
    SUBJECT: "New contact from Grav site!"          // Subject for email.
    RECIPIENT: "inesnaya@aradianoff.com"            // Email address.

    FIELDS:                     // Default fields, you can translate the text.
      NAME:
        LABEL: "Name"
        PLACEHOLDER: "Add your name"

      EMAIL:
        LABEL: "Email"
        PLACEHOLDER: "Add your email"

      MESSAGE:
        LABEL: "Message"
        PLACEHOLDER: "Add your message"

      ANTISPAM:
        LABEL: "Antispam"
        PLACEHOLDER: "Please leave this field empty for Antispam"

      SUBMIT:
        LABEL: "Submit"

    MESSAGES:                   // Default messages, you can translate the text.
      SUCCESS: "Thank You! Your message has been sent."
      ERROR: "Oops! There was a problem with your submission. Please complete the form and try again."
      FAIL: "Oops! Something went wrong and we couldn't send your message."

To customize the plugin, you first need to create an override config. To do so, create the folder `user/config/plugins` (if it doesn't exist already) and copy the [recaptcha-contact.yaml](recaptcha-contact.yaml) config file in there and then make your edits.
If you want to add your own translations of the `languages.yaml`variables or modify the existing ones you can do so by creating a `languages`folder in your `user`folder and creating a `.yaml` file for the languages you want (ex. `es.yaml`) adding the above variables to the file and changing them.

# Usage

If you want to add the contact form to a page your can do it by adding to the page header:
    ---
    title: 'My "Page"'

    recaptcha-contact: true
    ---

    # "Lorem ipsum dolor sit amet"

With this method you use the config file and languages file options (either the default ones or your customized ones if they exist. This will add the contact form at the end of the contents of your page. 


Also you can override the default options per-page (currently not working):

    ---
    title: 'My "Page"'

    recaptcha-contact:
      subject: "New contact from your Grav site!"
      recipient: "pippo@example.it"

      fields:
        name:
          label: "Name"
          placeholder: "Add your name"

        email:
          label: "Email"
          placeholder: "Add your email"

        message:
          label: "Message"
          placeholder: "Add your message"

        antispam:
          label: "Antispam"
          placeholder: "Please leave this field empty for Antispam"

        submit:
          label: "Submit"

      messages:
        success: "Thank You! Your message has been sent."
        error: "Oops! There was a problem with your submission. Please complete the form and try again."
        fail: "Oops! Something went wrong and we couldn't send your message."
    ---

    # "Lorem ipsum dolor sit amet"

# Updating

As development for this plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating this plugin is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (Currently not available)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this with this by navigating to the root directory of your Grav install using your system's Terminal (also called command line) and typing the following:

    bin/gpm update recaptcha-contact

This command will check your Grav install to see if your plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit enter. The plugin will automatically update and clear Grav's cache.

## Manual Update

Manually updating this plugin is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/recaptcha-contact` directory.
* Download the new version of the plugin from either [GetGrav.org](http://getgrav.org/downloads/plugins#extras) or the [reCAPTCHA Contact GitHub repo](https://github.com/aradianoff/recaptcha-contact).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `recaptcha-contact`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> Note: Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (for example a YAML settings file placed in `user/config/plugins`) will remain intact.