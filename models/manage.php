<?php defined('_JEXEC') or die;
/*
 * @package     com_unsysex
 * @copyright   Copyright (C) 2016 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR, null, false, true);
JLoader::register('InstallerModel', JPATH_ADMINISTRATOR . '/components/com_installer/models/extension.php');

class UnsysextModelManage extends InstallerModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'protected',
				'name',
				'type',
				'type_translated',
				'folder',
				'folder_translated',
				'extension_id'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'name', $direction = 'asc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
		$this->setState('filter.protected', $this->getUserStateFromRequest($this->context . '.filter.protected', 'filter_protected', '', 'string'));
		$this->setState('filter.type', $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '', 'string'));
		$this->setState('filter.folder', $this->getUserStateFromRequest($this->context . '.filter.folder', 'filter_folder', '', 'string'));
		parent::populateState($ordering, $direction);
	}

	public function publish(&$eid = array(), $value = 0)
	{
		if (!JFactory::getUser()->authorise('core.manage', 'com_unsysext'))
		{
			JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			return false;
		}

		$result = true;

		if (!is_array($eid))
		{
			$eid = array($eid);
		}

		$table = JTable::getInstance('Extension');
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_templates/tables');

		foreach ($eid as $i => $id)
		{
			$table->load($id);
			$table->protected = $value;
			if (!$table->store())
			{
				$this->setError($table->getError());
				$result = false;
			}
		}

		return $result;
	}

	protected function getListQuery()
	{
		$query = $this->getDbo()->getQuery(true)
			->select('extension_id, name, type, element, folder, protected, client_id, manifest_cache')
			->from('#__extensions');

		$protected = $this->getState('filter.protected');
		$type = $this->getState('filter.type');
		$folder = $this->getState('filter.folder');

		if ($protected !== '')
		{
			$query->where('protected = ' . (int)$protected);
		}

		if ($type)
		{
			$query->where('type = ' . $this->_db->quote($type));
		}

		if ($folder != '')
		{
			$query->where('folder = ' . $this->_db->quote($folder == '*' ? '' : $folder));
		}

		$search = $this->getState('filter.search');

		if (!empty($search) && stripos($search, 'id:') === 0)
		{
			$query->where('extension_id = ' . (int) substr($search, 3));
		}

		$listOrder = $this->getState('list.ordering', 'name');
		if ($listOrder == 'protected' || $listOrder == 'extension_id')
		{
			$listDirn  = $this->getState('list.direction', 'asc');
			$query->order($this->_db->quoteName($listOrder) . ' ' . $this->_db->escape($listDirn));
		}
		return $query;
	}
}
