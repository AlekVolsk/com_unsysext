<?php defined('_JEXEC') or die;
/*
 * @package     com_unsysex
 * @copyright   Copyright (C) 2016 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_unsysext&view=manage'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		
		<?php if (!count($this->items)) { ?>
		
		<div class="alert alert-warning"><?php echo JText::_('COM_UNSYSEXT_DATA_EMPTY_FROM_FILTER'); ?></div>
		
		<?php } else { ?>
		
		<table class="table table-striped" id="articleList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone center"><?php echo JHtml::_('grid.checkall'); ?></th>
					<th width="5%" class="center" style="min-width:55px;"><?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'protected', $listDirn, $listOrder); ?></th>
					<th class="nowrap"><?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_NAME', 'name', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_TYPE', 'type_translated', $listDirn, $listOrder); ?></th>
					<th class="hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_INSTALLER_HEADING_FOLDER', 'folder_translated', $listDirn, $listOrder); ?></th>
					<th width="1%" class="hidden-phone center nowrap"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'extension_id', $listDirn, $listOrder); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone"><?php echo JHtml::_('grid.id', $i, $item->extension_id); ?></td>
					<td class="center">
						<?php if (!$item->protected) { ?>
						<a class="btn btn-mini hasPopover" title="<?php echo JText::_('JSTATUS'); ?>" data-content="<?php echo JText::_('COM_UNSYSEXT_PROTECTED_OFF'); ?>" data-placement="bottom" onclick="return listItemTask('cb<?php echo $i; ?>','manage.publish');" href="javascript:void(0);"><span class="icon-unpublish"></span></a>
						<?php } else { ?>
						<a class="btn btn-mini hasPopover active" title="<?php echo JText::_('JSTATUS'); ?>" data-content="<?php echo JText::_('COM_UNSYSEXT_PROTECTED_ON'); ?>" data-placement="bottom" onclick="return listItemTask('cb<?php echo $i; ?>','manage.unpublish');" href="javascript:void(0);"><span class="icon-publish"></span></a>
						<?php } ?>
					</td>
					<td class="nowrap has-context"><?php echo $this->escape($item->name); ?></td>
					<td><?php echo $item->type_translated; ?></td>
					<td><?php echo $item->folder_translated; ?></td>
					<td class="center hidden-phone"><?php echo (int)$item->extension_id; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<?php echo $this->pagination->getListFooter(); ?>
		
		<?php } ?>
		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		
	</div>
</form>
