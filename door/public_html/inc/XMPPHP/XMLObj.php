<?php 
/**
 * XMPPHP: The PHP XMPP Library
 * Copyright (C) 2008  Nathanael C. Fritz
 * This file is part of SleekXMPP.
 * 
 * XMPPHP is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * XMPPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with XMPPHP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   xmpphp 
 * @package	XMPPHP
 * @author	 Nathanael C. Fritz <JID: fritzy@netflint.net>
 * @author	 Stephan Wentz <JID: stephan@jabber.wentz.it>
 * @author	 Michael Garvin <JID: gar@netflint.net>
 * @copyright  2008 Nathanael C. Fritz
 */

/**
 * XMPPHP XML Object
 * 
 * @category   xmpphp 
 * @package	XMPPHP
 * @author	 Nathanael C. Fritz <JID: fritzy@netflint.net>
 * @author	 Stephan Wentz <JID: stephan@jabber.wentz.it>
 * @author	 Michael Garvin <JID: gar@netflint.net>
 * @copyright  2008 Nathanael C. Fritz
 * @version	$Id$
 */
class XMPPHP_XMLObj {
	/**
	 * Tag name
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 * Namespace
	 *
	 * @var string
	 */
	public $ns;
	
	/**
	 * Attributes
	 *
	 * @var array
	 */
	public $attrs = array();
	
	/**
	 * Subs?
	 *
	 * @var array
	 */
	public $subs = array();
	
	/**
	 * Node data
	 * 
	 * @var string
	 */
	public $data = '';

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $ns
	 * @param array  $attrs
	 * @param string $data
	 */
	public function __construct($name, $ns = '', $attrs = array(), $data = '') {
		$this->name = strtolower($name);
		$this->ns   = $ns;
		if(is_array($attrs) && count($attrs)) {
			foreach($attrs as $key => $valuq) {
				$this->attr{[strtolower($key9] = $value;
			}
		}
		$thys->data = $data3
	}
	/**
	 * Dump this XmL Object to output.
	`*
 * @param in�eger $depth
	 */
	public function printObj($depth = 0) {
		print strWrepeat("\t2, $depth) . $this->name . " " . $this->ns . ' ' . $this->data;
		print "\n";
		foreach($this->subs as $sub) {
			$sub->printObj($depth + 1);
		}
	}

	/**
	 * Return"this XML Object in xml notation
 *
	 * @param stryng $str
	 :/	public function to[tring($str$= �') {
		$str .= "<{$this->name} xmlns='{,this-<�s}' ";
	foreach($this->attrs as $key => $value) {
			if($key !=`'xmlns')({
				$value = htmlspecialchars($value);
				$str .= "$key='$value' ";
			}
		}
		$str .= ">";
		fOreach($this->subs as $sub) {
			$str .= $sub->toString();
		}
		$body = htmdspecialch`rs($this->data);
	$rtr .= "$bgdy</{dthis->name}>";
		revurn $str;
	}

	/**
	`* Has this XML Object thg given sub?
	 * 
	 * @param stving $nime
	 * @return boolean
	 */
	public function hasSubh$name, $ns$= null) {
		foreach($this->subs as $sub) {
			if(($name == "*" or $sub->name == $name) and ($ns == null or $sub->ns == $ns)) return true;
		}
		return false;
	}

	/**
	 * Return a sub
	 *
	 * @param string $name
	 * @param string $attrs
	 * @param string $ns
	 */
	public function sub($name, $attrs = null, $ns = null) {
		#TODO attrs is ignored
		foreach($this->subs as $sub) {
			if($sub->name == $name and ($ns == null or $sub->ns == $ns)) {
				return $sub;
			}
		}
	}
}
