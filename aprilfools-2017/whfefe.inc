<?php
define('MINTEXTLEN', 50);
define('MAGIC_CONST', 1165321313); // {'E', 'u', 'd', 'a'}
$NO_ENTRIES = "<p>No entries found.</p>\n";

$spalten = "pid,tid,uid,username,message,FROM_UNIXTIME(dateline,'%a %b %d %Y') AS datestr";
$where = 'fid NOT IN (17,39,40)';
// set something like 'limit' => 1000 if too many results
$order = ['order_by' => 'dateline', 'order_dir' => 'DESC'];

// sorted by UID
$nicknames = [
	"TAL" => ["Thomas, die kleine Lokomotive", "きかんしゃトーマス", "new MyBBUser(1)"],
	"Commodore-Freak" => ["Commo", "Hitler"],
	"pETe!" => ["Beitrag gemeldet von pETe!"],
	"oreissig" => ["Maître Modérateur", "Herr Reißig"],
	"s4ndwichMakeR" => ["s4ndy bridge", "<code>3473 646e 6977 6863 614d 656b 0052</code>"],
	"Spit" => ["Patrick", "fetter Klempner"],
	"HOMEBOY87" => ["Hoschi", "Chef-Reparateur"],
	"Randy" => ["Christian Holland"],
	"DosAmp" => ["Herr Offizier", "und aktualisiere endlich MyBB, du faules Stück", "professioneller Publikums-Beschimpfer"],
	"Chrisse" => ["generischer Benutzer", "Beitrag gemeldet von Chrisse"],
	"chiaki" => ["<font color=\"magenta\">neu jetzt auch in Pink!</font>"],
	"dancle" => ["Daniel", "Quoten-Furry"],
	"Igor Todeshure" => ["Methusalem", "Folterknecht", "Phoenix"],
	"Benjamin92" => ["Benjamin"],
	"freaked" => ["möpschen", "Schatz", "oida"],
	"gandro" => ["gantroll", "Ganondrof", "party like it's 2012"],
	"CHRiSNEW" => ["Christian", "Unfortunately, MyImages.eu has stopped"],
	"Pain" => ["Matthias", "und Verstärker sind besser"],
	"amok_alex" => ["Koma-Alex", "der andere Alexander auf Telegram"],
	"winfreak" => ["WHTESTER"],
	"Tax-5" => ["<em>hört gerade Tax-5</em>"],
	"Aqua" => ["Voodoo-Priester", "Kemtrails-Aluhut"],
	"Der Doktor" => ["CDWs Zweitaccount", "wir sind Nummer Eins", "MEHR MIKROCONTROLLER"],
	"Alpha" => ["Coni", "Chef-Pole", "und bitte nicht Forum löschen"],
	"Smaecks" => ["Maximemeilian", "Prof. Dr. med. Wiese"],
	"Diamant001" => ["Diamond Zero Zero One", "ダイヤモンド001", "Das Axiom", "professionelle Diskussion gegen die Wand"],
	"Blue" => ["Wynton", "nicht so fetter Elektriker"],
	"clik!84" => ["clik", "WinZip Master"],
	"thosch97" => ["qsuscs", "qsx", "t23h" /* , hier stand ursprünglich ein Witz über DNSSEC */ ],
	"HobbyBastler" => ["HobbyBaster", "China-Importeur"],
	"mrshadowtux" => ["Alexander", "shadowtux", "und Receiver sind besser"],
	"CDW" => ["Zweitaccount von Der Doktor", "😂👌", "tbqh fam"],
	"Kuxuntu" => ["Micha", "Xeon-Profi"],
	"Ostholländer" => ["Dominik", "hecken"],
	"Fuexline" => ["Fuchsfaktor", "ich zeig Sie an!"],
	"Arnulf zu Linden" => ["Arnulf", "Sackpfeyffer zu Linden", "WLAN-Aluhut"],
];

// I am a MyBB, too.
define('IN_MYBB', 1);
define('MYBB_LOCATION', 'showthread.php');
require_once "../global.php";
require_once MYBB_ROOT."inc/class_parser.php";
$parser = new postParser;

function next_month($mv) {
	$year = intval(substr($mv, 0, 4), 10);
	$mon = intval(substr($mv, 4, 2), 10);
	if ($mon >= 12) {
		$year++;
		$mon = 1;
	} else {
		$mon++;
	}
	return sprintf('%04d%02d', $year, $mon);
}

function prev_month($mv) {
	$year = intval(substr($mv, 0, 4), 10);
	$mon = intval(substr($mv, 4, 2), 10);
	if ($mon <= 1) {
		$year--;
		$mon = 12;
	} else {
		$mon--;
	}
	return sprintf('%04d%02d', $year, $mon);
}

function remove_quotes($msg) {
	$curpos = 0;
	$quotedepth = 0;
	$cleaned_msg = "";

	while (preg_match("@<blockquote>|</blockquote>(?:\n)?(?:<br \/>)?@", $msg, $matches, PREG_OFFSET_CAPTURE, $curpos)) {
		$match = $matches[0][0];
		$matchpos = $matches[0][1];
		if ($quotedepth == 0) {
			$cleaned_msg .= substr($msg, $curpos, $matchpos - $curpos);
		}
		if (substr($match, 0, 2) === '<b') {
			$quotedepth++;
		} else {
			$quotedepth = ($quotedepth > 0 ? $quotedepth - 1 : 0);
		}
		$curpos = $matchpos + strlen($match);
	}
	if ($quotedepth == 0) {
		$cleaned_msg .= substr($msg, $curpos);
	}

	return $cleaned_msg;
}

function random_image_name() {
	$c = ["Symbolbild", "Bild", "Photo", "Beispiel", "Abbildung"];
	return $c[mt_rand(0, count($c) - 1)];
}

function format_msg($result) {
	global $parser;
	$m = remove_quotes($parser->parse_message($result['message'],
	       ['allow_html' => 0, 'allow_smilies' => 0, 'allow_mycode' => 1, 'nl2br' => 1, 'filter_badwords' => 0, 'me_username' => 0, 'shorten_urls' => 0, 'highlight' => 0, 'filter_cdata' => 1]));
	// instant XHTML->HTML5 converter :P
	$m = preg_replace('@ />@s', '>', $m);
	// untouched MyCode
	$m = preg_replace('/\[video=[a-z]*\]([^[]*)\[\/video\]/si', '(<a href="$1">Video</a>)', $m);
	$m = preg_replace_callback('/\[img\]([^[]*)\[\/img\]/si', function($match) {
		return "(<a href=\"{$match[1]}\">" . random_image_name() . '</a>)';
	}, $m);
	$m = preg_replace('@\[attachment=[0-9]*\](?:<br \/>\n)*@si', '', $m);
	// horizontal rulers look ugly
	$m = preg_replace('/<hr>\n?/s', '', $m);
	// anything more than the BMP confuses our users (replace with U+2BD1)
	$m = preg_replace('/[\xf0-\xf7][\x80-\xbf]{3}/', "\xe2\xaf\x91", $m);
	// general cleanup
	$m = preg_replace('/^\n/', '', $m);
	return $m;
}

function tribute_msg($msg, $result) {
	global $singlepost, $nicknames;
	$mpid = sprintf('%x', MAGIC_CONST - $result['pid']);
	$ret = "<a href=\"?ts=$mpid\">[l]</a> ";
	if ($singlepost) {
		$ret .= "<a href=\"/showthread.php?tid={$result['tid']}&amp;pid={$result['pid']}#pid{$result['pid']}\">[o]</a> ";
	}
	if ($result['pid'] % 139 == 13) {
		$ret .= "Dear Princess Celestia,<br>";
	}
	$ret .= $msg;
	$nickchoice = $nicknames[$result['username']];
	if ($nickchoice) {
		$nick = $nickchoice[$result['pid'] % count($nickchoice)];
	} else {
		$nick = $result['username'];
	}
	$ret .= " (Danke, {$nick})";
	return $ret;
}
?>
