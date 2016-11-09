<?php
/* 
 * NOTICE OF LICENSE
 * 
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@q-sols.com so we can send you a copy immediately.
 * 
 * 
 *  @copyright   Copyright (c) 2010 Q-Solutions. (www.q-sols.com)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Description of model
 *
 * @author Qasim Shabbir <qasim@q-sols.com>
 */
abstract class Model{

    public function __get($key) {
        return Registry::getInstance()->get($key);
    }

    public function __set($key, $value) {
        Registry::getInstance()->set($key, $value);
    }
}
?>
