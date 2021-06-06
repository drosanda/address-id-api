<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once $GLOBALS['SEMEDIR']->app_core.'ji_controller.php';
require_once $GLOBALS['SEMEDIR']->app_controller.'home.php';

final class HomeTest extends SeneTestCase
{
  public function __construct(){
    parent::__construct();
  }
  /**
  * @covers Home
  * @uses SENE_Controller
  */
  public function testIndex(): void
  {
    $expected = 'Thank you for using Seme Framework';
    $this->expectOutputString($expected);
    $calc = new Home();
    $calc->index();
  }
}
