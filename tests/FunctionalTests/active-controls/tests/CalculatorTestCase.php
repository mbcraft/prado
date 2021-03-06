<?php

class CalculatorTestCase extends PradoGenericSelenium2Test
{
	function test()
	{
		$base='ctl0_Content_';
		$this->url("active-controls/index.php?page=Calculator");
		$this->assertContains("Callback Enabled Calculator", $this->source());
		$this->assertNotVisible("{$base}summary");

		$this->byId("{$base}sum")->click();
		$this->assertVisible("{$base}summary");

		$this->type("{$base}a", "2");
		$this->type("{$base}b", "5");

		$this->byId("{$base}sum")->click();
		$this->pause(500);

		$this->assertNotVisible("{$base}summary");
		$this->assertValue("{$base}c", "7");
	}
}
