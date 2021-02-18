<?php
/**
*
* @package phpBB Extension - Antibanner
* @copyright (c) 2021 Татьяна5
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace tatiana5\antibanner\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	
	/** @var \phpbb\config\config $config Config object */
	protected $config;

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.twig_environment_render_template_after'	=> 'antibanner_func',
		);
	}

	/**
	* Constructor
	*/
	public function __construct(\phpbb\config\config $config)
	{
		$this->config = $config;
	}

	public function antibanner_func($event) {
		$output = $event['output'];

		$output = preg_replace_callback(
			'#<script(.*)src="(.*\.js)"(.*)><\/script>#',
			function ($matches) {
				return '<script' . $matches[1] . 'src="' . $matches[2] . '?assets_version=' . $this->config['assets_version'] . '"' . $matches[3] . '></script>';
			},
			$output
		);

		$event['output'] = $output;
	}
}
