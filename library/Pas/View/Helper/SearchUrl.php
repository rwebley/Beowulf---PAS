<?php
/**
 * A view helper for creating a url slug
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_SearchUrl extends Zend_View_Helper_Abstract  {
   
	
	public function buildUrlString($data){
	$parameters = $this->getParameters($data);
	return $this->view->url($parameters,'default',true);
	}
	
	public function getParameters($data){
		$section = $data['section'];
		$parameters = NULL;
		switch($section){
			case($section === 'news'):
				$parameters['module'] = 'news';
				$parameters['controller'] = 'stories';
				$parameters['action'] = 'article';
				$parameters['id'] = $data['id'];
			break;
			case($section === 'profiles'):
				$parameters['module'] = 'contacts';
				$parameters['controller'] = 'staff';
				$parameters['action'] = 'profile';
				$parameters['id'] = $data['id'];
			break;
			case($section === 'research'):
				$parameters['module'] = 'research';
				$parameters['controller'] = 'projects';
				$parameters['action'] = 'project';
				$parameters['id'] = $data['id'];
			break;
			case($section === 'events'):
				$parameters['module'] = 'events';
				$parameters['controller'] = 'info';
				$parameters['action'] = 'index';
				$parameters['id'] = $data['id'];
			break;
			case($section === 'treports'):
				$parameters['module'] = 'treasure';
				$parameters['controller'] = 'reports';
				$parameters['action'] = 'slug';
				$parameters['id'] = $data['slug'];
			break;
			default:
				$parameters['module'] = 'contacts';
				$parameters['controller'] = 'staff';
				$parameters['action'] = 'profiles';
				$parameters['id'] = $data['id'];
			}
			return $parameters;
		}
		
	public function searchUrl($data){
	if(is_array($data)){
		return $this->buildUrlString($data);
	} else {
		return false;
	}
	}
}