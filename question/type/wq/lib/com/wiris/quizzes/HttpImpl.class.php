<?php

class com_wiris_quizzes_HttpImpl extends haxe_Http {
	public function __construct($url) {
		if(!isset($this->onData)) $this->onData = array(new _hx_lambda(array(&$this, &$url), "com_wiris_quizzes_HttpImpl_0"), 'execute');
		if(!isset($this->onError)) $this->onError = array(new _hx_lambda(array(&$this, &$url), "com_wiris_quizzes_HttpImpl_1"), 'execute');
		if(!php_Boot::$skip_constructor) {
		parent::__construct($url);
	}}
	public $response;
	public function onData($data) { return call_user_func_array($this->onData, array($data)); }
	public $onData = null;
	public function onError($msg) { return call_user_func_array($this->onError, array($msg)); }
	public $onError = null;
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'com.wiris.quizzes.HttpImpl'; }
}
function com_wiris_quizzes_HttpImpl_0(&$�this, &$url, $data) {
	{
		$�this->response = $data;
	}
}
function com_wiris_quizzes_HttpImpl_1(&$�this, &$url, $msg) {
	{
		throw new HException($msg);
	}
}
