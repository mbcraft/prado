<?php

class ActiveImageButtonTestCase extends PradoGenericSelenium2Test
{
	function test()
	{
		$base='ctl0_Content_';
		$this->url("active-controls/index.php?page=ActiveImageButtonTest");
		$this->assertContains("TActiveImageButton Functional Test", $this->source());
		$this->assertText("{$base}label1", "Label 1");
		$this->byId("{$base}image1")->click();
		$this->pause(800);
		//unable to determine mouse position
		$this->assertRegExp('/Image clicked at x=\d+, y=\d+/', $this->source());
	}
}
