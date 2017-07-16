<?php
define('AVG_YEAR', 31556952);
// @stephenhay's compromise https://mathiasbynens.be/demo/url-regex
define('SIMPLE_URL_REGEX', '@^(https?|ftp)://[^\s/$.?#].[^\s]*\.css$@iS');
if (isset($_GET['css'])) {
	$newcss = $_GET['css'];
	if (preg_match('/^[a-z0-9]*\.css$/i', $newcss) ||
	    preg_match(SIMPLE_URL_REGEX, $newcss)) {
		setcookie("af2017css", $newcss, time() + AVG_YEAR);
		$css = $newcss;
	} elseif (empty($_GET['css'])) {
		setcookie("af2017css", "", time() - 60*60);
	}
} elseif (isset($_COOKIE['af2017css'])) {
	$oldcss = $_COOKIE['af2017css'];
	if (preg_match('/^[a-z0-9]*\.css$/i', $oldcss) ||
	    preg_match(SIMPLE_URL_REGEX, $oldcss)) {
		$css = $oldcss;
	}
}
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>WHaFes Blog</title>
<?php if ($css) { if (preg_match(SIMPLE_URL_REGEX, $css)) {?><link rel="stylesheet" type="text/css" href="<?= $css ?>">
<?php } else {?><link rel="stylesheet" type="text/css" href="aprilfools-2017/css/<?= $css ?>">
<?php }}?></head>
<body>
<h2><a href="/af_whfefe">WHaFes Blog</a></h2>
<b>Wer schöne Beitragsmeldungen für mich hat: ab an dosamp (æt) winhistory-forum.net!</b>
<p style="text-align:right">Fragen? <a href="af_whfefaq.html">Antworten!</a></p>
<?php
require_once 'whfefe.inc';
$err = TRUE;
$prevdatestr = "";
$monthview = "";
if (isset($_GET['ts'])) {
	$singlepost = TRUE;
	if (preg_match('/^[0-9a-f]{1,8}$/i', $_GET['ts'])) {
		$qpid = intval($_GET['ts'], 16);
		if ($qpid >= 0 || $qpid < MAGIC_CONST) {
			$err = FALSE;
			$qpid = MAGIC_CONST - $qpid;
			$where .= " AND pid = ".$qpid;
		}
	}
} else {
	$singlepost = FALSE;
	$where .= " AND LENGTH(message) >= ".MINTEXTLEN;
	if (isset($_GET['mon'])) {
		if (preg_match('/^20[0-9]{2}(?:0[1-9]|1[0-2])$/', $_GET['mon'])) {
			$err = FALSE;
			$monthview = $_GET['mon'];
			// MySQL has far better date functions than PHP
			$where .= " AND dateline >= UNIX_TIMESTAMP(STR_TO_DATE('{$monthview}01', '%Y%m%d')) AND dateline < UNIX_TIMESTAMP(STR_TO_DATE('" . next_month($monthview) . "01', '%Y%m%d'))";
		}
	} else {
		$err = FALSE;
		$where .= " AND dateline >= UNIX_TIMESTAMP(SUBDATE(CURDATE(), 3))";
	}
}
if (!$err) {
	// no posts are an error, too
	$err = TRUE;
	$q = $db->simple_select('posts', $spalten, $where, $order);
	while ($r = $db->fetch_array($q)) {
		if ($r['datestr'] !== $prevdatestr) {
			if ($prevdatestr) {
				echo "</ul>\n";
			}
			$prevdatestr = $r['datestr'];
			echo "<h3>{$prevdatestr}</h3>\n<ul>\n";
		}
		$msg = format_msg($r);
		if (!$singlepost && strlen($msg) < MINTEXTLEN) continue;
		$err = FALSE;
		$msg = tribute_msg($msg, $r);
		echo "<li>$msg</li>\n";
	}
	if ($err) {
		echo $NO_ENTRIES;
	} else {
		echo "</ul>\n";
	}
} else {
	echo $NO_ENTRIES;
}
?><div style="text-align:center"><?php
if ($monthview) {
	echo "<a href=\"?mon=" . prev_month($monthview) . "\">früher</a> -- <a href=\"/af_whfefe\">aktuell</a> -- <a href=\"?mon=" . next_month($monthview) . "\">später</a>";
} else {
	echo "<a href=\"?mon=" . date("Ym") . "\">ganzer Monat</a>";
}
?></div>
<div style="text-align:right">Made quick &amp; dirty with the usual LEMP stack<br><a href="impressum.php">Impressum</a></div>
</body>
</html>
