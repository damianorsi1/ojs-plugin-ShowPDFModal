{**
* plugins/generic/filePreview/templates/filePreview.tpl
*
* Copyright (c) 2014-2018 Simon Fraser University
* Copyright (c) 2003-2018 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Document Markup gateway plugin settings
* TODO
*}

<script type="text/javascript">
$(function() {ldelim}
    // Attach the form handler.
    $('#filePreviewPdfForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
{rdelim});
</script>

<div id="filePreviewPdf">
    <iframe src="http://www.repositoriojmr.unla.edu.ar/descargar/TFI/EsEpi/029492_Perner.pdf" id="iframePDF" frameborder="0" scrolling="no" width="100%" height="700px"></iframe>
</div>