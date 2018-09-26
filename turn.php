<?php
require_once ( "lib/armies.php" );

header ( "Content-Type: application/json; charset=UTF-8" );

$incoming_data = json_decode ( $_POST['json_data'], FALSE );

if ( $incoming_data->initialize == "YES" )	{

	$battlefield = new Battlefield ( $incoming_data->x_size, $incoming_data->y_size, $incoming_data->num );

} else	{

	$battlefield = new Battlefield ( $incoming_data->x_size, $incoming_data->y_size, $incoming_data->num, $incoming_data );

	for ( $i = 0; $i < $battlefield->num; $i++ )	{

		for ( $j = $battlefield->who_goes_first; $j <= ( $battlefield->who_goes_first + 1 ); $j++ )	{

			$own_army_id = ( $j % 2 );
			$enemy_army_id = ( !( $j % 2 ) ? 1 : 0 );

			if ( ! $battlefield->army[$own_army_id]->soldier[$i]->isDead ( ) )	{
				$selected_enemy = $battlefield->army[$own_army_id]->soldier[$i]->selectTarget ( $battlefield->army[$enemy_army_id] );
				$battlefield->army[$own_army_id]->soldier[$i]->move ( $battlefield->army[$enemy_army_id], $battlefield->army[$own_army_id], $battlefield );
				$soldier_hit = $battlefield->army[$own_army_id]->soldier[$i]->fire ( $battlefield->army[$own_army_id], $battlefield->army[$enemy_army_id] );

				if ( $soldier_hit >= 0 )	{
					$battlefield->army[$enemy_army_id]->soldier[$soldier_hit]->hit ( $battlefield->army[$own_army_id]->soldier[$i], $battlefield->army[$enemy_army_id] );
				}

			}

		}

	}

}

echo ( json_encode ( $battlefield ) );

?>
