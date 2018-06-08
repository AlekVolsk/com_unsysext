<?php defined('_JEXEC') or die;
/*
 * @package     com_unsysex
 * @copyright   Copyright (C) 2016 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

class UnsysextControllerManage extends JControllerLegacy
{
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unpublish', 'publish');
		$this->registerTask('publish', 'publish');
	}

	public function publish()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids = $this->input->get('cid', array(), 'array');
		$values = array('publish' => 1, 'unpublish' => 0);
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_INSTALLER_ERROR_NO_EXTENSIONS_SELECTED'));
		}
		else
		{
			$model = $this->getModel('manage');
			if (!$model->publish($ids, $value))
			{
				JError::raiseWarning(500, implode('<br />', $model->getErrors()));
			}
			else
			{
				if ($value == 1)
				{
					$ntext = 'COM_UNSYSEXT_PROTECTED_MSG_PROTECTED';
				}
				elseif ($value == 0)
				{
					$ntext = 'COM_UNSYSEXT_PROTECTED_MSG_UNPROTECTED';
				}
				$this->setMessage(JText::plural($ntext, count($ids)));
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_unsysext&view=manage', false));
	}
}