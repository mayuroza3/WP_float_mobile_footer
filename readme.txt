=== Float Mobile Footer ===
Contributors: mayuroza3
Tags: floating footer, mobile footer, call button, whatsapp button, sticky mobile footer
Requires at least: 5.6
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight, modern floating mobile footer to boost engagement and conversions on mobile screens with Call, WhatsApp, Email, and Custom Links.

== Description ==

Float Mobile Footer is a customizable and highly optimized sticky footer strictly designed for mobile devices. It provides an immediate and responsive way for your visitors to reach out via Call, WhatsApp, Email, or an external Link, boosting your conversion rates natively on smaller screens. 

Built with modern WordPress coding standards in mind (OOP architecture, native Settings API, no bloatware), this plugin securely hooks into your theme's footer without overlapping your content or destroying your layouts.

= Core Features =
* **Fully Responsive & Mobile First:** Displays perfectly fixed on mobile devices while neatly hiding on large screens using CSS.
* **4 Dedicated Action Buttons:** Configure Phone Number, WhatsApp Number, Email Address, and Custom Links securely.
* **Intelligent Loading:** No frontend scripts or styles are loaded until you specifically enable the footer, keeping your site fast.
* **Fully Customizable Colors:** Match the floating bar to your brand directly from the WordPress live preview Customizer section in the backend. 
* **Lightweight & Clean:** Uses native WordPress Dashicons to avoid requiring external font icon libraries.
* **Translation Ready:** Fully prepped for your local language strings.
* **Zero Bloat / No Ads:** No tracking scripts, no spam notifications, just a clean floating mobile footer.


== Installation ==

1. Upload the entire `float-mobile-footer` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to **Settings > Mobile Footer** to configure your buttons and color schemes.
4. Toggle the Enable switch and click **Save Changes** to instantly output the floating bar.

== Frequently Asked Questions ==

= Does it hide on Desktop browsers automatically? =

Yes! A reliable CSS media query handles the mobile visibility natively right in the browser, meaning the footer intelligently hides itself to avoid frustrating desktop users without requiring slow server-side detection rules.

= Can I use fewer than 4 buttons? =

Absolutely! Leave the inputs blank for any buttons you don't wish to render. The width of the deployed buttons cleanly redistributes across the space organically.

= Does this conflict with page builders? =

Float Mobile Footer uses standardized WordPress hooks (`wp_footer`) allowing it to bypass page builder specifics and render perfectly fixed on the base window structure.

== Screenshots ==

1. screenshot-1.png - The Admin Settings Page featuring color pickers and a fully functional live mobile preview interface.
2. screenshot-2.png - Sample appearance of the floating mobile footer on testing devices.

== Changelog ==

= 2.0.0 =
* Major release: Refactored entirely into Modern Object-Oriented PHP Architecture.
* Fixed: Removed usage of global variables and insecure form POST data.
* Fixed: Removed all email tracking pings associated with older legacy versions.
* Improved: Completely overhauled Backend Admin interface utilizing Settings API.
* Improved: Better dynamic button distribution (using CSS flexbox).
* Security: Implemented rigorous escaping, data sanitization, nonces, and `current_user_can` validation over all options.

= 1.0.0 =
* Initial legacy release.
