<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\LeftMenu\tests;

use Piwik\Access;
use Piwik\Option;
use Piwik\Plugins\LeftMenu\API;
use Piwik\Plugins\LeftMenu\Settings;
use \FakeAccess;

/**
 * @group LeftMenu
 * @group APITest
 * @group Database
 */
class APITest extends \DatabaseTestCase
{
    /**
     * @var API
     */
    private $api;

    /**
     * @var Settings
     */
    private $settings;

    public function setUp()
    {
        parent::setUp();

        $this->api = API::getInstance();
        $this->createSettings();
    }

    public function test_isEnabled_shouldReturnFalse_ByDefault()
    {
        $this->assertLeftMenuIsDisabled();

        $this->setUser();
        $this->assertLeftMenuIsDisabled();

        $this->setSuperUser();
        $this->assertLeftMenuIsDisabled();
    }

    public function test_isEnabled_shouldReturnTrue_IfEnabledSystemWideAndNoUserPreference()
    {
        $this->enableLeftMenuForAll();

        $this->assertLeftMenuIsEnabled();

        $this->setUser();
        $this->assertLeftMenuIsEnabled();

        $this->setAnonymous();
        $this->assertLeftMenuIsEnabled();
    }

    public function test_isEnabled_AUserPreferenceShouldOverwriteASystemPreference_DefaultDisabled()
    {
        $this->assertLeftMenuIsDisabled();

        $this->setUser();
        $this->setUserSettingValue('yes');

        $this->assertLeftMenuIsEnabled();

        $this->setAnonymous();
        $this->assertLeftMenuIsDisabled();
    }

    public function test_isEnabled_AUserPreferenceShouldOverwriteASystemPreference_DefaultEnabled()
    {
        $this->enableLeftMenuForAll();

        $this->assertLeftMenuIsEnabled();

        $this->setUser();
        $this->setUserSettingValue('no');

        $this->assertLeftMenuIsDisabled();

        $this->setAnonymous();
        $this->assertLeftMenuIsEnabled();
    }

    private function assertLeftMenuIsEnabled()
    {
        $this->assertTrue($this->api->isEnabled());
    }

    private function assertLeftMenuIsDisabled()
    {
        $this->assertFalse($this->api->isEnabled());
    }

    private function setSuperUser()
    {
        $pseudoMockAccess = new FakeAccess;
        FakeAccess::$superUser = true;
        FakeAccess::$superUserLogin = 'superUserLogin';
        Access::setSingletonInstance($pseudoMockAccess);
        $this->createSettings();
    }

    private function setAnonymous()
    {
        Access::setSingletonInstance(null);
        $this->createSettings();
    }

    private function setUser()
    {
        $pseudoMockAccess = new FakeAccess;
        FakeAccess::$idSitesView = array(1);
        FakeAccess::$identity    = 'userLogin';
        Access::setSingletonInstance($pseudoMockAccess);
        $this->createSettings();
    }

    private function enableLeftMenuForAll()
    {
        $this->setSuperUser();
        $this->settings->globalEnabled->setValue(true);
        $this->settings->save();
    }

    private function createSettings()
    {
        $this->settings = new Settings('LeftMenu');
    }

    private function setUserSettingValue($value)
    {
        $this->settings->userEnabled->setValue($value);
        $this->settings->save();
    }
}
