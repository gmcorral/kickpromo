<html>
<body>
<?php
//header('content-type: text/html; charset: utf8');
//mb_internal_encoding('utf8');
require_once('user.php');


$usr = new User('a@b.c', 'aaa', 'Jose Lopez', +1, '2013-03-21', '10.43.2.12', '1987-10-28');
$usr->insert();
print "<br/>";
print 'First '.$usr->toJSON()."<br/>";
$usr->usr_fullname = 'Pepe Perez';
$usr->usr_fbid = '1234567';
$res = $usr->update();
print "<br/>";
print 'Updated '.$res." rows<br/>";

$usr = User::selectById($usr->usr_id);
print "<br/>";
print 'Last '.$usr->toJSON()."<br/>";
$res = $usr->delete();
print 'Deleted '.$res." rows<br/>";

?>
</body>
</html>