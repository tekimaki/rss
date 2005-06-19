<?php

// get default rss feed version from database or set to 1.0 if none in there
$rss_version = $gBitSystem->getPreference("rssfeed_default_version",1);

// override version if set as request parameter
if (isset($_REQUEST["ver"])) {
	if (substr($_REQUEST["ver"],0,1) == '2') {
		$rss_version = 2;
	} else {
		$rss_version = 1;
	}
}

$feed = substr( md5( $_SERVER['REQUEST_URI'] ), 0, 30 );

$query = "select * from `".BIT_DB_PREFIX."tiki_rss_feeds` where `name`=? and `rss_ver`=?";
$bindvars=array($feed, $rss_version);
$result = $gBitSystem->query($query, $bindvars);

$changes = "";
$output = "EMPTY";

if (!$result->numRows())
{
  // nothing found, then insert row for this feed+rss_ver
  $now = date("U");
  $query = "insert into `".BIT_DB_PREFIX."tiki_rss_feeds`(`name`,`rss_ver`,`refresh`,`last_updated`,`cache`) values(?,?,?,?,?)";

  // default value for cache timeout is 300 (5 minutes)
  $bindvars=array($feed,(int) $rss_version,(int) 300 ,(int) $now, $output);
  $result = $gBitSystem->query($query, $bindvars);
}
else
{
  // entry found in db:
  $res = $result->fetchRow();
  $output = $res["cache"];
  $refresh = $res["refresh"];
  $last_updated = $res["last_updated"];
  // up to date? if not, then set trigger to reload data:
  if ( TRUE || $last_updated + $refresh < $now) { $output="EMPTY"; } // TODO: make timeout configurable (is 7 minutes now)
}

?>
