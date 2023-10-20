<?php

/**
 * @file plugins/generic/filePreview/FilePreviewPlugin.inc.php
 *
 * Copyright (c) 2003-2023 Simon Fraser University
 * Copyright (c) 2003-2023 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class FilePreviewPlugin
 * @ingroup plugins_generic_filepreview
 *
 * @brief File preview plugin
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

/**
 * Class FilePreviewPlugin
 */
class FilePreviewPlugin extends GenericPlugin {
	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {

		return __('plugins.generic.filePreview.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {

		return __('plugins.generic.filePreview.description');
	}

	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {

		if (parent::register($category, $path, $mainContextId)) {
			if ($this->getEnabled()) {
				// Register callbacks.
				HookRegistry::register('TemplateManager::fetch', array($this, 'templateFetchCallback'));

				$this->_registerTemplateResource();
			}
			return true;
		}
		return false;
	}


	/**
	 * Adds additional links to submission files grid row
	 * @param $hookName string The name of the invoked hook
	 * @param $params array Hook parameters
	 */
	public function templateFetchCallback($hookName, $params) {

		$request = $this->getRequest();
		$router = $request->getRouter();
		$dispatcher = $router->getDispatcher();

		$templateMgr = $params[0];
		$resourceName = $params[1];
		if ($resourceName == 'controllers/grid/gridRow.tpl') {
			$row = $templateMgr->getTemplateVars('row');
			$data = $row->getData();
			if (is_array($data) && (isset($data['submissionFile']))) {
				$submissionFile = $data['submissionFile'];
				$fileExtension = strtolower($submissionFile->getData('mimetype'));

				// get stage ID
				$stageId = (int)$request->getUserVar('stageId');
				//$fileStage = SUBMISSION_FILE_PRODUCTION_READY;

				if (strtolower($fileExtension) == 'application/pdf') {
					import('lib.pkp.classes.linkAction.request.OpenWindowAction');

					$this->_previewAction($row, $dispatcher, $request, $submissionFile, $stageId);
					$this->modalAction($row, $dispatcher, $request, $submissionFile, $stageId);

				}
				elseif (strtolower($fileExtension) == 'application/msword') {
					// CONVERTIR ARCHIVO A PDF Y MOSTRARLO
				}
				elseif (strtolower($fileExtension) == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
					// CONVERTIR ARCHIVO A PDF Y MOSTRARLO
				}
			}
		}
	}

	/**
	 * Add preview with file Preview action to files grid
	 * @param $row SubmissionFilesGridRow
	 * @param Dispatcher $dispatcher
	 * @param PKPRequest $request
	 * @param $submissionFile SubmissionFile
	 * @param int $stageId
	 */
	private function modalAction($row, Dispatcher $dispatcher, PKPRequest $request, $submissionFile, int $stageId): void {

		// Add grid-level actions
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');

		$actionArgs['path'] = "test-path";

		$row->addAction(
			new LinkAction(
				'filePreviewModal',
				new AjaxModal($router->url($request, null, null, 'filePreviewModal', null, array('verb' => 'preview')),
				$this->getDisplayName()),
				__('plugins.generic.filePreview.preview'),
				null
			),
		);
	}	

	public function filePreviewModal($args, $request){
		var_dump('LLEGOOOOO HASTA ACA');
		$context = $request->getContext();
		$contextId = ($context == null) ? 0 : $context->getId();

		// $templateMgr = TemplateManager::getManager();
		// $templateMgr->registerPlugin('function', 'plugin_url', array($this, 'smartyPluginUrl'));
		// echo('VERBOOOO:'.$request->getUserVar('verb'));
		// // TEST
		// $file = __DIR__ . '\salida.txt';
		// file_put_contents($file, serialize('VERBOOOO:'.$request->getUserVar('verb')));
		// // TEST

		$this->import('classes.form.FilePreviewForm');
		$form = new FilePreviewForm($this, $context);
		$form->initData();
		return new JSONMessage(true, $form->fetch($request));
		switch ($request->getUserVar('verb')) {
			case 'preview':
				$this->import('classes.form.FilePreviewForm');
				$form = new FilePreviewForm($this, $context);
				$form->initData();
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}

	/**
	 * Add preview with file Preview action to files grid
	 * @param $row SubmissionFilesGridRow
	 * @param Dispatcher $dispatcher
	 * @param PKPRequest $request
	 * @param $submissionFile SubmissionFile
	 * @param int $stageId
	 */
	private function _previewAction($row, Dispatcher $dispatcher, PKPRequest $request, $submissionFile, int $stageId): void {

		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');

		$row->addAction(new LinkAction(
				'download',
				new RedirectAction (
					$dispatcher->url($request, ROUTE_COMPONENT, null, 'api.file.FileApiHandler', 'downloadFile', null,
					array(
						'submissionFileId' => $submissionFile->getData('id'),
						'submissionId'=> $submissionFile->getData('submissionId'),
						'stageId'=> $stageId
					)
				)
			),
			__('plugins.generic.filePreview.download'),
			null
		));
	}

	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $actionArgs) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');

		return array_merge(
			array(
				new LinkAction(
					'status',
					new AjaxModal($router->url($request, null, null, 'manage', null,
					array(
						'verb' => 'preview',
						'plugin' => $this->getName(),
						'category' => 'generic')),
						$this->getDisplayName()),
					__('plugins.generic.filePreview.preview'),
					null
				),
			),
			parent::getActions($request, $actionArgs)
		);
		// error_log("Sigo de Largo");
	}

	function manage($args, $request){
		$context = $request->getContext();
		$contextId = ($context == null) ? 0 : $context->getId();

		switch ($request->getUserVar('verb')) {
			case 'preview':
				$this->import('classes.form.FilePreviewForm');
				$form = new FilePreviewForm($this, $context);
				$form->initData();
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}

	public function filePreviewForm($args, $request){
		$context = $request->getContext();
		$contextId = ($context == null) ? 0 : $context->getId();

		// error_log("Entre");

		$this->import('classes.form.FilePreviewForm');
		$form = new FilePreviewForm($this, $contextId);
		$form->initData();

		return new JSONMessage(true, $form->fetch($request));
	}
	
}
