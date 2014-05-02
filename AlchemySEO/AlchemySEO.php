<?php

require_once(ALCHEMYSEO_PATH."AlchemyAPI.php");
require_once(ALCHEMYSEO_PATH."AlchemyAPIParams.php");
require_once(ALCHEMYSEO_PATH."AlchemySEO_Config.php");


function AlchemyAPI_ParseEntityResponse($result)
{
	global $AlchemySEO_Enable_Disambiguation;

	$doc = simplexml_load_string($result);

	$entities = $doc->xpath("//entity");

	$resultArr = array();

	foreach ($entities as $key => $value)
	{
        	$typeArr = $doc->xpath("/results/entities/entity[$key+1]/type");
	        $textArr = $doc->xpath("/results/entities/entity[$key+1]/text");

	        if (count($typeArr) > 0 && count($textArr) > 0 && strlen($typeArr[0]) > 0)
        	{
	                $type = "$typeArr[0]";
        	        $text = "$textArr[0]";

	                $disambArr = $doc->xpath("/results/entities/entity[$key+1]/disambiguated/name");

        	        if (count($disambArr) > 0 && strlen($disambArr[0]) > 0)
                	{
				if ($AlchemySEO_Enable_Disambiguation == true)
				{
		                        $text = "$disambArr[0]";
				}
	                }

        	        if (!array_key_exists($type, $resultArr))
	                {
        	                $resultArr[$type] = array();
                	}

	                if (!in_array($text, $resultArr[$type]))
	                {
        	                $resultArr[$type][] = $text;
                	}
	        }
	}
	return $resultArr;
}

function AlchemyAPI_ParseConceptResponse($result)
{
	$doc = simplexml_load_string($result);

	$concepts = $doc->xpath("/results/concepts/concept");

	$resultArr = array();

	foreach ($concepts as $key => $value)
	{
			$textArr = $doc->xpath("/results/concepts/concept[$key+1]/text");
        	$text = $textArr[0];
			$type = "concept";
			 if (!array_key_exists($type, $resultArr))
	         {
        	       $resultArr[$type] = array();
             }

	         if (!in_array($text, $resultArr[$type]))
	         {
        	        $resultArr[$type][] = $text;
             }
	}
	
	return $resultArr;
}

function AlchemySEO_CheckForSearchEngine()
{
	global $AlchemySEO_SearchEngine_UserAgents;

	if (!isset($_SERVER['HTTP_USER_AGENT']))
	{
		return false;
	}

	$userAgent = $_SERVER['HTTP_USER_AGENT'];

	foreach ($AlchemySEO_SearchEngine_UserAgents as $key => $value)
	{
		$offset = strpos($userAgent, $value, 0);
		if ($offset > 0 && $offset != false)
		{
			return true;
		}
	}

	return false;
}

function AlchemySEO_FindPageMetaTagData($pageContents)
{
	$byteOffset = 0;
	while ($byteOffset < strlen($pageContents))
	{
		$byteOffsetD = stripos($pageContents, "<meta ", $byteOffset);
		$byteOffsetC = stripos($pageContents, "<!--", $byteOffset);

		if ($byteOffsetD == false)
		{
			return -1;
		}
		else if ($byteOffsetC < $byteOffsetD && $byteOffsetC != false)
		{
			$byteOffset = stripos($pageContents, "-->", $byteOffsetC);
		}
		else if ($byteOffsetD != false)
		{
			$byteOffsetE = stripos($pageContents, ">", $byteOffsetD);

			$byteOffsetKA = stripos($pageContents, "=\"keywords\"", $byteOffsetD);
			$byteOffsetKB = stripos($pageContents, "='keywords'", $byteOffsetD);

			if (($byteOffsetKA < $byteOffsetE && $byteOffsetKA != false) ||
			    ($byteOffsetKB < $byteOffsetE && $byteOffsetKB != false))
			{
				$byteOffsetCA = stripos($pageContents, "content=\"", $byteOffsetD);
				$byteOffsetCB = stripos($pageContents, "content='", $byteOffsetD);

				if (($byteOffsetKA < $byteOffsetE && $byteOffsetCA != false) ||
				    ($byteOffsetKB < $byteOffsetE && $byteOffsetCB != false))
				{
					$cStartA = $byteOffsetCA + strlen("content=\"");
					$cStartB = $byteOffsetCA + strlen("content=\"");

					if ($cStartA < $cStartB)
					{
						return $cStartA;
					}
					else
					{
						return $cStartB;
					}
				}
				else
				{
					return -1;
				}
			}
			else
			{
				return -1;
			}
		}
	}

	return -1;
}

function AlchemySEO_FindPageData($pageContents, $pageData, $startOffset)
{
	$byteOffset = $startOffset;
	while ($byteOffset < strlen($pageContents))
	{
		$byteOffsetD = stripos($pageContents, $pageData, $byteOffset);
		$byteOffsetC = stripos($pageContents, "<!--", $byteOffset);

		if ($byteOffsetD == false)
		{
			return -1;
		}
		else if ($byteOffsetC < $byteOffsetD && $byteOffsetC != false)
		{
			$byteOffset = stripos($pageContents, "-->", $byteOffsetC);
		}
		else
		{
			return $byteOffsetD;
		}
	}

	return -1;
}

function AlchemySEO_BuildKeywordList($resultArr)
{
	$keywordList = "";

	foreach ($resultArr as $typeKey => $typeVal)
	{
		foreach ($typeVal as $valuesKey => $valuesVal)
		{
			if (strlen($valuesVal) > 0)
			{
				if (strlen($keywordList) > 0)
				{
					$keywordList = $keywordList.", ";
				}

				$keywordList = $keywordList.$valuesVal;
			}
		}
	}

	return $keywordList;
}

function AlchemySEO_BuildRelTagList($resultArr)
{
	global $AlchemySEO_RelTag_Base_URL;

	$relTagList = "Tags: ";

	foreach ($resultArr as $typeKey => $typeVal)
	{
		foreach ($typeVal as $valuesKey => $valuesVal)
		{
			if (strlen($valuesVal) > 0)
			{
				$relTagList = $relTagList." ";

				$relTagList = $relTagList."<a href=\"".$AlchemySEO_RelTag_Base_URL;

				if (strlen($AlchemySEO_RelTag_Base_URL) > 0)
				{
					if (substr($AlchemySEO_RelTag_Base_URL, strlen($AlchemySEO_RelTag_Base_URL) - 1) != "/")
					{
						$relTagList = $relTagList."/";
					}
				}
				else
				{
					$relTagList = $relTagList."/";
				}

				$relTagList = $relTagList.urlencode($valuesVal)."\" rel=\"tag\">".$valuesVal."</a>";
			}
		}
	}
	$relTagList = $relTagList."<br/><br/>";

	return $relTagList;
}

function AlchemySEO_RewriteMetaTags($pageContents, $resultArr)
{
	$metaOffset = AlchemySEO_FindPageMetaTagData($pageContents);

	if ($metaOffset == (-1))
	{
		$headOffset = AlchemySEO_FindPageData($pageContents, "</head>", 0);

		if ($headOffset == (-1))
		{
			$bodyOffset = AlchemySEO_FindPageData($pageContents, "<body", 0);

			if ($bodyOffset == (-1))
			{
				// do nothing
			}
			else
			{
				$insertBefore = substr($pageContents, 0, $bodyOffset);
				$insertAfter = substr($pageContents, $bodyOffset);

				$keywordList = AlchemySEO_BuildKeywordList($resultArr);

				$insertText = "<HEAD><META name=\"keywords\" content=\"".$keywordList."\"></HEAD>";

				$pageContents = $insertBefore.$insertText.$insertAfter;
			}
		}
		else
		{
			$insertBefore = substr($pageContents, 0, $headOffset);
			$insertAfter = substr($pageContents, $headOffset);

			$keywordList = AlchemySEO_BuildKeywordList($resultArr);

			$insertText = "<META name=\"keywords\" content=\"".$keywordList."\">\n";

			$pageContents = $insertBefore.$insertText.$insertAfter;
		}
	}
	else
	{
		$insertBefore = substr($pageContents, 0, $metaOffset);
		$insertAfter = substr($pageContents, $metaOffset);

		$keywordList = AlchemySEO_BuildKeywordList($resultArr);

		$byteOffsetE = stripos($pageContents, ">", $metaOffset);
		$byteOffsetEB = stripos($pageContents, "\"", $metaOffset);
		if ($byteOffsetEB < $byteOffsetE && $byteOffsetEB != false)
		{
			$byteOffsetE = $byteOffsetEB;
		}

		if ($byteOffsetE != $metaOffset)
		{
			$keywordList = $keywordList.", ";
		}

		$pageContents = $insertBefore.$keywordList.$insertAfter;
	}

	return $pageContents;	
}

function AlchemySEO_InsertRelTags($pageContents, $resultArr)
{
	$bodyOffset = AlchemySEO_FindPageData($pageContents, "<body", 0);

	if ($bodyOffset == (-1))
	{
		return $pageContents;
	}

	$bodyOffsetE = AlchemySEO_FindPageData($pageContents, ">", $bodyOffset);

	if ($bodyOffsetE == (-1))
	{
		return $pageContents;
	}

	$insertOffset = $bodyOffsetE + 1;

	$insertBefore = substr($pageContents, 0, $insertOffset);
	$insertAfter = substr($pageContents, $insertOffset);

	$insertText = AlchemySEO_BuildRelTagList($resultArr);

	$pageContents = $insertBefore.$insertText.$insertAfter;

	return $pageContents;
}

function AlchemySEO_UpdatePageWithEntities($pageContents, $resultArr)
{
	global $AlchemySEO_Enable_Meta_Tags, $AlchemySEO_Enable_Rel_Tags;

	$newPage = $pageContents;

	if ($AlchemySEO_Enable_Meta_Tags == true)
	{
		$newPage = AlchemySEO_RewriteMetaTags($newPage, $resultArr);
	}

	if ($AlchemySEO_Enable_Rel_Tags == true)
	{
		$newPage = AlchemySEO_InsertRelTags($newPage, $resultArr);
	}

	echo $newPage;
	

	return true;
}

function AlchemySEO_GetPageURL()
{
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
	return $url;
}

function AlchemySEO_RewritePageContents($pageContents)
{
	global $AlchemySEO_API_Key;
	global $AlchemySEO_NLP_Mode;
	//global $AlchemySEO_Keyword_Strict;

        if (strlen($AlchemySEO_API_Key) < 1)
        {
		echo $pageContents;
                return false;
        }

        $alchemyObj = new AlchemyAPI();
		$conceptParams = new AlchemyAPI_ConceptParams();

        $alchemyObj->setAPIKey($AlchemySEO_API_Key);
		
		//if( "true" == $AlchemySEO_Keyword_Strict )
		//	$keywordParams->setKeywordExtractMode("strict");

	    $result = "";
		$resultArr = array();
		
		if( $AlchemySEO_NLP_Mode != 0 ) {
			try
			{
                $result = $alchemyObj->HTMLGetRankedConcepts($pageContents, AlchemySEO_GetPageURL(),"xml");
			}
			catch (Exception $e)
			{
				echo $pageContents;
                return false;
			}
			$resultArr = AlchemyAPI_ParseConceptResponse($result);
			
		}
		
		if( $AlchemySEO_NLP_Mode != 1 ) {
			try
			{
                $result = $alchemyObj->HTMLGetRankedNamedEntities($pageContents, AlchemySEO_GetPageURL());
			}
			catch (Exception $e)
			{
			echo $pageContents;
                return false;
			}
			$resultArr2 = AlchemyAPI_ParseEntityResponse($result);
			$resultArr = array_merge($resultArr, $resultArr2);
		}
		
		
		
		

	if (count($resultArr) < 1)
	{
		echo $pageContents;
		return true;
	}

	return AlchemySEO_UpdatePageWithEntities($pageContents, $resultArr);
}

function AlchemySEO_HandleRewrite_PageStart()
{
	global $AlchemySEO_TestingMode;

	if ((AlchemySEO_CheckForSearchEngine() == true) ||
	    ($AlchemySEO_TestingMode == true))
	{
		ob_start();
	}
}

function AlchemySEO_HandleRewrite_PageEnd()
{
	global $AlchemySEO_TestingMode;

	if ((AlchemySEO_CheckForSearchEngine() == true) ||
	    ($AlchemySEO_TestingMode == true))
	{
		$pageContents = ob_get_contents();
		
		ob_end_clean();

		if (AlchemySEO_RewritePageContents($pageContents) == false)
		{
			$pageUrl = AlchemySEO_GetPageURL();
			syslog(LOG_ERR, "AlchemySEO: Failure rewriting page: ".$pageUrl."");
		}
		
	}
	else
	{
		$pageContents = ob_get_contents();

		ob_end_clean();

		echo $pageContents;
	}
}

?>
