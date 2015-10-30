<?php

interface com_wiris_quizzes_service_HttpResponse {
	function getHeader($name);
	function close();
	function writeString($s);
	function writeBinary($data);
	function sendError($num, $message);
	function setHeader($name, $value);
}
