<?php

/**
 * @file plugins/generic/filePreview/classes/form/FilePreviewHandler.inc.php
 *
 * Copyright (c) University of Pittsburgh
 * Distributed under the GNU GPL v2 or later. For full terms see the LICENSE file.
 *
 * @ingroup plugins_generic_akismet
 * @brief Handles controller requests for Akismet plugin.
 */

import('classes.handler.Handler');

class FilePreviewHandler extends Handler {

	/**
	 * @copydoc GridHandler::initialize()
	 */
	function initialize($request, $args = null) {
        error_log("ESTOY EN ESTE LUGAR NUEVO 33333333333333333333333333333333333333");
		// Load grid locale for 'grid.user.cannotAdminister' error.
	}

	/**
	 * Handle filePreview action
	 * @param $args array Arguments array.
	 * @param $request PKPRequest Request object.
	 */
	function filePreview($args, $request) {
		$user = $request->getUser();

        error_log("ESTOY EN ESTE LUGAR NUEVO 33333333333333333333333333333333333333");
		// Ensure that we can administer this user
		if (!isset($args['userid']) || !Validation::canAdminister($args['userid'], $user->getId())) {
			return new JSONMessage(false, __('grid.user.cannotAdminister'));
		}
	}
}