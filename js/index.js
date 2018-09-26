const SOLDIER_SIZE = 24;

var elem = null;

function init ( )
/* Function initializes event handlers for input fields. */
{
	x_size_field = document.getElementById ( "x_size" );
	y_size_field = document.getElementById ( "y_size" );
	num_soldiers_field = document.getElementById ( "num_soldiers" );

	x_size_field.onfocus = xSizeOnFocus;
	x_size_field.onblur = xSizeOnBlur;
	y_size_field.onfocus = ySizeOnFocus;
	y_size_field.onblur = ySizeOnBlur;
	num_soldiers_field.onfocus = numSoldiersOnFocus;
	num_soldiers_field.onblur = numSoldiersOnBlur;
}



function checkBattlefieldSize ( )
/* Function accepts as input the size of a battlefield expressed in pixels and
 * the number of soldiers per side. 'x' represents the horizontal size of the
 * battlefield, 'y' represents the vertical size, and 'num' represents the
 * number of soldiers for each side. Function will return true if battlefield
 * is large enugh for the given number of soldiers, false if not, or null if
 * one or more of the fields is blank.
 * Rules:
 * 	1. There must be at least one soldier width between each soldier, both
 * 	   in each row and between rows.
 * 	2. There must be at least one soldier size space between the ends of
 * 	   the battlefield and the farthest rows of soldiers. */
{
	x_size_field = document.getElementById ( "x_size" );
	y_size_field = document.getElementById ( "y_size" );
	num_soldiers_field = document.getElementById ( "num_soldiers" );
	error_area = document.getElementById ( "error" );
	submit_button = document.getElementById ( "submit" );

	var x = x_size_field.value;
	var y = y_size_field.value;
	var num = num_soldiers_field.value;
	var soldiers_per_row;
	var army_area_req;

	if ( ( x === "" ) || ( y === "" ) || ( num === "" ) )	{
		return null;
	}

	// Determine maximum number of soldiers per row given vertical size
	// of battlefield.
	soldiers_per_row = y / ( SOLDIER_SIZE * 2 );

	// Next, determine how much room is needed for a single army.
	army_area_req = ( ( num / soldiers_per_row ) * SOLDIER_SIZE ) + SOLDIER_SIZE;

	// Check if number of rows takes up more than half the battlefield.
	// (More than half because there will be two armies.)
	if ( army_area_req > ( x / 2 ) )	{

		error_area.innerHTML = "The battlefield size is too small for the number of soldiers specified. Please increase the battlefield size or reduce the number of soldiers.";
		submit.disabled = true;

	} else	{

		error_area.innerHTML = "";
		submit.disabled = false;

	}
}



function xSizeOnFocus ( )
{

	var x_size_field = document.getElementById ( "x_size" );

	if ( x_size_field.value == "800" )	{
		x_size_field.value = "";
	}


	checkBattlefieldSize ( );
}



function xSizeOnBlur ( )
{

	var x_size_field = document.getElementById ( "x_size" );

	if ( x_size_field.value == "" )	{
		x_size_field.value = "800";
	}

	checkBattlefieldSize ( );

}



function ySizeOnFocus ( )
{

	var y_size_field = document.getElementById ( "y_size" );

	if ( y_size_field.value == "400" )	{
		y_size_field.value = "";
	}

	checkBattlefieldSize ( );

}



function ySizeOnBlur ( )
{

	var y_size_field = document.getElementById ( "y_size" );

	if ( y_size_field.value == "" )	{
		y_size_field.value = "400";
	}

	checkBattlefieldSize ( );

}



function numSoldiersOnFocus ( )
{

	var num_soldiers_field = document.getElementById ( "num_soldiers" );

	if ( num_soldiers_field.value == "10" )	{
		num_soldiers_field.value = "";
	}

	checkBattlefieldSize ( );

}



function numSoldiersOnBlur ( )
{

	var num_soldiers_field = document.getElementById ( "num_soldiers" );

	if ( num_soldiers_field.value == "" )	{
		num_soldiers_field.value = "10";
	}

	checkBattlefieldSize ( );

}
