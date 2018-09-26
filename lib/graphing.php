<?php

function getDistance ( $x, $y, $x2 = NULL, $y2 = NULL )
/* Function determines the distance between (x1, y1) and (x2, y2). Values can
   be passed in as two sets of keyed arrays or individually. If keyed arrays
   are used, the first set of coordinates should be passed in via 'x', and the
   second set via 'y'. Function expects the following in keyed arrays:
	array ( 'x' => 0,
		'y' => 2
	)'
   Function will return the distance between the two sets of coordinates on
   success, or FALSE if there was an error. */
{

	if ( is_array ( $x ) && is_array ( $y ) ) {

		$x1 = $x['x'];
		$y1 = $x['y'];
		$x2 = $y['x'];
		$y2 = $y['y'];

	} elseif ( ( $x2 == NULL ) && ( $y2 == NULL ) )	{

		return FALSE;

	} else	{

		$x1 = $x;
		$y1 = $y;

	}

	$distance_squared = pow ( abs ( $x1 - $x2 ), 2 ) + pow ( abs ( $y1 - $y2), 2 );
	$distance = sqrt ( $distance_squared );

	return $distance;

}



function circleIntersectsLine ( $x0, $y0, $radius, $x1, $y1, $x2, $y2 )
/* Function accepts as input the coordinates of a circle located at x0, y0 and
   determines if the line given enpoints x1, y1, x2, y2 intersects the circle
   given the circle's radius. Function return TRUE or FALSE. */
{
	$x1 -= $x0;
	$y1 -= $y0;
	$x2 -= $x0;
	$y2 -= $y0;

	$a = pow ( $x1, 2 ) + pow ( $y1, 2 ) - $radius;
	$b = 2 * ( $x1 * ( $x2 - $x1 ) + $y1 * ( $y2 - $y1 ) );
	$c = pow ( ( $x2 - $x1 ), 2 ) + pow ( ( $y2 - $y1 ), 2 );

	$disc = pow ( $b, 2 ) - 4 * $a * $c;

	if ( $disc <= 0 )	{
		return FALSE;
	}

	$sqrtdisc = sqrt ( $disc );

	$t1 = ( -$b + $sqrtdisc ) / ( 2 * $a );
	$t2 = ( -$b - $sqrtdisc ) / ( 2 * $a );

	if ( ( 0 < $t1 && $t1 < 1 ) || ( 0 < $t2 && $t2 < 1 ) )	{
		return TRUE;
	}

	return FALSE;

}

?>
