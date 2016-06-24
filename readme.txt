=== WordPress .htpasswd Generator ===
Contributors: andreacomo
Tags: security, basic authentication, htaccess, htpasswd
Requires at least: 4.4.2
Tested up to: 4.4.2
Stable tag: 1.1.2
License: MIT
License URI: https://opensource.org/licenses/MIT

Sync your WordPress users with .htpasswd file for enabling Apache (2.2.18 and above) basic authentication based on file for your resources

== Description ==
Want to protect resources in a folder only to registered users in your WordPress installation? This plugin sync any WP users with Apache basic authentication system file-based automagically: accessing protected resources will prompt for WordPress account credentials

== Installation ==
1. Upload *plugin folder* to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** menu in WordPress.
1. Go to *Htpasswd Generator* settings page under WordPress *Settings* menu
1. Fill *Generic Settings* section to provide resource paths to protect. Remember that resource folders must alredy exist
1. Now Apache will ask for basic authentication while trying to access that resources

== Advanced options ==
If you want to protect resources with WordPress credential on another server, you can provide FTP credentials:
this plugin will take care to copy `.htpasswd_generated` to remote server. It's up to you then to configure Apache `.htaccess` file propertly.

== Frequently Asked Questions ==
= How WordPress users are synchronized with *.htpasswd_generated* file? =
This plugin [hooks to some WordPress Actions](https://codex.wordpress.org/Plugin_API/Action_Reference) involving user's **creation**, **update** or **deletion**, such as:

* *user_register*
* *profile_update*
* *delete_user*
* *password_reset*

= After plugin activation, only new users or updated users can access protected resources, why? =
WP user's passwords are hashed before storing on db, so they cannot be reverted to plain text for security reason. Apache basic authentication based on file expects a different type of password hashing, so this plugin cannot use WP hashed version but has to catch and hash user's password before they are hashed from WP. Unfortunately, the only way to sync `.htpasswd_generated` with existing users is to **update their password** (even with same value) from admin panel or from **reset password** functionality so it can be caught and hashed when still plain text.

= Is it opensource? =
You can fork, edit and pull request sources from [GitHub](https://github.com/andreacomo/wp-htpasswd-generator)

== Screenshots ==

1. Htpasswd Generator settings page

== Changelog ==

= 1.1.2 =

* No new feature. Just needed to meet WordPress update system

= 1.1.1 =

* Fixed issue #1: switched encryption algorithm to *APR1-MD5*, compatible with **Apache 2.2.18 and above**, both on Linux and Windows

= 1.1.0 =

* **Automatic folder protection**: now in new settings page you can specify folders path you want to protect: **no more need** to move and rename `rename_me_to_.htaccess` manually! 
* **New FTP upload feature**: if you need to protect a remote resource (on another server than WordPress installation), you can upload `.htpasswd_generated` automatically via FTP to another server. Remember to configure `.htaccess` file properly on remote server.

