<?php
/**
 * ActiveRatingListTestCase.php
 *
 * @author Bradley Booms <Bradley.Booms@nsighttel.com>
 * @version Creation Date: Oct 22, 2008
 */

/**
 * ActiveRatingListTestCase.php class
 *
 *
 *
 * Properties
 * -
 *
 * @author Bradley Booms <Bradley.Booms@nsighttel.com>
 * @version Modified Date: Oct 22, 2008
 *
 * Modifications:
 */
class ActiveRatingListTestCase extends PradoGenericSelenium2Test
{
	function testCheckBoxes()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListCheckBoxesTest");
		$this->assertContains("TActiveRatingList Check Boxes Test Case", $this->source());
		$this->assertCheckBoxes("{$base}RatingList", array(2), 6);

		// Change the list and make sure the radio buttons get updated properly.
		$this->clickTD("{$base}RatingList_c4");
		$this->pause(800);
		$this->assertCheckBoxes("{$base}RatingList", array(4), 6);

		$this->clickTD("{$base}RatingList_c2");
		$this->pause(800);
		$this->assertCheckBoxes("{$base}RatingList", array(2), 6);
	}

	function testRating()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListRatingTest");
		$this->assertContains("TActiveRatingList Rating Test Case", $this->source());

		// Check the list, make sure it starts out with 5 stars.
		$this->assertText("{$base}Status", "Rating: 5");

		// Click on 1 star and make sure the Rating property updates.
		$this->clickTD("{$base}RatingList_c0");
		$this->pause(800);
		$this->assertText("{$base}Status", "Rating: 1");

		// Then set Rating to three on the server side and make sure it's correct.
		$this->byId("{$base}SetRating")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "Rating: 3");
	}

	function testSelectedIndex()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListSelectedIndexTest");
		$this->assertContains("TActiveRatingList SelectedIndex Test Case", $this->source());
		$this->assertText("{$base}Status", "SelectedIndex: 1");

		// Click on 5 stars and make sure the SelectedIndex property updates.
		$this->clickTD("{$base}RatingList_c4");
		$this->pause(800);
		$this->assertText("{$base}Status", "SelectedIndex: 4");

		// Then set SelectedIndex to 5 on the server side and make sure it's correct.
		$this->byId("{$base}SetSelectedIndex")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "SelectedIndex: 5");
	}

	function testAutoPostBack()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListAutoPostBackTest");
		$this->assertContains("TActiveRatingList AutoPostBack Test Case", $this->source());
		$this->assertText("{$base}Status", "AutoPostback=false");

		// Make sure that it doesn't auto post when clicked.
		$this->clickTD("{$base}RatingList_c3");
		$this->pause(800);
		$this->assertText("{$base}Status", "AutoPostback=false");

		// Then submit with an active button and make sure it updates.
		$this->byId("{$base}Submit")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "4 : Good");
	}

	function testAllowInput()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListAllowInputTest");
		$this->assertContains("TActiveRatingList AllowInput Test Case", $this->source());
		$this->assertText("{$base}Status", "AllowInput=false");
		$this->assertCheckBoxes("{$base}RatingList", array(3), 6);

		// Make sure that clicking doesn't change anything.
		$this->clickTD("{$base}RatingList_c5");
		$this->pause(800);
		$this->assertText("{$base}Status", "AllowInput=false");
		$this->assertCheckBoxes("{$base}RatingList", array(3), 6);
	}

	function testReadOnly()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListReadOnlyTest");
		$this->assertContains("TActiveRatingList ReadOnly Test Case", $this->source());
		$this->assertText("{$base}Status", "ReadOnly=true");
		$this->assertCheckBoxes("{$base}RatingList", array(0), 6);

		$this->clickTD("{$base}RatingList_c4");
		$this->pause(800);
		$this->assertText("{$base}Status", "ReadOnly=true");
		$this->assertCheckBoxes("{$base}RatingList", array(0), 6);

		// Then set ReadOnly to false, and make sure it works.
		$this->byId("{$base}Writable")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "ReadOnly=false");
		$this->assertCheckBoxes("{$base}RatingList", array(0), 6);


		$this->clickTD("{$base}RatingList_c1");
		$this->pause(800);
		$this->assertText("{$base}Status", "2 : Fair");
		$this->assertCheckBoxes("{$base}RatingList", array(1), 6);

		// Then set ReadOnly to true, and make sure it doesn't work anymore.
		$this->byId("{$base}ReadOnly")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "ReadOnly=true");
		$this->assertCheckBoxes("{$base}RatingList", array(1), 6);


		$this->clickTD("{$base}RatingList_c2");
		$this->pause(800);
		$this->assertText("{$base}Status", "ReadOnly=true");
		$this->assertCheckBoxes("{$base}RatingList", array(1), 6);
	}

	function testEnabled()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListEnabledTest");
		$this->assertContains("TActiveRatingList Enabled Test Case", $this->source());
		$this->assertText("{$base}Status", "Enabled=false");
		$this->assertCheckBoxes("{$base}RatingList", array(5), 6);

		$this->clickTD("{$base}RatingList_c4");
		$this->pause(800);
		$this->assertText("{$base}Status", "Enabled=false");
		$this->assertCheckBoxes("{$base}RatingList", array(5), 6);

		// Then set Enable to true, and make sure it works.
		$this->byId("{$base}Enable")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "Enabled=true");
		$this->assertCheckBoxes("{$base}RatingList", array(5), 6);


		$this->clickTD("{$base}RatingList_c3");
		$this->pause(800);
		$this->assertText("{$base}Status", "4 : Good");
		$this->assertCheckBoxes("{$base}RatingList", array(3), 6);

		// Then set Enable to false, and make sure it doesn't work anymore.
		$this->byId("{$base}Disable")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "Enabled=false");
		$this->assertCheckBoxes("{$base}RatingList", array(3), 6);


		$this->clickTD("{$base}RatingList_c5");
		$this->pause(800);
		$this->assertText("{$base}Status", "Enabled=false");
		$this->assertCheckBoxes("{$base}RatingList", array(3), 6);
	}

	function testHoverCaption()
	{
		$base='ctl0_Content_';
		// Verify we're on the right page.
		$this->url("active-controls/index.php?page=ActiveRatingListHoverCaptionTest");
		$this->assertContains("TActiveRatingList Hover Caption Test Case", $this->source());
		$this->assertText("{$base}Status", "CaptionID='Status'");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c0']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c1']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c2']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[contains(@class, 'rating_half')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c4']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c5']/../../../td[contains(@class, 'rating')]");

		$this->moveto($this->byXPath("//input[@id='{$base}RatingList_c4']/../.."));
		$this->assertText("{$base}Status", "Excellent");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c0']/../../../td[contains(@class, 'rating_hover')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c1']/../../../td[contains(@class, 'rating_hover')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c2']/../../../td[contains(@class, 'rating_hover')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[contains(@class, 'rating_hover')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c4']/../../../td[contains(@class, 'rating_hover')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c5']/../../../td[not(contains(@class, 'rating_hover'))]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c5']/../../../td[contains(@class, 'rating')]");

		$this->moveto($this->byCssSelector('body'));
		$this->assertText("{$base}Status", "CaptionID='Status'");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c0']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c1']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c2']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[not(contains(@class, 'rating_selected'))]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c4']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c5']/../../../td[contains(@class, 'rating')]");


		$this->moveto($this->byXPath("//input[@id='{$base}RatingList_c1']/../.."));
		$this->assertText("{$base}Status", "Fair");

		$this->byXPath("//input[@id='{$base}RatingList_c1']/../..")->click();
		$this->pause(800);
		$this->assertText("{$base}Status", "2 : Fair");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c0']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c1']/../../../td[contains(@class, 'rating_selected')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c2']/../../../td[not(contains(@class, 'rating_selected'))]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c2']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c3']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c4']/../../../td[contains(@class, 'rating')]");
		$this->assertElementPresent("//input[@id='{$base}RatingList_c5']/../../../td[contains(@class, 'rating')]");
	}

	function clickTD($clientID){
		$this->byXPath("//input[@id='{$clientID}']/../..")->click();
	}

	function assertCheckBoxes($clientID, $checks, $total = 5)
	{
		for($i = 0; $i < $total; $i++)
		{
			if(in_array($i, $checks))
				$this->assertTrue($this->byId("{$clientID}_c{$i}")->selected());
			else
				$this->assertFalse($this->byId("{$clientID}_c{$i}")->selected());
		}
	}
}