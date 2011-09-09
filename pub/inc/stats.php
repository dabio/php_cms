<?php 
// Turn on error-reporting
// ERROR_REPORTING(E_ALL);
// $result = ini_set('display_errors', true);

!empty($_GET) ? $get = $_GET : exit();

require('../../config/database.php');

$prefix     = 'stats_';

$referrer_id   = false;    $platform_id   = false;
$language_id   = false;    $cookie_id     = false;
$java_id       = false;    $resolution_id = false;
$document_id   = false;    $browser_id    = false;

$link = mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
mysql_select_db($db['db'], $link) or die(mysql_error());

// referrers muessen noch ueberarbeitet werden, da auch eigene angezeigt werden
// referrers
if ($get['referrer'])
{
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "referrers` WHERE url = '". $get['referrer'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "referrers` SET url = '". $get['referrer'] ."'", $link) or die(mysql_error());
      $referrer_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "referrers` SET hits = ". $row['hits'] ." WHERE url = '". $get['referrer'] ."'", $link) or die(mysql_error());
      $referrer_id = $row['id'];
   }
}

// platforms
if ($get['browser'])
{
   $get['platform'] = getPlatform($get['browser']);
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "platforms` WHERE name = '". $get['platform'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "platforms` SET name = '". $get['platform'] ."'", $link) or die(mysql_error());
      $platform_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "platforms` SET hits = ". $row['hits'] ." WHERE name = '". $get['platform'] ."'", $link) or die(mysql_error());
      $platform_id = $row['id'];
   }
}

// languages
if ($get['language'])
{
   (strlen($get['language']) > 2) ? $get['language'] = substr($get['language'], -2) : $get['language'] = $get['language'];
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "languages` WHERE string = '". $get['language'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "languages` SET string = '". $get['language'] ."'", $link) or die(mysql_error());
      $language_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "languages` SET hits = ". $row['hits'] ." WHERE string = '". $get['language'] ."'", $link) or die(mysql_error());
      $language_id = $row['id'];
   }
}

// cookies
if ($get['cookie'])
{
   $get['cookie'] = isTrue($get['cookie']);
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "cookies` WHERE enabled = '". $get['cookie'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "cookies` SET enabled = '". $get['cookie'] ."'", $link) or die(mysql_error());
      $cookie_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "cookies` SET hits = ". $row['hits'] ." WHERE enabled = '". $get['cookie'] ."'", $link) or die(mysql_error());
      $cookie_id = $row['id'];
   }
}

// java
if ($get['java'])
{
   $get['java'] = isTrue($get['java']);
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "java` WHERE enabled = '". $get['java'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "java` SET enabled = '". $get['java'] ."'", $link) or die(mysql_error());
      $java_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "java` SET hits = ". $row['hits'] ." WHERE enabled = '". $get['java'] ."'", $link) or die(mysql_error());
      $java_id = $row['id'];
   }
}

// resolutions
if ($get['resolution'])
{
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "resolutions` WHERE string = '". $get['resolution'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "resolutions` SET string = '". $get['resolution'] ."'", $link) or die(mysql_error());
      $resolution_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "resolutions` SET hits = ". $row['hits'] ." WHERE string = '". $get['resolution'] ."'", $link) or die(mysql_error());
      $resolution_id = $row['id'];
   }
}

// documents
if ($get['document'])
{
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "documents` WHERE title = '". $get['document_title'] ."' AND url = '". $get['document'] ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "documents` SET title = '". $get['document_title'] ."', url = '". $get['document'] ."'", $link) or die(mysql_error());
      $document_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "documents` SET hits = ". $row['hits'] ." WHERE title = '". $get['document_title'] ."' AND url = '". $get['document'] ."'", $link) or die(mysql_error());
      $document_id = $row['id'];
   }
}

// browsers
if ($get['browser'])
{
   list($name, $version) = getBrowser($get['browser']);
   $result = mysql_query("SELECT id, hits FROM `" .$prefix. "browsers` WHERE name = '". $name ."' AND version = '". $version ."'", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "browsers` SET name = '". $name ."', version = '". $version ."'", $link) or die(mysql_error());
      $browser_id = mysql_insert_id();
   } else
   {
      $row['hits']++;
      $result = mysql_query("UPDATE `" .$prefix. "browsers` SET hits = ". $row['hits'] ." WHERE name = '". $name ."' AND version = '". $version ."'", $link) or die(mysql_error());
      $browser_id = $row['id'];
   }

}

// visits
if ($get['time'])
{
   $result = mysql_query("SELECT id, hits, timestamp FROM `" .$prefix. "visits` WHERE browser_id = '$browser_id' AND platform_id = '$platform_id' AND resolution_id = '$resolution_id' AND cookie_id = '$cookie_id' AND language_id = '$language_id' AND java_id = '$java_id' AND ip = '" .$get['ip'] ."' ORDER BY timestamp DESC LIMIT 1", $link) or die(mysql_error());
   $row = mysql_fetch_assoc($result);

   if (!$row)
   {
      $result = mysql_query("INSERT INTO `" .$prefix. "visits` SET ip = '". $get['ip'] ."', timestamp = '". $get['time'] ."', browser_id = '$browser_id', platform_id = '$platform_id', referrer_id = '$referrer_id', document_id = '$document_id', resolution_id = '$resolution_id', cookie_id = '$cookie_id', language_id = '$language_id', java_id = '$java_id'", $link) or die(mysql_error());
   } else
   {
      if($get['time'] > ($row['timestamp'] + (1000*60*10)))
      {
         $result = mysql_query("INSERT INTO `" .$prefix. "visits` SET ip = '". $get['ip'] ."', timestamp = '". $get['time'] ."', browser_id = '$browser_id', platform_id = '$platform_id', referrer_id = '$referrer_id', document_id = '$document_id', resolution_id = '$resolution_id', cookie_id = '$cookie_id', language_id = '$language_id', java_id = '$java_id'", $link) or die(mysql_error());
      } else {
         $row['hits']++;
         $result = mysql_query("UPDATE `" .$prefix. "visits` SET hits = ". $row['hits'] .", timestamp = ". $get['time'] ." WHERE id = '". $row['id'] ."'", $link) or die(mysql_error());
      }
   }

}

// print_r($get);




// ***************************************************************************
function isTrue ($value)
// test if a value is TRUE or FALSE
{
   if (is_bool($value)) return $value;

   // a string field may contain several possible values
   if (preg_match('/^(Y|YES|T|TRUE|ON|1)$/i', $value))
   {
      return 1;
   } // if

   return 0;

} // is_True

function getPlatform($value)
{
   $os = array(
      "AIX"          => array("[ ;\(]aix"),
      "AmigaOS"      => array("amiga[ ]?OS[ /]([0-9.]{1,10})", "amiga"),
      "AtheOS"       => array("atheos"),
      "BeOS"         => array("beos[ a-z]*([0-9.]{1,10})", "beos"),
      "Darwin"       => array("darwin[ ]?([0-9.]{1,10})", "darwin"),
      "Digital"      => array("asf[0-9][ ]?V(4[0-9.]{1,10})"),
      "FreeBSD"      => array("free[ \-]?bsd[ /]([a-z0-9._]{1,10})", "free[ \-]?bsd"),
      "HPUX"         => array("hp[ \-]?ux[ /]([a-z0-9._]{1,10})"),
      "IRIX"         => array("irix[0-9]*[ /]([0-9.]{1,10})", "irix"),
      "Linux"        => array("linux[ /\-]([a-z0-9._]{1,10})", "linux"),
      "MacOS X"      => array("mac[ ]?os[ ]?x"),
      "MacOS PPC"    => array("mac(_mower|intosh.+p)pc"),
      "NetBSD"       => array("net[ \-]?bsd[ /]([a-z0-9._]{1,10})", "net[ \-]?bsd"),
      "OS/2 Warp"    => array("warp[ /]?([0-9.]{1,10})", "os[ /]?2"),
      "OpenBSD"      => array("open[ \-]?bsd[ /]([a-z0-9._]{1,10})", "open[ \-]?bsd"),
      "OpenVMS"      => array("open[ \-]?vms[ /]([a-z0-9._]{1,10})", "open[ \-]?vms"),
      "PalmOS"       => array("palm[ \-]?(source|os)[ /]?([0-9.]{1,10})", "palm[ \-]?(source|os)"),
      "QNX Photon"   => array("photon"),
      "RiscOS"       => array("risc[ \-]?os[ /]?([0-9.]{1,10})", "risc[ \-]?os"),
      "SunOS"        => array("sun[ \-]?os[ /]?([0-9.]{1,10})", "sun[ \-]?os"),
      "Symbian OS"   => array("symbian"),
      "Tru64"        => array("osf[0-9][ ]?V(5[0-9.]{1,10})"),
      "UnixWare"     => array("unixware[ /]?([0-9.]{1,10})", "unixware"),
      "Windows 2003" => array("wi(n|ndows)[ \-]?(2003|nt[ /]?5\.2)"),
      "Windows 2000" => array("wi(n|ndows)[ \-]?(2000|nt[ /]?5\.0)"),
      "Windows 95"   => array("wi(n|ndows)[ \-]?95"),
      "Windows CE"   => array("wi(n|ndows)[ \-]?ce"),
      "Windows ME"   => array("win 9x 4\.90", "wi(n|ndows)[ \-]?me"),
      "Windows XP"   => array("windows xp", "wi(n|ndows)[ \-]?nt[ /]?5\.1"),
      // The following ones are catch ups, they got to stay here.
      "BSD"          => array("bsd"),
      "MacOS"        => array("mac[^hk]"),
      "Windows NT"   => array("wi(n|ndows)[ \-]?nt[ /]?([0-4][0-9.]{1,10})", "wi(n|ndows)[ \-]?nt"),
      "Windows 98"   => array("wi(n|ndows)[ \-]?98"),
      "Windows"      => array("wi(n|n32|ndows)"),
      // things we don't know by now
      "other"        => array(".*")
   );

   foreach($os as $i => $v)
   {
      foreach($v as $sv)
      {
         if (preg_match("{".$sv."}", $value, $treffer)) return $i;
      }
   }

   return '';

}

function getBrowser($value)
{
   $browser = array(
      "ABrowse"         => array("abrowse[ /\-]([0-9.]{1,10})", "^abrowse"),
      "Amaya"           => array("amaya/([0-9.]{1,10})"),
      "ANTFresco"       => array("antfresco[ /]([0-9.]{1,10})"),
      "AOL"             => array("aol[ /\-]([0-9.]{1,10})", "aol[ /\-]?browser"),
      "Avant Browser"   => array("avant[ ]?browser"),
      "AvantGo"         => array("avantgo[ /]([0-9.]{1,10})"),
      "Aweb"            => array("aweb[/ ]([0-9.]{1,10})"),
      "Beonex"          => array("beonex/([0-9.]{1,10})"),
      "Blazer"          => array("blazer[/ ]([0-9.]{1,10})"),
      "Camino"          => array("camino/([0-9.+]{1,10})"),
      "Chimera"         => array("chimera/([0-9.+]{1,10})"),
      "Columbus"        => array("columbus[ /]([0-9.]{1,10})"),
      "Crazy Browser"   => array("crazy browser[ /]([0-9.]{1,10})"),
      "Curl"            => array("curl[ /]([0-9.]{1,10})"),
      "Deepnet Explorer"=> array("deepnet explorer[/ ]([0-9.]{1,10})", " deepnet explorer[\);]"),
      "Dillo"           => array("dillo/([0-9.]{1,10})"),
      "Doris"           => array("doris/([0-9.]{1,10})"),
      "ELinks"          => array("elinks[ /][\(]*([0-9.]{1,10})"),
      "Epiphany"        => array("epiphany/([0-9.]{1,10})"),
      "Firebird"        => array("firebird/([0-9.+]{1,10})"),
      "Firefox"         => array("firefox/([0-9.+]{1,10})"),
      "Galeon"          => array("galeon/([0-9.]{1,10})"),
      "IBrowse"         => array("ibrowse[ /]([0-9.]{1,10})"),
      "iCab"            => array("icab[/ ]([0-9.]{1,10})"),
      "ICEbrowser"      => array("icebrowser/v?([0-9._]{1,10})"),
      "iSiloX"          => array("isilox/([0-9.]{1,10})"),
      "Lotus Notes"     => array("lotus[ \-]?notes[ /]([0-9.]{1,10})"),
      "K-Meleon"        => array("k-meleon[ /]([0-9.]{1,10})"),
      "Konqueror"       => array("konqueror/([0-9.]{1,10})"),
      "Links"           => array("links[ /]\(([0-9.]{1,10})"),
      "Lunascape"       => array("lunascape[ /]([0-9.]{1,10})"),
      "Lynx"            => array("lynx/([0-9a-z.]{1,10})"),
      "Maxthon"         => array(" maxthon[\);]"),
      "mBrowser"        => array("mbrowser[ /]([0-9.]{1,10})"),
      "Mosaic"          => array("mosaic[ /]([0-9.]{1,10})"),
      "Multi-Browser"   => array("multi-browser[ /]([0-9.]{1,10})"),
      "MyIE2"           => array(" myie2[\);]"),
      // "Nautilus"        => array("(gnome[ \-]?vfs|nautilus)/([0-9.]{1,10})"),
      "Netcaptor"       => array("netcaptor[ /]([0-9.]{1,10})"),
      "NetFront"        => array("netfront[ /]([0-9.]{1,10})$"),
      "NetPositive"     => array("netpositive[ /]([0-9.]{1,10})"),
      "OmniWeb"         => array("omniweb/[ a-z]?([0-9.]{1,10})$"),
      "Opera"           => array("opera[ /]([0-9.]{1,10})"),
      "Oregano"         => array("oregano[0-9]?[ /]([0-9.]{1,10})$"),
      "PhaseOut"        => array("www\.phaseout\.net"),
      "PLink"           => array("plink[ /]([0-9a-z.]{1,10})"),
      "Phoenix"         => array("phoenix/([0-9.+]{1,10})"),
      "Proxomitron"     => array("space[ ]?bison/[0-9.]{1,10}"),
      "Safari"          => array("safari/([0-9.]{1,10})"),
      "Shiira"          => array("shiira/([0-9.]{1,10})"),
      "Sleipnir"        => array("sleipnir( version)?[ /]([0-9.]{1,10})"),
      "SlimBrowser"     => array("slimbrowser"),
      "StarOffice"      => array("staroffice[ /]([0-9.]{1,10})"),
      "Sunrise"         => array("sunrisebrowser[ /]([0-9.]{1,10})"),
      "Voyager"         => array("voyager[ /]([0-9.]{1,10})"),
      "w3m"             => array("w3m/([0-9.]{1,10})"),
      "Webtv"           => array("webtv[ /]([0-9.]{1,10})", "webtv"),
      "Xiino"           => array("^xiino[ /]([0-9a-z.]{1,10})"),
      // Catch up for the originals. they got to stay in that order.
      "Explorer"        => array("\(compatible; msie[ /]([0-9.]{1,10})"),
      "Netscape"        => array("netscape[0-9]?/([0-9.]{1,10})", "^mozilla/([0-4]\.[0-9.]{1,10})"),
      "Mozilla"         => array("^mozilla/[5-9]\.[0-9.]{1,10}.+rv:([0-9a-z.+]{1,10})", "^mozilla/([5-9]\.[0-9a-z.]{1,10})"),
      // Things we don't know by now
      "other"           => array(".*")
   );

   foreach($browser as $i => $v)
   {
      foreach($v as $sv)
      {
         if (preg_match("|".$sv."|", $value, $treffer)) return array($i, $treffer[1]);
      }
   }

   return array();

}
?>
