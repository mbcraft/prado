<?php

class MyTestCase extends PradoGenericSelenium2Test
{
	function test1()
	{
		$this->url('http://127.0.0.1');
		$this->assertNotContains('asd', $this->source());
	}
}
