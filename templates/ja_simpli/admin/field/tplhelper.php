<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;
JFormHelper::loadFieldClass('moduleposition');
/**
 * Form Field class for the Joomla Framework.
 *
 * @since  2.5
 */
class JFormFieldTplhelper extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'TplHelper';
	protected $task = '';
	protected $html = '';

	protected function getInput()
	{

		if (isset($this->element['task'])) $this->task = $this->element['task'];
		if (!$this->task) $this->task = 'default';

		$func = $this->task . 'Task';
		if (method_exists($this, $func)) {
			$this->$func();
		}

		return $this->html;
	}

	/**
	 * Method to get the field label markup for a spacer.
	 * Use the label text or name from the XML element as the spacer or
	 * Use a hr="true" to automatically generate plain hr markup
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		// use this indicator to hide the empty control generated by tplhelper
		return '<span class="hide tplhelper"></span>';
	}

	protected function defaultTask () {		
		// get template name
		$path = str_replace (JPATH_ROOT, '', dirname(__DIR__));
		$path = str_replace ('\\', '/', substr($path, 1));

		$doc = JFactory::getDocument();
		$doc->addStyleSheet (JUri::root() . $path . '/assets/css/style.css');
		$doc->addScript (JUri::root() . $path . '/assets/js/script.js');
		
		// input content
		$this->html = '<input type="hidden" id="tplhelper" name="' . $this->name . '" value="' . htmlspecialchars($this->value) . '" />';
	}

	// Add assets (js,css)
	protected function assetTask () {
		$doc = JFactory::getDocument();
		$path = str_replace (JPATH_ROOT, '', dirname(__DIR__));
		$path = str_replace ('\\', '/', substr($path, 1)) . '/assets/';

		// add script
		$js = isset($this->element['js']) ? explode(',', $this->element['js']) : null;

		if (is_array($js)) {
			foreach ($js as $name) {
				$doc->addScript (JUri::root() . $path . 'js/' . $name . '.js');
			}
		}
		// add css
		$css = isset($this->element['css']) ? explode(',', $this->element['css']) : null;
		if (is_array($css)) {
			foreach ($css as $name) {
				$doc->addStyleSheet (JUri::root() . $path . 'css/' . $name . '.css');
			}
		}
	}

	// include html
	protected function htmlTask() {
		$file = isset($this->element['file']) ? $this->element['file'] : null;
		if (is_file(JPATH_ROOT . '/' . $file)) {
			$this->html = file_get_contents(JPATH_ROOT . '/' . $file);
		}
	}


}
