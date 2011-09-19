<?php
/**
 * This class is to retrieve tweets and display them.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_AutoLink
 * @uses Zend_View_Helper_Url
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_LatestTweets 
	extends Zend_View_Helper_Abstract {

	protected $_cache;
	protected $_config;

	/** Constructor
	 * 
	 */
	public function __construct(){
	$this->_cache = Zend_Registry::get('rulercache');
	$this->_config = Zend_Registry::get('config');
	}
	
	/** Retrieve tokens (already generated elsewhere)
	 * @access private
	 * @return object $tokenList
	 */
	public function getTokens() {
	if (!$tokenList = $this->_cache->load('latestweets')) {
	$tokens = new OauthTokens();
	$tokenList = $tokens->fetchRow($tokens->select()->where('service = ?', 'twitterAccess'));
	$this->_cache->save($tokenList, 'latestweets');
	}
	return $tokenList;
	}

	/** Call Twitter after getting token for oauth
	 * 
	 */
	public function callTwitter() {
	$token = $this->getTokens();
	$token = unserialize($token->accessToken);
	$twitter = new Zend_Service_Twitter(array('username' => 'findsorguk','accessToken' =>  $token));
	$response = $twitter->status->userTimeline(array("count" => 2));
	return $this->buildHtml($response);
	}

	/** Build the html
	 * 
	 * @param array $response
	 */
	public function buildHtml($response){
	$html = '';
	$html .= '<ul>';
	foreach($response as $post){
	$html .= '<li>On <strong>'. date('m.d.y @ H:m:s',strtotime($post->created_at)) 
	. '</strong>, <strong><a href="http://www.twitter.com/'.$post->user->screen_name 
	. '">' . $post->user->screen_name . '</a></strong> said: '. $this->view->autoLink($post->text) 
	. '</li>';
	}
	$html .= '</ul>';
	return $html;
	}
	
	/** Call Twitter to get tweets
	 * 
	 */
	public function latestTweets() {
	return $this->callTwitter();
	}
	
	
}

