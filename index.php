<?php
require_once ( "lib/armies.php" );
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="style.css" />
<script language="javascript" type="text/javascript" src="js/index.js"></script>
<title>Army Battle</title>
</head>
<body onload="init ( );">
<form method="post" action="battlefield.php">
<label>Enter horizontal (x) size of battlefield:</label>
<input id="x_size" type="text" name="x" id="x_size" value="800" /><br />
<label>Enter vertical (y) size of battlefield:</label>
<input id="y_size" type="text" name="y" id="y_size" value="400" /><br />
<label>Enter number of soldiers per side:</label>
<input id="num_soldiers" type="text" name="num" id="num_soldiers" value="10" /><br />
<div id="error">
</div>
<input type="submit" id="submit" />
</form>
<pre>
<?php
if ( $_POST )	{
	// Grab user input.
	$x = $_POST['x'];
	$y = $_POST['y'];
	$num = $_POST['num'];

	$battlefield = new Battlefield ( $x, $y, $num );

	$json_output = json_encode ( $battlefield );
	echo ( $json_output );
?>
<p />
<?php
	print_r ( $battlefield );
}
?>
</pre>
</body>
</html>
