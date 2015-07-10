<?php
/**
 * User edit / usage rights check functions
 * Determined by profiles / user permissions
 *
 * @see Users > User Profiles & User Permissions
 */

/**
 * Can Edit program check
 *
 * Always perform AllowEdit() check:
 * before displaying fields / options to edit data
 * AND before saving or updating data
 *
 * @param  string  $modname Specify program name (optional)
 *                          defaults to current program
 *
 * @return boolean          false if not allowed, true if allowed
 */
function AllowEdit( $modname = false )
{
	global $_ROSARIO;

	if ( User( 'PROFILE' ) === 'admin' )
	{
		if ( !$modname
			&& isset( $_ROSARIO['allow_edit'] ) )
			return $_ROSARIO['allow_edit'];

		if ( !$modname )
			$modname = $_REQUEST['modname'];

		// Student / User Info tabs
		if ( ( $modname === 'Students/Student.php'
			|| $modname === 'Users/User.php' )
			&& isset( $_REQUEST['category_id'] ) )
			$modname = $modname . '&category_id=' . $_REQUEST['category_id'];

		// get CAN_EDIT programs from database
		if ( !isset( $_ROSARIO['AllowEdit'] ) )
		{
			if ( User( 'PROFILE_ID' ) )
				$_ROSARIO['AllowEdit'] = DBGet( DBQuery( "SELECT MODNAME
					FROM PROFILE_EXCEPTIONS
					WHERE PROFILE_ID='" . User( 'PROFILE_ID' ) . "' AND CAN_EDIT='Y'" ), array(), array( 'MODNAME' ) );
			else
				$_ROSARIO['AllowEdit'] = DBGet( DBQuery( "SELECT MODNAME
					FROM STAFF_EXCEPTIONS
					WHERE USER_ID='" . User( 'STAFF_ID' ) . "' AND CAN_EDIT='Y'"), array(), array( 'MODNAME' ) );
		}

		if ( isset( $_ROSARIO['AllowEdit'][$modname] ) )
			return true;
		else
			return false;
	}
	else
		return $_ROSARIO['allow_edit'];
}


/**
 * Can Use program check
 *
 * @param  string  $modname Specify program name (optional)
 *                          defaults to current program
 *
 * @return boolean          false if not allowed, true if allowed
 */
function AllowUse( $modname = false )
{
	global $_ROSARIO;

	if ( !$modname )
		$modname = $_REQUEST['modname'];

	// Student / User Info tabs
	if ( ( $modname === 'Students/Student.php'
			|| $modname ==='Users/User.php' )
		&& isset( $_REQUEST['category_id'] ) )
		$modname = $modname . '&category_id=' . $_REQUEST['category_id'];

	// get CAN_USE programs from database
	if ( !isset( $_ROSARIO['AllowUse'] ) )
	{
		if ( User( 'PROFILE_ID' ) )
			$_ROSARIO['AllowUse'] = DBGet( DBQuery( "SELECT MODNAME
				FROM PROFILE_EXCEPTIONS
				WHERE PROFILE_ID='" . User( 'PROFILE_ID' ) . "' AND CAN_USE='Y'"), array(), array( 'MODNAME' ) );
		else
			$_ROSARIO['AllowUse'] = DBGet( DBQuery( "SELECT MODNAME
				FROM STAFF_EXCEPTIONS
				WHERE USER_ID='" . User( 'STAFF_ID' ) . "' AND CAN_USE='Y'" ), array(), array( 'MODNAME' ) );
	}

	if ( isset( $_ROSARIO['AllowUse'][$modname] ) )
		return true;
	else
		return false;
}

?>