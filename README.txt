===========================================================
 AlchemySEO: Semantic-powered SEO for PHP-powered Websites
===========================================================

AlchemySEO is a Semantic-powered Search Engine Optimization tool that
improves indexing of your content by search engines such as Yahoo (SearchMonkey)
and Google (Googlebot).

AlchemySEO detects when a search engine is accessing your website, returning
a semantically-marked up version of your content.  Specifically, AlchemySEO 
annotates your web page content with:

 * HTML META "keyword" tags

 * REL TAG Microformats tags

AlchemySEO utilizes the AlchemyAPI text mining and natural language processing
engine to analyze your web page content, identifying key terms & concepts, 
people, companies, locations, and other objects.  By exposing this semantic
meta-data to search engines such as Google and Yahoo, AlchemySEO improves 
your search engine rankings and increases flows of relevant traffic.

AlchemySEO works in conjunction with your PHP-powered website or blog.

For more information on AlchemySEO, visit:

              http://www.alchemyapi.com/tools/alchemyseo/

For more information on AlchemyAPI text mining services, visit:

              http://www.alcheyapi.com/


INSTALLATION

To install this plugin into your PHP-powered website or blog, do the following:

1. Edit AlchemySEO_Config.php, setting your API key and any other desired 
   configuration options:

    vi AlchemySEO/AlchemySEO_Config.php

    NOTE: If you do not have an API key, you may obtain one at:
           
               http://www.alchemyapi.com/api/register.html

2. Copy the "AlchemySEO" directory into webserver content directory:

    cp -R AlchemySEO /path/to/webserver/content/

    Example:  cp -R AlchemySEO /usr/local/apache/htdocs/

3. Edit any PHP pages hosted by your website, inserting the AlchemySEO PHP code into
   each file that you want to utilize semantic markup.

    NOTE: For information on inserting AlchemySEO PHP code, see the "INSERTING PHP CODE"
    section below.

4. That's it!  Your website will now benefit from Semantic-powered tags, microformats,
   and improved indexing by Yahoo, Google, and other search engines.


INSERTING PHP CODE

To enable AlchemySEO on a particular PHP page, you must wrap the page content with the
provided PHP header and footer blocks.  These PHP code blocks facilitate the semantic 
markup of your website content when requests are received from Google, Yahoo, and other 
search engines.

See "example.php" for an example PHP page that has been wrapped with AlchemySEO headers
and footers.

Example "Input" File:

<HTML>
<HEAD>
</HEAD>
<BODY>
Example PHP-powered web page: <?php echo "hello world!\n"; ?>
</BODY>
</HTML>

Example "Wrapped" File (AlchemySEO Enabled):

<?php 
if (!defined("ALCHEMYSEO_PATH"))
{
        define("ALCHEMYSEO_PATH", "../AlchemySEO/");
}
require_once(ALCHEMYSEO_PATH."AlchemySEO_HeaderInc.php");
?>
<HTML>
<HEAD>
</HEAD>
<BODY>
Example PHP-powered web page: <?php echo "hello world!\n"; ?>
</BODY>
</HTML>
<?php 
require_once(ALCHEMYSEO_PATH."AlchemySEO_FooterInc.php");
?>


CONFIGURING ALCHEMYSEO

AlchemySEO provides a number of configuration options that
enable you to customize semantic markup operations.  These
options may be set within the "AlchemySEO_Config.php" file.

Notable configuration options include:

===================
$AlchemySEO_API_Key
===================
	Your AlchemyAPI Access Key
	(Register at: http://www.alchemyapi.com/api/register.html )

===========================
$AlchemySEO_RelTag_Base_URL
===========================
	Your REL-TAG base url
	REL-TAG URLs are formed in the manner: BASE_URL/TAG
 
	Example: BASE_URL == "http://www.test.com/topics";
        	 TAG = "Example Tag"

	Resulting URL: "http://www.test.com/topics/Example+Tag"

============================
$AlchemySEO_Enable_Meta_Tags
============================
	Enable / disable HTML META "keyword" tag generation.

===========================
$AlchemySEO_Enable_Rel_Tags
===========================
	Enable / disable REL TAG Microformats generation.

====================
$AlchemySEO_NLP_Mode
====================
	Tag generation mode
	0 == Named Entities Only
	1 == Concept Tags Only
	2 == Both Named Entities and Concept Tags

=================================
$AlchemySEO_Enable_Disambiguation
=================================
	Enable / disable named entity tag disambiguation
	(See http://www.alchemyapi.com/api/entity/disamb.html )
	This parameter only takes effect when AlchemySEO_NLP_Mode is
	set to "0" (Entities Only) or "2" (Entities and Keywords).

=======================
$AlchemySEO_TestingMode
=======================
	Enable / disable "testing" mode.
	When enabled, Semantic annotation will occur for all
	requests, regardless of User-Agent.

NOTE: A complete listing of all configuration options is available
within AlchemySEO_Config.php


DEPENDENCIES

AlchemySEO requires the following 3rd party components:

   PHP-5


COPYRIGHT AND LICENCE

Copyright (C) 2009-2010 Orchestr8, LLC.
All Rights Reserved.


