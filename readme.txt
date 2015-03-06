=== Ads EZ ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com/ads-ez-pro
Tags: google adsense, adsense, adsense plugin, ads, advertising, income
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 2.00
License: GPL2 or later

Ads EZ is personal ad server with numerous features. It centralizes your banner ads in one location, and provides a modern interface to manage them.

== Description ==

*Ads EZ* is the simplest possible way to serve your ads to multiple web sites.

Do you have multiple websites which you monetize using advertising? If so, this personal ad server may be the right tool for you. *Ads EZ* makes it easy for you to set up an optimized ad server that can service your websites. Aiming at simplicity, Ads EZ does away with all the complicated zone and expiry settings. All you have to do is to collect your banners in a folder, upload them and specify their targets using a simple and intuitive interface.

= Features =

1. Fully Automated Setup: *Ads EZ* gets you started within two minutes, rather than hours and days.
2. Modern Admin Interface: *Ads EZ* sports a modern and beautiful admin interface based on the twitter bootstrap framework. Fully responsive, with editable tables to set up and modify your ads.
3. Admin Interface Tour: A slick tour will take you around the admin page and familiarize you with its features.
4. Generous Help for Admin: Whenever you need help, the information and hint is only a click away in *Ads EZ*. (In fact, it is only a mouseover away.)
5. No Coding Required: *Ads EZ* is written for creative people who have some ads in the form of banners or ad codes. So it doesn't call for any deep computing knowledge. The most you will have to do is perhaps to cut and paste some ad code.
6. Batch Processing: *Ads EZ* makes it a cinch to get your existing banners into your database through an intuitive batch processing.
7. Memory Caching: *Ads EZ* uses memory caching where available so that the database access is minimized. Caching can tremendously improve performance of busy sites.
8. Robust Security: Unbreakable authentication (using hash and salt), impervious to SQL injection etc.
9. Data Validation: Client-side and server-side data validation to minimize data errors.

*Ads EZ* is available as a freely distributed [lite version](http://buy.thulasidas.com/lite/ads-ez-lite.zip "Get Ads EZ Lite") and a [Pro version](http://buy.thulasidas.com/ads-ez-pro "Get Ads EZ Pro for $14.95"), which adds a ton of extra features.

= Pro Features =

If the following features are important to you, consider buying the *Pro* version.

1. *Cache Visualization and Management*: You can see detailed cache statics in the *Pro* version, and options to set the cache expiry, and to manually clear it, if needed.
2. *CDN Support*: Put your static files, such as your banners, on a Content Delivery Network (CDN) for improved performance.
3. *In-Browser Banner Uploads*: The *Pro* version lets you upload multiple banners (through drag and drop) and enter their meta data. In the lite version, you would manually upload the banners to your server and run a batch to do it.
4. *Statistics and Charts*: The *Pro* version can optionally collect statistics about your ad serving and present you with detailed performance reports and charts, including geolocation.
5. *Category Management*: In the *Pro* version, you can have as many ad categories as you want. The lite version comes with three categories, and adding more would require direct database manipulation.
6. *HTML Ads*: The *Pro* version allows you to store and serve HTML and JavaScript ads such as AdSense and other providers. The lite version is limited to banner ads.
7. *OpenX Replacement*: Ads EZ is designed to be drop-in replacement for OpenX. In the *Pro*, you can generate the `.htaccess` directives that will make your Ads EZ app act like your OpenX server to the world.

== Upgrade Notice ==

Adding WordPress plugin version.

== Screenshots ==

1. Database setup screen.
2. Admin user setup screen.
3. Admin interface elements.
4. Listing your banners.
5. Generating invocation codes.
6. Configuration optons, showing help.
7. Editing categories.
8. Admin page tour sample screen.
9. Ad serving statistics and charts in the Pro version.
10. Advanced tools and Cache management in the Pro version.
11. OpenX/Revive replacement tools in the Pro version.
12. Drag-and-drop banners upload in the Pro version.

== Installation ==

1. Upload the contents of the archive `ads-ez-lite` or `ads-ez-pro` to your server.
2. Browse to the admin location of your uploaded the package (`http://yourserver/ads-ez-lite/admin`, for instance) using your web browser.
3. Enter the DB details and set up and Admin account in a couple of minutes and you are done with the installation!

Note that in the second step, your web server will try to create a configuration file where you uploaded the `ads-ez` package. If it cannot do that because of permission issues, you will have to create an empty file `dbCfg.php` and make it writeable. Don't worry, the setup will prompt you for it with detailed instructions.

= To get started with Ad Serving =

1. Upload your banners to the banners folder on your server and hit the Batch Process menu item to provide banner data.
2. Get the invocation codes and place them on your websites to start serving ads.
3. Take a tour to learn the program features.

== Frequently Asked Questions ==

= Why another Ad Server? =

I looked around for a light-weight ad serving solution for my own requirements, and could not find any. So I wrote one myself, as an exercise to learn bootstrap and other fancy modern web technology. I think you will like the result.

= Why would I want to replace my OpenX server? =

Well, I did, which is why I wrote this package. OpenX is a large application, and consequently quite demanding both in terms of the effort needed to maintain it, and on system resources. It may not be appropriate for small scale, personal ad serving, which needs to be simple and quick. If you have only a couple of hunded banners and don't plan on charging for your ads, *Ads EZ* may be the right solution for you. Having said that, I will develop a paid advertising module for *Ads EZ* if there is enough demand for it.

== Change Log ==

= History =

* V2.00: Adding WordPress plugin version. [Jan 2, 2015]
* V1.80: Major improvements to statistics and charting in the Pro version. [Dec 7, 2014]
* V1.71: Fixing the tour to handle accordion menus. [Nov 14, 2014]
* V1.70: Accordion menu implementation for a cleaner interface. [Nov 13, 2014]
* V1.61: Misc bug fixes. [Nov 11, 2014]
* V1.60: Sortable and paginated tables using dataTables. [Nov 11, 2014]
* V1.51: More misc cleanup and validation checks. [Nov 10, 2014]
* V1.50: Numerous misc changes. Ads EZ Pro is now feature-complete. [Nov 8, 2014]
* V1.40: Implemented stats, geolocation and charts. [Nov 6, 2014]
* V1.30: Implemented OpenX/Revive integration in the Pro version. [Nov 4, 2014]
* V1.20: Implemented new category creation in the Pro version. [Nov 1, 2014]
* V1.10: Implemented batch upload of banners and HTML Ads in the Pro version. [Oct 31, 2014]
* V1.00: Initial Public Release. [Oct 29, 2014]