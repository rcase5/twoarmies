class Initialize	{

	constructor ( x, y, num )	{
		this.x_size = x;
		this.y_size = y;
		this.num = num;
		this.initialize = "YES";
	}

}


function battleDriver ( battlefield = null )
/* This function is the main driver for the on-screen battle presentation.
 * Function will continue to run until all soldiers on one side are dead. */
{
	var army_health = [];
	var i, j;
	var own_army_id;
	var enemy_army_id;
	var hit_target_id;
	var enemy_target_health;

	// Begin main processing.
	army_health[0] = 0;
	army_health[1] = 0;

	for ( i = 0; i < battlefield.num; i++ )	{

		for ( j = battlefield.who_goes_first; j <= ( battlefield.who_goes_first + 1 ); j++ )	{

			// Get values to make our lives easier
			own_army_id = (j % 2);
			enemy_army_id = !(j % 2) ? 1 : 0;

			// If this is the first time through to get initial
			// positioning, we don't need to do this part.
			if ( ! battlefield.initial_run )	{

				hit_target_id = battlefield.army[own_army_id].soldier[i].hit_target;
				enemy_target_health = battlefield.army[enemy_army_id].soldier[hit_target_id].health;

			}

			// Move soldier
			document.getElementById ( "army" + own_army_id + "_soldier" + i ).setAttribute ( "cx", battlefield.army[own_army_id].soldier[i].x_pos );
			document.getElementById ( "army" + own_army_id + "_soldier" + i ).setAttribute ( "cy", battlefield.army[own_army_id].soldier[i].y_pos );

			// We don't need to do this on the initial run either.
			if ( ! battlefield.initial_run )	{
console.log ( "driver.js: battleDriver: Not the initial run, processing additional items, part 2" );

				// Process Hit
				if ( own_army_id === 1 )	{

					document.getElementById ( "army" + enemy_army_id + "_soldier" + hit_target_id ).setAttribute ( "fill", "#" + rgbToHex ( 0, 0, Math.round ( ( enemy_target_health/100 ) * 255 ) ) );

				} else	{

					document.getElementById ( "army" + enemy_army_id + "_soldier" + hit_target_id ).setAttribute ( "fill", "#" + rgbToHex ( Math.round ( ( enemy_target_health/100 ) * 255 ), 0, 0 ) );

				}

			}

			// Display stats
			document.getElementById ( "id_army" + own_army_id + "_soldier" + i ).innerHTML = i;
			document.getElementById ( "exp_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].experience;
			document.getElementById ( "health_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].health;
			document.getElementById ( "inttarget_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].intended_target;
			document.getElementById ( "hittarget_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].hit_target;
			document.getElementById ( "xpos_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].x_pos;
			document.getElementById ( "ypos_army" + own_army_id + "_soldier" + i ).innerHTML = battlefield.army[own_army_id].soldier[i].y_pos;

			// Tabulate army health statistics.
			army_health[own_army_id] += battlefield.army[own_army_id].soldier[i].health;

		}

	}

	if ( battlefield.initial_run )	{
		battlefield.initial_run = false;
	}

	if ( army_health[0] && army_health[1] )	{

		battlefield = sendAndReceive ( battlefield );
console.log ( "driver.js: battleDriver: returned from sendAndReceive." );

	} else	{

		if ( army_health[0] )	{
			document.getElementById ( "header" ).innerHTML = "Blue has prevailed!";
		} else	{
			document.getElementById ( "header" ).innerHTML = "Red has prevailed!";
		}

	}

}


function initialize ( )
/* Function instructs the server to initialize game play and request an initial
 * set of game data. Function will return the object received from the server.
 */
{

	var init_object;
	var battlefield;
	var x_size;
	var y_size;
	var num;

	x_size = document.getElementById ( "bf_size_x" ).value;
	y_size = document.getElementById ( "bf_size_y" ).value;
	num = document.getElementById ( "num" ).value;

	init_object = new Initialize ( x_size, y_size, num );

	sendAndReceive ( init_object );

}


function sendAndReceive ( battlefield )
/* Function accepts as input the 'battlefield' object and sends it to the
 * server for processing. It then waits for the response from the server and
 * returns a battlefield object for playback, or false if there was a problem. 
 * */
{
	var bf_json;
	var new_battlefield;

	// Prepare connection.
	bf_json = JSON.stringify ( battlefield );
	xmlhttp = new XMLHttpRequest ( );

	xmlhttp.onreadystatechange = function ( )	{

		if ( this.readyState == 4 && this.status == 200 )	{

			new_battlefield = JSON.parse ( this.responseText );
			battleDriver ( new_battlefield );

		}

	}

	xmlhttp.open ( "POST", "turn.php", true );
	xmlhttp.setRequestHeader ( "Content-type", "application/x-www-form-urlencoded" );
	xmlhttp.send ( "json_data=" + bf_json );

}


function rgbToHex ( r, g, b )
/* Function accepts as input three values: a 'r'ed value, a 'g'reen value, and a
 * 'b'lue value. Function takes those values and converts them to a hex color
 * code on success. */
{

	var html_color_code;

	if ( r < 16 )	{
		html_color_code = "0" + r.toString(16);
	} else	{
		html_color_code = r.toString(16);
	}

	if ( g < 16 )	{
		html_color_code += "0" + g.toString(16);
	} else	{
		html_color_code += g.toString(16);
	}

	if ( b < 16 )	{
		html_color_code += "0" + b.toString(16);
	} else	{
		html_color_code += b.toString(16);
	}

	return html_color_code;

}
