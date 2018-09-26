<?php

require_once ( "lib/armies.php" );

if ( $_POST )	{

	if ( is_numeric ( $_POST['x'] ) )	{
		$bf_size_x = $_POST['x'];
	}

	if ( is_numeric ( $_POST['y'] ) )	{
		$bf_size_y = $_POST['y'];
	}

	if ( is_numeric ( $_POST['num'] ) )	{
		$num = $_POST['num'];
	}

}

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Battle of the Two Armies</title>
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" language="javascript" src="js/graphing.js"></script>
<script type="text/javascript" language="javascript" src="js/driver.js"></script>
</head>
<body onload="initialize ( )">
<form id="hidden_form">
<input id="bf_size_x" type="hidden" name="bf_size_x" value="<?php echo ( $bf_size_x ); ?>" />
<input id="bf_size_y" type="hidden" name="bf_size_y" value="<?php echo ( $bf_size_y ); ?>" />
<input id="num" type="hidden" name="num" value="<?php echo ( $num ); ?>" />
</form>
<h1 id="header">The Battle Is Joined!</h1>
<svg id="battlefield" width="<?php echo ( $bf_size_x ); ?>" height="<?php echo ( $bf_size_y ); ?>">
<?php	for ( $i = 0; $i < $num; $i++ )	{

		for ( $j = 0; $j <= 1 ; $j++ )	:	?>

<circle id="army<?php echo ( $j % 2 ); ?>_soldier<?php echo ( $i ); ?>" cx="0" cy="0" r="<?php echo ( SOLDIER_SIZE / 2 ); ?>" fill="<?php

			if ( $j % 2 )	{
				echo ( "#ff0000" );
			} else	{
				echo ( "#0000ff" );
			}
?>" />
<?php		endfor;

	}
?>
</svg>
<div id="army0">
<h2 id="army0">Blue Army</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Experience</th>
<th>Health</th>
<th>I Target</th>
<th>H Target</th>
<th>X pos</th>
<th>Y pos</th>
<tr>
</thead>
<tbody>
<?php	for ( $i = 0; $i < $num; $i++ )	:	?>
<tr>
<td id="id_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="exp_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="health_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="inttarget_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="hittarget_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="xpos_army0_soldier<?php echo ( $i ); ?>"></td>
<td id="ypos_army0_soldier<?php echo ( $i ); ?>"></td>
</tr>
<?php	endfor;	?>
</tbody>
</table>
</div>
<div id="army1">
<h2 id="army1">Red Army</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Experience</th>
<th>Health</th>
<th>I Target</th>
<th>H Target</th>
<th>X pos</th>
<th>Y pos</th>
<tr>
</thead>
<tbody>
<?php	for ( $i = 0; $i < $num; $i++ )	:	?>
<tr>
<td id="id_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="exp_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="health_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="inttarget_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="hittarget_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="xpos_army1_soldier<?php echo ( $i ); ?>"></td>
<td id="ypos_army1_soldier<?php echo ( $i ); ?>"></td>
</tr>
<?php	endfor;	?>
</tbody>
</table>
</div>
</body>
</html>
