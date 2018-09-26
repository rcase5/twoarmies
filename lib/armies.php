<?php
require_once ( "graphing.php" );

define ( 'SOLDIER_SIZE',	24 );

class Battlefield	{
	public $x_size;
	public $y_size;
	public $num;
	public $army;
	public $who_goes_first;
	public $turn_number;
	public $initial_run;

	public function __construct ( $x_size, $y_size, $num, $bf_obj = NULL )
	{

		$this->x_size = $x_size;
		$this->y_size = $y_size;
		$this->num = $num;

		if ( $bf_obj )	{
			for ( $i = 0; $i < 2; $i++ )	{
				$this->army[$i] = new Army ( $i, $x_size, $y_size, $num, $bf_obj->army[$i] );
			}

		} else	{

			for ( $i = 0; $i < 2; $i++ )	{
				$this->army[] = new Army ( $i, $x_size, $y_size, $num );
			}

		}

		$this->coinToss ( );

		if ( $bf_obj )	{
			$this->initial_run = $bf_obj->initial_run;
			$this->turn_number = $bf_obj->turn_number + 1;
		} else	{
			$this->initial_run = TRUE;
			$this->turn_number = 1;
		}

	}

	public function coinToss ( )
	/* Function selects at random which army goes first. Function will
	   select at random a number 0 or 1, indicating the id of the army that
	   should go first. This function is only called once per battle.
	   Function returns the id of the army chosen to go first. */
	{

		$this->who_goes_first = round ( rand ( 0, 1 ) );

		return $this->who_goes_first;

	}

	public function turnFlip ( )
	/* Function takes the 'who_goes_first' value and flips it to the
	   opposite value. This function should be called after each turn in
	   the battle. Function returns the current value of 'who_goes_first'
	   after the flip. */
	{

		$this->who_goes_first = ! $this->who_goes_first;

		return $this->who_goes_first;

	}

}

class Army	{

	public $id;
	public $num_soldiers = 0;
	public $soldier;

	public function __construct ( $id, $bf_size_x, $bf_size_y, $num_soldiers, $army_obj = NULL )
	{

		// Set id of army.
		$this->id = $id;

		// Figure number of soldiers per row.
		$num_soldiers_per_row = floor ( $bf_size_y / ( SOLDIER_SIZE * 2 ) );

		// Figure number of rows.
		$num_rows = ceil ( $num_soldiers / $num_soldiers_per_row );

		// Update number of soldiers per row based on equal numbers of
		// soldiers per row.
		$num_soldiers_per_row = ceil ( $num_soldiers / $num_rows );

		// Figure space between soldiers.
		$space_between_soldiers = round ( $bf_size_y / ( $num_soldiers_per_row + 1 ) );

		// Populate soldiers.
		if ( $army_obj )	{

			for ( $i = 0; $i < $num_soldiers; $i++ )	{
				$this->soldier[$i] = new Soldier ( $id, $i, $army_obj->soldier[$i]->x_pos, $army_obj->soldier[$i]->y_pos, $army_obj->soldier[$i] );

				$this->num_soldiers++;
			}

		} else	{

			$row_num = $num_rows;

			for ( $i = 0; $i < $num_soldiers; $i++ )	{

				// Determine soldier position.
				if ( $i and ( ! ( $i % $num_soldiers_per_row ) ) )	{
					$row_num--;
					$y = 0;
				}

				if ( $this->id % 2 )	{
					$x = $bf_size_x - ( $row_num * ( SOLDIER_SIZE * 2 ) );
				} else	{
					$x = $row_num * ( SOLDIER_SIZE * 2 );
				}

				$y += $space_between_soldiers;

				$this->soldier[] = new Soldier ( $id, $i, $x, $y );
				$this->num_soldiers++;

			}

		}

	}

	public function inRange ( $this_id, $source_x, $source_y, $target_x, $target_y )
	/* Function determines what soldiers, if any, are in the range specified	   by the coordinates given. Values can be passed as two sets of keyed
	   arrays or individually. If keyed arrays are used, the first set of
	   coordinates should be passed in via 'source_x', and the second set
	   via 'source_y'. Function expects the following keyed arrays:
		array ( 'x' => 0,
			'y' => 2
		);
	   Function will return all soldiers in the specified range as an
	   array on success, or FALSE if there were no soldiers found or there
	   there was an error. */
	{

		if ( is_array ( $source_x ) && is_array ( $source_y ) )	{

			if ( $source_x['x'] < $source_y['x'] )	{
				$x1 = $source_x['x'] - SOLDIER_SIZE;
				$x2 = $source_y['x'] + SOLDIER_SIZE;
			} else	{
				$x1 = $source_y['x'] - SOLDIER_SIZE;
				$x2 = $source_x['x'] + SOLIDER_SIZE;
			}

			if ( $source_x['y'] < $source_y['y'] )	{
				$y1 = $source_x['y'] - SOLDIER_SIZE;
				$y2 = $source_y['y'] + SOLDIER_SIZE;
			} else	{
				$y1 = $source_y['y'] - SOLDIER_SIZE;
				$y2 = $source_x['y'] + SOLDIER_SIZE;
			}

		} elseif ( ( $target_x == NULL ) && ( $target_y == NULL ) )	{

			return FALSE;

		} else	{

			if ( $source_x < $target_x )	{
				$x1 = $source_x - SOLDIER_SIZE;
				$x2 = $target_x + SOLDIER_SIZE;
			} else	{
				$x1 = $target_x - SOLDIER_SIZE;
				$x2 = $source_x + SOLDIER_SIZE;
			}

			if ( $source_y < $target_y )	{
				$y1 = $source_y - SOLDIER_SIZE;
				$y2 = $target_y + SOLDIER_SIZE;
			} else	{
				$y1 = $target_y - SOLDIER_SIZE;
				$y2 = $source_y + SOLDIER_SIZE;
			}

		}

		for ( $i = 0; $i < $this->num_soldiers; $i++ )	{

			if ( $i == $this_id )	{
				continue;
			}

			if ( ( $this->soldier[$i]->x_pos > $x1 ) &&
			     ( $this->soldier[$i]->x_pos < $x2 ) &&
			     ( $this->soldier[$i]->y_pos > $y1 ) &&
			     ( $this->soldier[$i]->y_pos < $y2 ) )	{
				$in_range[] = $i;
			}

		}

		return $in_range;

	}

}

class Soldier	{
	public $army;
	public $id;
	public $health = 100;	// Full health = 100pts.
	public $experience = 0;	// Full experience = 100pts.
	public $intended_target;
	public $hit_target;
	public $x_pos;
	public $y_pos;
	public $prev_x_pos;
	public $prev_y_pos;

	function __construct ( $army, $id, $x_pos, $y_pos, $soldier_obj = NULL )
	{

		$this->army = $army;
		$this->id = $id;
		$this->x_pos = $x_pos;
		$this->y_pos = $y_pos;

		if ( $soldier_obj )	{
			$this->health = $soldier_obj->health;
			$this->experience = $soldier_obj->experience;
			$this->intended_target = $soldier_obj->intended_target;
			$this->hit_target = $soldier_obj->hit_target;
		}

	}

	public function getPosition ( )
	/* Function will return 'x_pos' and 'y_pos' of this soldier as a keyed
	   array. */
	{

		$position = array (
			'x' => $this->x_pos,
			'y' => $this->y_pos
		);

		return $position;

	}

	public function selectTarget ( $enemy_army )
	/* Function accepts as input an 'army' object, which is the object of
	   the opposing army. The function will select at random a target
	   soldier from the opposing army that is not dead (health != 0).
	   Function returns the 'id' of the opposing army soldier. */
	{

		if ( $this->isDead ( ) )	{
			return -1;
		}

		if ( ( ! isset ( $this->intended_target ) ) || $enemy_army->soldier[$this->intended_target]->isDead ( ) )	{

			// Keep selecting an emeny until you find one
			// that's alive, up to 20 times.
			$i = 0;
			do	{
				$i++;
				$selected_enemy = rand ( 0, ( $enemy_army->num_soldiers - 1 ) );
			} while ( ( $enemy_army->soldier[$selected_enemy]->isDead ( ) ) && ( $i < 20 ) );

			// If that didn't work, try the fallback of going
			// through the enemy soldier list and finding a live one
			// that way. If that doesn't work, then the battle is
			// over since it appears the enemy is dead.
			if ( $i == 20 )	{

				// Nope, random selection didn't work. Trying
				// the fallback.
				$selected_enemy = NULL;
				for ( $i = 0; ( $i < $enemy_army->num_soldiers ) && ( $selected_enemy === NULL ); $i++ )	{

					if ( $enemy_army->soldier[$i]->getHealth ( ) )	{
						$selected_enemy = $i;
					}

				}

			}

			if ( $selected_enemy !== NULL )	{
				$this->intended_target = $selected_enemy;
			} else	{
				// Abort and send a message to caller that
				// the war is over.
				return -2;
			}

			return ( $selected_enemy );

		}

		if ( $this->hit_target == -1 )	{
			$this->hit_target = $this->intended_target;
		}

	}

	public function isDead ( )
	/* Function determines if the soldier is dead (health = 0). Functon
	   returns TRUE or FALSE */
	{

		if ( $this->health )	{
			return FALSE;
		} else	{
			return TRUE;
		}

	}

	public function move ( $enemy_army, $own_army, $battlefield )
	/* Function accepts as input an 'army' object, which is the object of
	   the opposing army. The function will get the current location of
	   the currently targeted soldier and attempt to move toward him.
	   Rules:
		1. The soldier can only move 50 pixels at a time maximum.
		2. If the soldier is blocked by another soldier, friend or foe,
		   the soldier may move only as far as he is not blocked. So,
		   if a friendly soldier is in the soldier's way after moving
		   25 pixels, the soldier will only move 25 pixels.
	   Function will return the current position of the soldier as a keyed
	   array. */
	{

		if ( $this->isDead ( ) )	{
			return array ( 'x' => $this->x_pos,
				       'y' => $this->y_pos );
		}

		$enemy_position = $enemy_army->soldier[$this->intended_target]->getPosition ( );
		$distance = getDistance ( $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

		// Get coordinate differences.
		$x_diff = $this->x_pos - $enemy_position['x'];
		$y_diff = $this->y_pos - $enemy_position['y'];

		// Compute angle
		if ( $y_diff == 0 )	{
			$degrees = 0;
		} else	{
			$degrees = atan ( abs ( $y_diff ) / abs ( $x_diff ) );
		}

		// Check to see if soldier is stuck. If so, try a different
		// direction.
		if ( ( $this->x_pos == $this->prev_x_pos ) && ( $this->y_pos == $this->prev_y_pos ) )	{
			$degrees = rand ( 0, 89 );
		}

		// Get x ratio
		$x_ratio = cos ( $degrees );

		// Get y ratio
		$y_ratio = sin ( $degrees );

		// Check and see if there is anything in the way.
		$peak_distance = 0;
		$friendly_obstacles = $own_army->inRange ( $this->id, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

		if ( $friendly_obstacles )	{

			foreach ( $friendly_obstacles as $friendly_id )	{

				if ( circleIntersectsLine ( $own_army->soldier[$friendly_id]->x_pos, $own_army->soldier[$friendly_id]->y_pos, SOLDIER_SIZE / 2, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] ) )	{

					$friendly_position = $own_army->soldier[$friendly_id]->getPosition ( );
					$distance = getDistance ( $this->x_pos, $this->y_pos, $friendly_position['x'], $friendly_position['y'] );

					if ( $distance < 50 )	{

						if ( $peak_distance < $distance )	{
							$peak_distance = $distance;
						}

					}

				}

			}

		}

		$enemy_obstacles = $enemy_army->inRange ( $this->id, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

		if ( $enemy_obstacles )	{

			foreach ( $enemy_obstacles as $enemy_id )	{

				$enemy_obstacle_position = $enemy_army->soldier[$enemy_id]->getPosition ( );

				if ( circleIntersectsLine ( $enemy_obstacle_position['x'], $enemy_obstacle_position['y'], SOLDIER_SIZE / 2, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] ) )	{

					$enemy_position = $enemy_army->soldier[$enemy_id]->getPosition ( );
					$distance = getDistance ( $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

					if ( $distance < 50 )	{

						if ( $peak_distance < $distance )	{
							$peak_distance = $distance;
						}

					}

				}

			}

		}

		// Determine movement.
		$x_move = floor ( ( 50 - $peak_distance ) * $x_ratio );
		$y_move = floor ( ( 50 - $peak_distance ) * $y_ratio );

		if ( $x_diff > 0 )	{

			if ( ( $this->x_pos == $this->prev_x_pos ) && ( $this->y_pos == $this->prev_y_pos ) )	{

				$change_directions = rand ( 0, 1 );

				if ( ! $change_directions )	{
					$x_move *= -1;
				}

			} else	{

				$x_move *= -1;

			}
		}

		if ( $y_diff > 0 )	{

			if ( ( $this->x_pos == $this->prev_x_pos ) && ( $this->y_pos == $this->prev_y_pos ) )	{

				$change_directions = rand ( 0, 1 );

				if ( ! $change_directions )	{

					$y_move *= -1;

				}

			} else	{

				$y_move *= -1;

			}

		}

		// Record current position as previous.
		$this->prev_x_pos = $this->x_pos;
		$this->prev_y_pos = $this->y_pos;

		// Move!
		$this->x_pos += $x_move;
		$this->y_pos += $y_move;

		// Whoa there, Tex! Stay on the battlefield!
		if ( $this->x_pos < ( SOLDIER_SIZE / 2 ) )	{
			$this->x_pos = SOLDIER_SIZE / 2;
		}

		if ( $this->x_pos > ( $battlefield->x_size - ( SOLDIER_SIZE / 2 ) ) )	{
			$this->x_pos = $battlefield->x_size - ( SOLDIER_SIZE / 2 );
		}

		if ( $this->y_pos < ( SOLDIER_SIZE / 2 ) )	{
			$this->y_pos = SOLDIER_SIZE / 2;
		}

		if ( $this->y_pos > ( $battlefield->y_size - ( SOLDIER_SIZE / 2 ) ) )	{
			$this->y_pos = $battlefield->y_size - ( SOLDIER_SIZE / 2 );
		}

		return array ( 'x' => $this->x_pos,
			       'y' => $this->y_pos
			);

	}

	public function fire ( $own_army, $enemy_army )
	/* Function accepts as input the object for his own army, and the object
	   for the enemy army.
	   Rules:
		1. The soldier cannot fire if there are friendly forces in the
		   way.
		2. If there is an enemy soldier in the way of the soldier's
		   intended target, the hit shall count against the enemy
		   soldier that is in the way of the intended target.
	   Function will return the id of the enemy soldier that will be hit,
	   or FALSE if the soldier cannot fire. */
	{
		// First, see if soldier hit intended target the last time. If
		// not, switch targets. If 'intended_target' is -1, that means
		// there were friendly forces in the way before, so soldier
		// should not choose a new target.

		if ( $this->isDead ( ) )	{
			return $this->intended_target;
		}

		if ( $this->hit_target && $this->hit_target != -1 )	{

			if ( ( $this->hit_target != $this->intended_target ) and ( $this->intended_target != -1 ) and ( ! $enemy_army->soldier[$this->hit_target]->isDead ( ) ) )	{
				$this->intended_target = $this->hit_target;
			} else	{
				$this->hit_target = $this->intended_target;
			}

		}

		// Next, determine if there are friendly forces in the way.
		$enemy_position = $enemy_army->soldier[$this->intended_target]->getPosition ( );
		$friendly_obstacles = $own_army->inRange ( $this->id, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

		if ( $friendly_obstacles )	{

			foreach ( $friendly_obstacles as $friendly_id )	{

				if ( ! $own_army->soldier[$friendly_id]->isDead ( ) )	{

					$friendly_position = $own_army->soldier[$friendly_id]->getPosition ( );

				}

			}

		}

		// Next, see if soldier can fire (friendly obstancles in
		// the way.
		if ( $this->hit_target != -1 )	{
			// There are no friendly targets in the way. Proceed.

			$enemy_obstacles = $enemy_army->inRange ( $this->id, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] );

			if ( $enemy_obstacles )	{

				foreach ( $enemy_obstacles as $enemy_id )	{

					if ( ! $enemy_army->soldier[$enemy_id]->isDead ( ) )	{

						$enemy_obstacle_position = $enemy_army->soldier[$enemy_id]->getPosition ( );

						if ( circleIntersectsLine ( $enemy_obstacle_position['x'], $enemy_obstacle_position['y'], SOLDIER_SIZE / 2, $this->x_pos, $this->y_pos, $enemy_position['x'], $enemy_position['y'] ) )	{

							$current_eo_distance = getDistance ( $enemy_obstacle_position['x'], $enemy_obstacle_position['y'], $this->x_pos, $this->y_pos );

							if ( isset ( $last_closest_eo_distance ) )	{

								if ( $current_eo_distance < $last_closest_eo_distance )	{

									$closest_target = $enemy_id;
									$last_closest_eo_distance = $current_eo_distance;

								}

							} else	{

								$last_closest_eo_distance = $current_eo_distance;
								$closest_target = $enemy_id;

							}

						}

					}

				}

				if ( isset ( $closest_target ) && ( ! $enemy_army->soldier[$closest_target]->isDead ( ) ) )	{
					$this->hit_target = $closest_target;
				} else	{
					$this->hit_target = $this->intended_target;
				}

			} else	{

				$this->hit_target = $this->intended_target;

			}

		}

		return $this->hit_target;

	}

	public function hit ( $enemy_soldier, $own_army )
	/* Function accepts as input an 'enemy_soldier' object. Function
	   computes the amount of damage the enemy soldier has inflicted on
	   this soldier. Rules:
		1. Maximum number of hit points possible: 20.
		2. Actual hit points are computed based on the following
		   criteria.
			a. The enemy soldier's actual experience. The more
			   experience points the enemy has, the more hit points
			   inflicted.
			b. This soldier's experience. The higher his experience,
			   the higher probability he soldier can dodge the
			   bullet to either minimize or avoid injury.
		3. Each soldier has a maximum of 100 health points. The number
		   of hit points computed are taken away from this soldiers
		   health points. If soldier reaches 0 points, the soldier is
		   dead.
		4. If this soldier survives the attack (more than 0 health
		   points after the attack), the soldier is awarded 1 experience
		   point.
		5. The enemy soldier is awarded experience points based on the
		   following criteria:
			a. 4 points maximum if this soldier is only wounded and
			   not killed.
			b. The number of points awareded will be decreased based
			   on the proportion of hit points inflicted compared to
			   the maximum. So, if 15 hit points are inflicted,
			   enemy soldier will be awarded 3 experience points.
			c. Enemy soldier will be awarded a flat 10 points if
			   this soldier is killed.
			d. Enemy solider will only receive half experience
			   points if he did not hit his intended target.
	   Function will return the number of hit points inflicted. */
	{

		if ( $this->isDead ( ) )	{
			$enemy_soldier->selectTarget ( $own_army );
			return 0;
		}

		$enemy_experience = $enemy_soldier->getExperience ();
		$enemy_target = $enemy_soldier->getIntendedTarget ();
		$hit_points = rand ( 5 * ( $enemy_experience / 100 ), 20 * ( $enemy_experience / 100 ) );
			// Compute basic hit points based on enemy soldier's
			// experience.

		if ( $hit_points > 20 )	{
			// Maximum amount of hit points allowed is 20.
			$hit_points = 20;

		}


		if ( $hit_points < 0 )	{
			// You cannot have negative hit points.
			$hit_points = 0;
		}

		if ( $hit_points )	{

			if ( $hit_points <= $this->health )	{
				$this->health -= $hit_points;
				$this->experience++;
					// 1 experience point awarded for
					// surviving an attack.
				$enemy_experience_awarded = 4 * ( $hit_points / 20 );
					// 4 experience points is basic when a
					// hit is landed. The amount of actual
					// experience points awarded based on
					// damage inflicted.

			} else	{

				$this->health = 0;
					// This soldier is dead.
				$enemy_experience_awarded = 10;
					// Award enemy soldier for the kill.

			}

			if ( $enemy_target != $this->id )	{
				$enemy_experience_awarded /= 2;
					// Half points for not hitting intended
					// target.

			}

			$enemy_soldier->awardExperience ( $enemy_experience_awarded );

		} else	{

			$this->experience++;
				// 1 experience point awarded for surviving an
				// attack.

		}

		return ( $hit_points );

	}

	public function getExperience ( )
	/* Function returns the current experience points for this soldier. */
	{

		return $this->experience;

	}

	public function getIntendedTarget ( )
	/* Function returns the current intended target for this soldier. */
	{

		return $this->intended_target;

	}

	public function awardExperience ( $experience_points )
	/* Function accepts as input experience points to be awarded. Function
	   will return the number of total experience points for this soldier */
	{

		$this->experience += $experience_points;

		return ( $this->experience );

	}

	public function getHealth ( )
	/* Function gets the current health of the soldier and returns that
	   value. */
	{

		return $this->health;

	}

}

?>
