

# Enhanced E-Commerce Tracking in Google Analytics by VanStudio

# VERSION 2.2.1 (22.12.17)

If you need support, modification or customization, please contact us by below details.

E-mail:  info@vanstudio.co.ua

Site: https://vanstudio.co.ua

# INSTALLATION
-------------------------------------------------------------------------

STEP 1 - Create Your Google Analytics Account

    If you’re already a Google Analytics User, skip ahead to STEP 2.
    If you don’t already have a Google Analytics account, you can sign up for a free account here -> http://www.google.ca/analytics.

STEP 2 - Set up Analytics tracking

    Add basic page tracking (analytics.js/gtag.js) to your website.
    If you’re already a basic page tracking, skip ahead to STEP 3.
    If you have not already added basic page tracking find the tracking code snippet for your property.
    2.1 Sign in to your Google Analytics account, and select the Admin tab.
    From the Account and Property columns, select the property you’re working with. Click Tracking Info > Tracking Code.
    2.2 Find your tracking code snippet. It's in a box with several lines of JavaScript code in it.
    Everything in this box is your tracking code snippet. It starts with <script> and ends with </script>.
    2.3 Copy and paste the snippet to the admin panel of your site:
    - in OpenCart 2.0.0.0-2.0.1.1 go to System > Settings > Server Teb > Google Analytics Code;
    - in OpenCart 2.0.2.0-2.0.3.1 go to System > Settings > Google Teb > Google Analytics Code;
    - in OpenCart 2.1.0.1-2.2.0.0 go to Extensions > Analytics > Google Analytics;
    - in OpenCart 2.3.0.0 and above go to Extensions > Extensions > Analytics > Google Analytics.
    Link to source -> https://support.google.com/analytics/answer/1008080.

Do one of the following Step 3

STEP 3 - Upload the module's files (If you use Extension Installer + OCMOD)

    Note: You must have set your FTP credentials to use this type of installation in OpenCart version 2.0.1.0-2.3.0.2

    Go to store administration menu "Extensions > Installer" and upload the archive "ee-tracking-vX.X-ocXXXX.ocmod.zip".

STEP 3 - Upload the module's files (If you use VQMOD)

    Note: You must have installed VQMOD to use this type of installation

    Upload the files and folders of extension (admin, catalog and vqmod folders) from the archive
    "ee-tracking-vX.X-ocXXXX.vqmod.zip" to your OpenCart server's main (root) directory by FTP client or by file manager.

STEP 3 - Upload the module's files (If you use OpenCart Marketplace in OpenCart version 3.0.0.0 and above)

    Note: You must have set your OpenCart API information to use this type of installation

    Install "CLOUD EE-Commerce Tracking vX.X OCXXXX" in the download tab of marketplace extension page.

STEP 4 - Install the module

    Note: In OpenCart version 1.5.4 - 2.2.0.0 the administration menu will look like "Extensions > Modules" instead
    "Extensions > Extensions > Modules"

    Go to store administration menu "Extensions > Extensions > Modules" and click Install "Enhanced E-Commerce Tracking
    by VanStudio" module.

    Note: If you use OCMOD, the extension automatically refresh the Modification List after this step

STEP 5 - Set the module settings

    5.1 Go to "Extensions > Extensions > Modules > Enhanced E-Commerce Tracking" and click Edit.
    5.2 Set Global Status to enabled.
    5.3 Enter your Google Analytics Tracking ID.
    5.4 Enter your Order ID from opencart.com or vanstudio.co.ua.
    5.5 Set Status to enabled for data types and actions which you want to collect (Impression, Click, Details View,
    Cart, Checkout, Transaction, Refund, Internal Promotion, Custom Dimension). Click Bulk Change > Status for
    change Status of all types and actions.
    5.6 Go to Transaction tab and select Order Status. The data will be sent to Google Analytics when an order get
    selected status (Complete and Pending by default).
    5.7 Go to Refund tab and select Order Status. The data will be sent to Google Analytics when an order get
    selected status (Refunded by default).
    5.8 (optional) If you use some module for quick checkout, go to Checkout tab, set Custom Checkout Page to enabled and enter Checkout Page URL.
    5.9 Click Save.

STEP 6 - Turn on Enhanced Ecommerce for a view in your Google Analytics account

    6.1 Sign in to your Google Analytics account here -> https://www.google.com/analytics/web/#home.
    6.2 Select the Admin tab and Navigate to the desired account, property and view -> https://support.google.com/analytics/answer/6099198.
    6.3 In the VIEW column, select Ecommerce Settings.
    6.4 Click the Enable Ecommerce toggle ON.
    6.5 Click the Enable Related Products toggle ON (optional).
    6.6 Click Next step.
    6.7 Enhanced Ecommerce Settings, set the status to ON.
    6.8 Enter labels for the checkout steps (click a funnel step, enter a label name, then click Done).
    Steps for the default OpenCart checkout:
        step 1 - Checkout Type
        step 2 - Payment Address
        step 3 - Shipping Address
        step 4 - Shipping Method
        step 5 - Payment Method
        step 6 - Confirm Order
    If you use some module for quick checkout, like Ajax Quick Checkout, where all checkout steps display
    at the same time, add one step only:
        step 1 – Checkout
    6.9 Click Submit.
    Link to source -> https://support.google.com/analytics/answer/6032539.

    Note: It may take 24 hours for data to appear in your reports once tracking has been installed

STEP 7 - Only for OpenCart 3.0.1.1 and above

    Note: OpenCart version 3.0.1.1 and above include template cache and you need to refresh it after installing or
    updating Enhanced E-Commerce Tracking module

    Go to store administration menu "Dashboard" and click blue button with gear icon on top right of page.
    Then click refresh the cache of theme in the Developer Settings popup window.

That's it!

# UPDATE
-------------------------------------------------------------------------

STEP 1 - Uninstall the module

    Note: In OpenCart version 1.5.4 - 2.2.0.0 the administration menu will look like "Extensions > Modules" instead
    "Extensions > Extensions > Modules"

    Go to store administration menu "Extensions > Extensions > Modules" and uninstall the module "Enhanced E-Commerce
    Tracking by VanStudio".

Do one of the following Step 2

STEP 2 - Update the module's files (If you use Extension Installer + OCMOD)

    Go to store administration menu "Extensions > Modifications" and remove the modification "Enhanced E-Commerce Tracking".
    Go to store administration menu "Extensions > Installer" and upload the archive "ee-tracking-vX.X-ocXXXX.ocmod.zip"
    with files overwriting.

STEP 2 - Update the module's files (If you use VQMOD)

    Upload the files and folders of extension (admin, catalog and vqmod folders) from the archive
    "ee-tracking-vX.X-ocXXXX.vqmod.zip" with files overwriting to your OpenCart server's main (root) directory
    by FTP client or by file manager.

STEP 2 - Update the module's files (If you use OpenCart Marketplace in OpenCart version 3.0.0.0 and above)

    Go to store administration menu "Extensions > Installer" and uninstall (remove) the "ee-tracking-vXX-ocXXXX.ocmod.zip" archive.
    Go to store administration menu "Extensions > Marketplace" and install "CLOUD EE-Commerce Tracking vX.X OCXXXX"
    in the download tab of marketplace extension page.

STEP 3 - Install the module

    Note: If you use OCMOD, the extension automatically refresh the Modification List after this step

    Go to store administration menu "Extensions > Extensions > Modules" and click Install "Enhanced E-Commerce Tracking
    by VanStudio" module.

STEP 4 - Set the module settings

    4.1 Go to "Extensions > Extensions > Modules > Enhanced E-Commerce Tracking" and click Edit.
    4.2 Set Global Status to enabled.
    4.3 Enter your Google Analytics Tracking ID.
    4.4 Enter your Order ID from opencart.com or vanstudio.co.ua.
    4.5 Set Status to enabled for data types and actions which you want to collect (Impression, Click, Details View,
    Cart, Checkout, Transaction, Refund, Internal Promotion, Custom Dimension). Click Bulk Change > Status for
    change Status of all types and actions.
    4.6 Go to Transaction tab and select Order Status. The data will be sent to Google Analytics when an order get
    selected status (Complete and Pending by default).
    4.7 Go to Refund tab and select Order Status. The data will be sent to Google Analytics when an order get
    selected status (Refunded by default).
    4.8 (optional) If you use some module for quick checkout, go to Checkout tab, set Custom Checkout Page to enabled and enter Checkout Page URL.
    4.9 Click Save.

STEP 5 - Only for OpenCart 3.0.1.1 and above

    Note: OpenCart version 3.0.1.1 and above include template cache and you need to refresh it after installing or
    updating Enhanced E-Commerce Tracking module

    Go to store administration menu "Dashboard" and click blue button with gear icon on top right of page.
    Then click refresh the cache of theme in the Developer Settings popup window.

That's it!

Instruction video:
- Installation Enhanced E-Commerce Tracking OpenCart Module - https://youtu.be/z_C_t6rGJB4
- How to install extension in OpenCart 2.x by Extension Installer - https://youtu.be/GVCplXqoi64
- How to install extension in OpenCart using VQMOD - https://youtu.be/3xQ_DitXfaE