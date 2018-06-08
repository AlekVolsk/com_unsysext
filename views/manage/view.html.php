<?php defined('_JEXEC') or die;
/*
 * @package     com_unsysex
 * @copyright   Copyright (C) 2016 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

class UnsysextViewManage extends JViewLegacy
{
	public $items;
	public $pagination;
	public $state;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('\n', $errors));
			return false;
		}
		
		JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR, null, false, true);
		
		$canDo = JHelperContent::getActions('com_unsysext');
		if ($canDo->get('core.manage'))
		{
			if (count($this->items) > 0)
			{
				JToolbarHelper::publish('manage.publish', 'JTOOLBAR_PROTECT', true);
				JToolbarHelper::unpublish('manage.unpublish', 'JTOOLBAR_UNPROTECT', true);
			}
		}

		$custom_button_html = '<span style="display:inline-block;padding:0 10px;font-size:12px;line-height:25.5px;border:1px solid #d6e9c6;border-radius:3px;background-color:#dff0d8;color:#3c763d;">' . JText::sprintf('J_COUNT_ITEMS_VIEW', count($this->items)) . '</span>';
		JToolBar::getInstance('toolbar')->appendButton('Custom', $custom_button_html, 'options');
		
		JToolBarHelper::title(JText::_('COM_UNSYSEXT'), 'puzzle');
		
		parent::display( $tpl );
	}
}
