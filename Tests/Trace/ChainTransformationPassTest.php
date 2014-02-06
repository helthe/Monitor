<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests\Trace;

use Helthe\Monitor\Trace\ChainTransformationPass;

class ChainTransformationPassTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $pass = new ChainTransformationPass(array(
           $this->getTransformationPassMock(),
           $this->getTransformationPassMock()
        ));

        $pass->transform(array());
    }

    /**
     * Get a TransformationPassInterface mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getTransformationPassMock()
    {
        $mock = $this->getMock('Helthe\Monitor\Trace\TransformationPassInterface');
        $mock->expects($this->once())->method('transform')->will($this->returnValue(array()));

        return $mock;
    }
}
