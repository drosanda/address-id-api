<?php declare(strict_types=1);
// Path to run ./vendor/bin/phpunit --bootstrap vendor/autoload.php FileName.php
// Butuh Framework PHPUnit
use PHPUnit\Framework\TestCase;

require_once $GLOBALS['SEMEDIR']->kero_sine.'SENE_Controller.php';

class SENE_Controller_Mock extends SENE_Controller {
  public function __construct(){
    parent::__construct();
  }
  public function index(){

  }
}

/**
 * @covers SENE_Controller
 */
final class SENE_Controller_Test extends TestCase
{
  public function __construct(){
    parent::__construct();
  }
  /**
  * Call protected/private method of a class.
  *
  * @param object &$object    Instantiated object that we will run method on.
  * @param string $methodName Method name to call
  * @param array  $parameters Array of parameters to pass into method.
  *
  * @return mixed Method return.
  */
  public function invokeMethod(&$object, $methodName, array $parameters = array())
  {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);
    return $method->invokeArgs($object, $parameters);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testTitle()
  {
    $tc = new SENE_Controller_Mock();
    $ts = "Welcome to Seme Framework"; // 4 Kata ..
    $this->invokeMethod($tc, 'setTitle', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getTitle', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getTitle', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testDescription()
  {
    $tc = new SENE_Controller_Mock();
    $ts = "Seme Framework is lightweight PHP MVC Framework for creating small and medium web application with fast delivery"; // 4 Kata ..
    $this->invokeMethod($tc, 'setDescription', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getDescription', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getDescription', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testLang()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'id-ID';
    $this->invokeMethod($tc, 'setLang', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getLang', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getLang', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testRobots()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'INDEX,FOLLOW';
    $this->invokeMethod($tc, 'setRobots', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getRobots', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getRobots', array()));
    $ts = 'NOINDEX,NOFOLLOW';
    $this->invokeMethod($tc, 'setRobots', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getRobots', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getRobots', array()));
    $ts = 'INDEX,NOFOLLOW';
    $this->invokeMethod($tc, 'setRobots', array($ts));
    $this->assertEquals('INDEX,NOFOLLOW', $this->invokeMethod($tc, 'getRobots', array()));
    $this->assertNotEquals(strtolower($ts), $this->invokeMethod($tc, 'getRobots', array()));
    $ts = 'INDEX';
    $this->invokeMethod($tc, 'setRobots', array($ts));
    $this->assertEquals('INDEX', $this->invokeMethod($tc, 'getRobots', array()));
    $this->assertNotEquals(strtolower(''), $this->invokeMethod($tc, 'getRobots', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testAuthor()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Seme Framework';
    $this->invokeMethod($tc, 'setAuthor', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getAuthor', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testIcon()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'favicon.ico';
    $this->invokeMethod($tc, 'setIcon', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getIcon', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testShortcutIcon()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'favicon.ico';
    $this->invokeMethod($tc, 'setShortcutIcon', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getShortcutIcon', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testKeyword()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Seme Framework';
    $this->invokeMethod($tc, 'setKeyword', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getKeyword', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testSession()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Seme Framework';
    $this->invokeMethod($tc, 'setKey', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getKey', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testCookie()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'is_login';
    $td = '1';
    $this->invokeMethod($tc, 'setcookie', array($ts,$td));
    $this->assertEquals($td, $this->invokeMethod($tc, 'getcookie', array($ts)));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testContentLanguage()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'en-ID';
    $this->invokeMethod($tc, 'setContentLanguage', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getContentLanguage', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testContentType()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'text/html; charset=utf-8';
    $this->invokeMethod($tc, 'setContentType', array($ts));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getContentType', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testTheme()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'admin';
    $dir = $GLOBALS['SEMEDIR']->app_view.'/'.$ts;
    if(is_dir($dir)) rmdir($dir);
    mkdir($dir);
    $this->invokeMethod($tc, 'setTheme', array($ts));
    $this->assertEquals($ts.'/', $this->invokeMethod($tc, 'getTheme', array()));
    if(is_dir($dir)) rmdir($dir);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testCanonicalEmpty()
  {
    $tc = new SENE_Controller_Mock();
    $td = '';
    $ts = base_url('');
    $this->invokeMethod($tc, 'setcanonical', array($td));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getcanonical', array()));

    $td = '/';
    $ts = base_url('');
    $this->invokeMethod($tc, 'setcanonical', array($td));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getcanonical', array()));

    $td = 'home';
    $ts = ('home/');
    $this->invokeMethod($tc, 'setcanonical', array($td));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getcanonical', array()));

    $td = 'home';
    $ts = ('home/');
    $this->invokeMethod($tc, 'setcanonical', array($td));
    $this->assertEquals($ts, $this->invokeMethod($tc, 'getcanonical', array()));
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testSetAdditional()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'test1.css';
    $this->invokeMethod($tc, 'setadditional', array($td));
    $this->assertContains($td, $tc->additional);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalPlain()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'skin/front/test2.css';
    $ts = 'skin/front/test2.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditional', array($td));
    $this->invokeMethod($tc, 'getadditional', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBaseUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url}}test3.css';
    $ts = base_url().'test3.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditional', array($td));
    $this->invokeMethod($tc, 'getadditional', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBaseUrlAdmin()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url_admin}}test4.css';
    $ts = base_url_admin().'test4.css';
    $this->expectOutputRegex('~\b'.$ts.'\b~');
    $this->invokeMethod($tc, 'setadditional', array($td));
    $this->invokeMethod($tc, 'getadditional', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalCdnUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{cdn_url}}test5.css';
    $ts = $this->invokeMethod($tc, 'cdn_url', array()).'test5.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditional', array($td));
    $this->invokeMethod($tc, 'getadditional', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testSetAdditionalBefore()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'test1.css';
    $this->invokeMethod($tc, 'setadditionalbefore', array($td));
    $this->assertContains($td, $tc->additionalBefore);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBeforePlain()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'skin/front/test2.css';
    $ts = 'skin/front/test2.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalbefore', array($td));
    $this->invokeMethod($tc, 'getadditionalbefore', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBeforeBaseUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url}}test3.css';
    $ts = base_url().'test3.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalbefore', array($td));
    $this->invokeMethod($tc, 'getadditionalbefore', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBeforeBaseUrlAdmin()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url_admin}}test4.css';
    $ts = base_url_admin().'test4.css';
    $this->expectOutputRegex('~\b'.$ts.'\b~');
    $this->invokeMethod($tc, 'setadditionalbefore', array($td));
    $this->invokeMethod($tc, 'getadditionalbefore', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalBeforeCdnUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{cdn_url}}test5.css';
    $ts = $this->invokeMethod($tc, 'cdn_url', array()).'test5.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalbefore', array($td));
    $this->invokeMethod($tc, 'getadditionalbefore', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testSetAdditionalAfter()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'test1.css';
    $this->invokeMethod($tc, 'setadditionalafter', array($td));
    $this->assertContains($td, $tc->additionalAfter);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalAfterPlain()
  {
    $tc = new SENE_Controller_Mock();

    $td = 'skin/front/test2.css';
    $ts = 'skin/front/test2.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalafter', array($td));
    $this->invokeMethod($tc, 'getadditionalafter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalAfterBaseUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url}}test3.css';
    $ts = base_url().'test3.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalafter', array($td));
    $this->invokeMethod($tc, 'getadditionalafter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalAfterBaseUrlAdmin()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{base_url_admin}}test4.css';
    $ts = base_url_admin().'test4.css';
    $this->expectOutputRegex('~\b'.$ts.'\b~');
    $this->invokeMethod($tc, 'setadditionalafter', array($td));
    $this->invokeMethod($tc, 'getadditionalafter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetAdditionalAfterCdnUrl()
  {
    $tc = new SENE_Controller_Mock();

    $td = '{{cdn_url}}test5.css';
    $ts = $this->invokeMethod($tc, 'cdn_url', array()).'test5.css';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'setadditionalafter', array($td));
    $this->invokeMethod($tc, 'getadditionalafter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testPutJsFooter()
  {
    $tc = new SENE_Controller_Mock();
    $td = 'skin/front/jquery.min.js';
    $ts = '<script src="'.$td.'"></script>';
    $this->invokeMethod($tc, 'putjsfooter', array($td,0));
    $this->assertContains($ts, $tc->js_footer);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testPutJsFooterWithoutExtension()
  {
    $tc = new SENE_Controller_Mock();
    $td = 'skin/front/jquery-1.12.4.min';
    $ts = '<script src="'.$td.'.js"></script>';
    $this->invokeMethod($tc, 'putjsfooter', array($td,0));
    $this->assertContains($ts, $tc->js_footer);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testPutJsFooterExternal()
  {
    $tc = new SENE_Controller_Mock();
    $td = 'https://code.jquery.com/jquery-3.6.0.min.js';
    $ts = '<script src="'.$td.'"></script>';
    $this->invokeMethod($tc, 'putjsfooter', array($td,1));
    $this->assertContains($ts, $tc->js_footer);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooter()
  {
    $tc = new SENE_Controller_Mock();
    $td = 'skin/front/test2';
    $ts = '<script src="'.$td.'.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterWithExtension()
  {
    $tc = new SENE_Controller_Mock();
    $td = 'skin/front/test2a.js';
    $ts = '<script src="skin/front/test2a.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterBaseUrl()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{base_url}}test3';
    $ts = '<script src="'.base_url().'test3.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterBaseUrlWithExtension()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{base_url}}test3a.js';
    $ts = '<script src="'.base_url().'test3a.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterBaseUrlAdmin()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{base_url_admin}}test4';
    $ts = '<script src="'.base_url_admin().'test4.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterBaseUrlAdminWithExtension()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{base_url_admin}}test4a.js';
    $ts = '<script src="'.base_url_admin().'test4a.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterCdnUrl()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{cdn_url}}test5';
    $ts = '<script src="'.$this->invokeMethod($tc, 'cdn_url', array()).'test5.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsFooterCdnUrlWithExtension()
  {
    $tc = new SENE_Controller_Mock();
    $td = '{{cdn_url}}test5a.js';
    $ts = '<script src="'.$this->invokeMethod($tc, 'cdn_url', array()).'test5a.js"></script>';
    $this->expectOutputRegex('~'.$ts.'~');
    $this->invokeMethod($tc, 'putjsfooter', array($td));
    $this->invokeMethod($tc, 'getjsfooter', array());
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testThemeScript()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'admin';
    $dir = $GLOBALS['SEMEDIR']->app_view.$ts;
    $file = $dir.'/'.$tc->js_json;
    $script = "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>";
    $json = array(
      $script,
      "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.5/jquery.min.js\"></script>"
    );
    if(is_dir($dir)) rmdir($dir);
    if(!is_dir($dir) && !file_exists($dir)) mkdir($dir);

    $this->invokeMethod($tc, 'setTheme', array($ts));
    $fh = fopen($file, "w");
    fwrite($fh,json_encode($json));
    fclose($fh);

    $this->assertContains($script, $this->invokeMethod($tc, 'getJsFooterBasic', array()));
    if(is_file($file)) unlink($file);
    if(is_dir($dir)) rmdir($dir);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testResetThemeContent()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__themeContent = $ts;
    $this->invokeMethod($tc, 'resetThemeContent', array());
    $this->assertNotEquals($ts, $tc->__themeContent);
    $this->assertEquals('', $tc->__themeContent);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetThemeContent()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__themeContent = $ts;
    $this->invokeMethod($tc, 'getThemeContent', array());
    $this->expectOutputString($ts);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetThemeRightContent()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__themeRightContent = $ts;
    $this->invokeMethod($tc, 'getThemeRightContent', array());
    $this->expectOutputString($ts);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetThemeLeftContent()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__themeLeftContent = $ts;
    $this->invokeMethod($tc, 'getThemeLeftContent', array());
    $this->expectOutputString($ts);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetBodyBefore()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__bodyBefore = $ts;
    $this->invokeMethod($tc, 'getBodyBefore', array());
    $this->expectOutputString($ts);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testGetJsContent()
  {
    $tc = new SENE_Controller_Mock();
    $ts = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    $tc->__jsContent = $ts;
    $this->invokeMethod($tc, 'getJsContent', array());
    $this->expectOutputString($ts);
  }

  /**
   * @uses SENE_Controller_Test
   * @uses SENE_Controller_Mock
   * @covers SENE_Controller
   */
  public function testCdnUrl()
  {
    $tc = new SENE_Controller_Mock();
    $tc->config->environment = '';
    $tc->config->cdn_url = 'https://seme-framework.b-cdn.net/';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar1.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'development';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar2.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'staging';
    $ts = '';
    $td = $tc->config->cdn_url;
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar3.png';
    $td = $tc->config->cdn_url.$ts;
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'production';
    $ts = '';
    $td = $tc->config->cdn_url;
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar4.png';
    $td = $tc->config->cdn_url.$ts;
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));


    $tc->config->environment = '';
    $tc->config->cdn_url = '123456';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar1a.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'development';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar2a.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'staging';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar3a.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));

    $tc->config->environment = 'production';
    $ts = '';
    $td = base_url();
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array()));

    $ts = 'media/skin/avatar4a.png';
    $td = base_url($ts);
    $this->assertEquals($td, $this->invokeMethod($tc, 'cdn_url', array($ts)));
  }
}
