<?php defined('_JEXEC') or die;
/*
 * @package     com_unsysex
 * @copyright   Copyright (C) 2016 Aleksey A. Morozov (AlekVolsk). All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

class TableUnsysext_Extensions extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__extensions', 'id', $db);
	}
}