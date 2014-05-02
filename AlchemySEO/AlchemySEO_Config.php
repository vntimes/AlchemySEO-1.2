<?php
// AlchemySEO Configuration File
// See README.txt for more information on AlchemySEO.

// Your AlchemyAPI Access Key
// (Register at: http://www.alchemyapi.com/api/register.html )
$AlchemySEO_API_Key = "";


// Your REL-TAG base url
// REL-TAG URLs are formed in the manner: BASE_URL/TAG
//
// Example: BASE_URL == "http://www.test.com/topics";
//          TAG = "Example Tag"
//
// Resulting URL: "http://www.test.com/topics/Example+Tag"
$AlchemySEO_RelTag_Base_URL = "";


// Enable / disable HTML META "keyword" tag generation.
$AlchemySEO_Enable_Meta_Tags = true;


// Enable / disable REL TAG Microformats generation.
$AlchemySEO_Enable_Rel_Tags = true;


// Enable / disable tag disambiguation
// (See http://www.alchemyapi.com/api/entity/disamb.html )
// Only takes effect when AlchemySEO_NLP_Mode is set to
// "0" (entities only) or "2" (entities and concept tags).
$AlchemySEO_Enable_Disambiguation = true;

// Enables use of named entities, concept tags, or both
// 0 = named entities only
// 1 = concept tags only
// 2 = both entities and concept tags
$AlchemySEO_NLP_Mode = 2;


// Search Engine User-Agents to trigger on.
// Semantic annotation will only occur for requests 
// matching one of these User-Agents.
$AlchemySEO_SearchEngine_UserAgents = array("Googlebot",
					    "Slurp",
					    "ia_archiver",
					    "Scooter",
					    "Mercator",
					    "AltaVista",
					    "WebCrawler");


// Enable / disable "testing" mode.
// When enabled, Semantic annotation will occur for all
// requests, regardless of User-Agent.
$AlchemySEO_TestingMode = false;

?>
