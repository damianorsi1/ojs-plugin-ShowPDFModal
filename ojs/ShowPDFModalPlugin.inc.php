<?php

/**
 * @file plugins/generic/showPDFModal/ShowPDFModalPlugin.inc.php
 *
 * Copyright (c) 2023-2023 Universidad Nacional Lanus
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ShowPDFModalPlugin
 *
 * @brief This plugin can display an article modal in PDF format
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ShowPDFModalPlugin extends GenericPlugin {
	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {
		$success = parent::register($category, $path, $mainContextId);
		// if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return $success;

		if ($success && $this->getEnabled($mainContextId)) {
			// HookRegistry::register('Templates::Article::Footer::PageFooter', array($this, 'callbackTemplateArticlePageFooter'));
		}
		return $success;
	}

	/**
	 * Install default settings on context creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * @copydoc Plugin::getDisplayName
	 */
	function getDisplayName() {
		return __('plugins.generic.showPDFModal.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription
	 */
	function getDescription() {
		return __('plugins.generic.showPDFModal.description');
	}

	/**
	 * @see LazyLoadPlugin::getName()
	 */
	function getName() {
		return 'ShowPDFModalPlugin';
	}

}