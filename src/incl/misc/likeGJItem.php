<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
require_once "../lib/GJPCheck.php";
$gs = new mainLib();

if(!isset($_POST['itemID']))
	exit(-1);

$type = isset($_POST['type']) ? $_POST['type'] : 1;
$itemID = ExploitPatch::remove($_POST['itemID']);
$isLike = isset($_POST['like']) ? $_POST['like'] : 1;
$accountID = GJPCheck::getAccountIDOrDie();
if(!is_numeric($accountID))
	exit("-1");

$query = $db->prepare("SELECT count(*) FROM actions_likes WHERE itemID=:itemID AND type=:type AND accountID=:accountID");
$query->execute([':type' => $type, ':itemID' => $itemID, ':accountID' => $accountID]);
if($query->fetchColumn() > 0)
	exit("-1");

$ip = $gs->getIP();
$query = $db->prepare("INSERT INTO actions_likes (itemID, type, isLike, accountID, ip) VALUES (:itemID, :type, :isLike, :accountID, INET6_ATON(:ip))");
$query->execute([':itemID' => $itemID, ':type' => $type, ':isLike' => $isLike, ':accountID' => $accountID, ':ip' => $ip]);

switch($type){
	case 1:
		$table = "levels";
		$column = "levelID";
		break;
	case 2:
		$table = "comments";
		$column = "commentID";
		break;
	case 3:
		$table = "acccomments";
		$column = "commentID";
		break;
	case 4:
		$table = "lists";
		$column = "listID";
		break;
}

$query=$db->prepare("SELECT likes FROM $table WHERE $column = :itemID LIMIT 1");
$query->execute([':itemID' => $itemID]);
$likes = $query->fetchColumn();
if($isLike == 1)
	$sign = "+";
else
	$sign = "-";

$query=$db->prepare("UPDATE $table SET likes = likes $sign 1 WHERE $column = :itemID");
$query->execute([':itemID' => $itemID]);
echo "1";
?>
