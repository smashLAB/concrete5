<? defined('C5_EXECUTE') or die("Access Denied."); ?>

<? $included = $permissionKey->getAssignmentList(); ?>
<? $excluded = $permissionKey->getAssignmentList(PermissionKey::ACCESS_TYPE_EXCLUDE); ?>
<?

$extensions = Loader::helper('concrete/file')->getAllowedFileExtensions();

?>
<? $form = Loader::helper('form'); ?>

<form id="ccm-file-set-permissions-add-file-form" onsubmit="return false" method="post" action="<?=$permissionKey->getPermissionKeyToolsURL()?>">

<? if (count($included) > 0 || count($excluded) > 0) { ?>

<div class="well clearfix">

<? if (count($included) > 0) { ?>

<h3><?=t('Who can add what?')?></h3>

<? foreach($included as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?=$entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?=$form->select('fileTypesIncluded[' . $entity->getAccessEntityID() . ']', array('1' => t('All File Types'), 'C' => t('Custom')), $assignment->getFileTypesAllowedPermission())?><br/><br/>
	<ul class="inputs-list" <? if ($assignment->getFileTypesAllowedPermission() != 'C') { ?>style="display: none"<? } ?>>
	<? foreach($extensions as $ext) {
		$checked = ($assignment->getFileTypesAllowedPermission() == 1 || ($assignment->getFileTypesAllowedPermission() == 'C' && in_array($ext, $assignment->getFileTypesAllowedArray())));
		?>
			<li><label><input type="checkbox" name="extensionInclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ext?>" <? if ($checked) { ?> checked="checked" <? } ?> /> <span><?=$ext?></span></label></li>
		<? } ?>
	</ul>
	</div>
</div>

<? }

} ?>


<? if (count($excluded) > 0) { ?>

<h3><?=t('Who can\'t add what?')?></h3>

<? foreach($excluded as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?=$entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?=$form->select('fileTypesExcluded[' . $entity->getAccessEntityID() . ']', array('0' => t('No File Types'), 'C' => t('Custom')), $assignment->getFileTypesAllowedPermission())?><br/><br/>
	<ul class="inputs-list" <? if ($assignment->getFileTypesAllowedPermission() != 'C') { ?>style="display: none"<? } ?>>
	<? foreach($extensions as $ext) {
		$checked = in_array($ext, $assignment->getFileTypesAllowedArray());
		?>
			<li><label><input type="checkbox" name="extensionExclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ext?>" <? if ($checked) { ?> checked="checked" <? } ?> /> <span><?=$ext?></span></label></li>
		<? } ?>
	</ul>
	</div>
</div>


<? }

} ?>


<input type="submit" class="btn primary ccm-button-right" onclick="$('#ccm-file-set-permissions-add-file-form').submit()" value="<?=t('Update Custom Settings')?>" />
</div>

<? } ?>

</form>

<script type="text/javascript">
$(function() {
	$("#ccm-file-set-permissions-add-file-form select").change(function() {
		if ($(this).val() == 'C') {
			$(this).parent().find('ul.inputs-list').show();
		} else {
			$(this).parent().find('ul.inputs-list').hide();
		}
	});
	
	$("#ccm-file-set-permissions-add-file-form").ajaxForm({
		beforeSubmit: function() {
			jQuery.fn.dialog.showLoader();
		},
		success: function(r) {
			jQuery.fn.dialog.hideLoader();
			jQuery.fn.dialog.closeTop();
		}
	});
});
</script>