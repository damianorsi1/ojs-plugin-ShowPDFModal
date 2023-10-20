<?php

/** 
*@file FilePreviewForm. inc.plp
*
*copyright (c) 2015-2019 University of Pittsburgh
*copyright (c) 2014-2021 Simon Fraser University
*copyright (c) 2003-2021 John willinsky
*Distributed under the GNU GPL v3. For full terms see the file docs/COPVING.
*
*@class FilePreviewForm
*@ingroup plugins_generic_filePreview
*
*abrief Form for registered users to preview files
*/

import('lib.pkp.classes.form.Form');

class FilePreviewForm extends Form {

    /** @var $contextId int */
    var $contextId;

    /** @var $plugin object */
    var $plugin;

    /**
     * Constructor
     * @param $plugin object
     * @param $contextId int
     */
    function __construct($plugin, $contextId) {
        $this->contextId = $contextId;
        $this->plugin= $plugin;
        parent::__construct($plugin->getTemplateResource('filePreview.tpl'));

    }

    /**
     * Initialize form data.
     */
    function initData(){
        $contextId = $this->contextId;
        $plugin = $this->plugin;
        $this->_data = array();
    }

    /**
     *  Fetch the form.
     * @copydoc Form::fetch()
     */
    function fetch($request, $template = null, $display = false){
        $contextId = $request->getContext()->getId();
        // $clienteId = $this->plugin->getSetting($contextId, 'orcidClienteId');
        // $clienteSecret = $this->plugin->getSetting($contextId, 'orcidClienteSecret');

        $templateMgr = TemplateManager::getManager($request);
        $aboutUrl = $request->getDispatcher()->url($request, ROUTE_PAGE, null, 'orcidapi', 'about', null);
        $templateMgr->assign(array(
            'globallyConfigure' => "test",
            'orcidAboutUrl' => "test",
        ));
        return parent::fetch($request, $template, $display);
    }
}