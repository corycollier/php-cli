<?php

namespace PhpCli;

use PhpCli\Output;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the write method.
     *
     * @param string $message The message to write.
     * @param array $params An array of params to use in testing.
     *
     * @dataProvider dataWrite
     */
    public function testWrite($message, $params = array())
    {
        $sut = $this->getMockBuilder('\PhpCli\Output')
            ->setMethods(array(
                'getMergedWriteParams',
                'getMessagePrefix',
                'getDecoratedMessage',
                'getReset',
            ))
            ->getMock();

        $sut->expects($this->once())
            ->method('getMergedWriteParams')
            ->with($this->equalTo($params))
            ->will($this->returnValue($params));
        $sut->expects($this->once())
            ->method('getMessagePrefix')
            ->with($this->equalTo($params))
            ->will($this->returnValue(''));
        $sut->expects($this->once())
            ->method('getDecoratedMessage')
            ->with($this->equalTo($message), $this->equalTo($params))
            ->will($this->returnValue(''));

        $sut->expects($this->once())
            ->method('getReset')
            ->will($this->returnValue(''));

        $result = $sut->write($message, $params);
        $this->assertEquals($sut, $result);
    }

    /**
     * Tests the getMergedWriteParams method.
     *
     * @param arary $expected The expected result of the test.
     * @param array $params The input params to test with.
     *
     * @dataProvider dataGetMergedWriteParams
     */
    public function testGetMergedWriteParams($expected, $params)
    {
        $sut = new Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getMergedWriteParams');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getDecoratedMessage method.
     *
     * @param string $expected The expected result of the test.
     * @param string $message The string to decorate
     * @param array $params An array of parameters to use.
     *
     * @dataProvider dataGetDecoratedMessage
     */
    public function testGetDecoratedMessage($expected, $message, $params = array())
    {
        $sut = new \PhpCli\Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getDecoratedMessage');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $message, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getMessagePrefix method.
     *
     * @param string $expected The expected result of the test.
     * @param array $params An array of parameters to use in testing.
     *
     * @dataProvider dataGetMessagePrefix
     */
    public function testGetMessagePrefix($expected, $params = array())
    {
        $sut = new Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getMessagePrefix');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getReset methods
     */
    public function testGetReset()
    {
        $expected = "\033[0m";
        $sut      = new Output;
        $result = $sut->getReset();

        $this->assertEquals($expected, $result);
    }

    /**
     * Data Provider for testing the write method.
     *
     * @return array
     */
    public function dataWrite()
    {
        return array(

            // simple message, simple params
            'simple message, simple params' => array(
                'message' => 'message',
                'params'  => array(),
            ),

        );
    }

    /**
     * Tests the getMergedWriteParams
     * @return [type] [description]
     */
    public function dataGetMergedWriteParams()
    {
        $defaults = array(
            'background' => false,
            'color'      => false,
            'bold'       => false,
            'underline'  => false,
        );

        return array(

            // empty test
            'empty test' => array(
                'expected' => array_merge($defaults, array()),
                'params'   => array(),
            ),

            // backgroundtest
            'has background' => array(
                'expected' => array_merge($defaults, array(
                    'background' => 'value',
                )),
                'params'   => array(
                    'background' => 'value',
                ),
            ),

            // color test
            'has color' => array(
                'expected' => array_merge($defaults, array(
                    'color' => 'value',
                )),
                'params'   => array(
                    'color' => 'value',
                ),
            ),

            // color test
            'is bold' => array(
                'expected' => array_merge($defaults, array(
                    'bold' => true,
                )),
                'params'   => array(
                    'bold' => true,
                ),
            ),

        );
    }

    /**
     * Data provider for testing getDecoratedMessage
     * @return array
     */
    public function dataGetDecoratedMessage()
    {
        return array(
            // simple test
            'simple test' => array(
                'expected' => 'message',
                'message'  => 'message',
                'params'   => array(),
            ),
            // red color
            'red color' => array(
                'expected' => '31mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'red'
                ),
            ),
            // green color
            'green color' => array(
                'expected' => '32mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'green'
                ),
            ),
            // yellow color
            'yellow color' => array(
                'expected' => '33mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'yellow'
                ),
            ),
            // blue color
            'blue color' => array(
                'expected' => '34mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'blue'
                ),
            ),
            // magenta color
            'magenta color' => array(
                'expected' => '35mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'magenta'
                ),
            ),
            // cyan color
            'cyan color' => array(
                'expected' => '36mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'cyan'
                ),
            ),
            // light gray color
            'light gray color' => array(
                'expected' => '37mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light gray'
                ),
            ),
            // dark gray color
            'dark gray color' => array(
                'expected' => '90mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'dark gray'
                ),
            ),
            // light red color
            'light red color' => array(
                'expected' => '91mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light red'
                ),
            ),
            // light green color
            'light green color' => array(
                'expected' => '92mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light green'
                ),
            ),
            // light yellow color
            'light yellow color' => array(
                'expected' => '93mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light yellow'
                ),
            ),
            // light blue color
            'light blue color' => array(
                'expected' => '94mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light blue'
                ),
            ),
            // light magenta color
            'light magenta color' => array(
                'expected' => '95mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light magenta'
                ),
            ),
            // light cyan color
            'light cyan color' => array(
                'expected' => '96mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'light cyan'
                ),
            ),
            // white color
            'white color' => array(
                'expected' => '97mmessage',
                'message'  => 'message',
                'params'   => array(
                    'color' => 'white'
                ),
            ),

        );
    }

    /**
     * Data provider for testing getMessagePrefix.
     * @return array
     */
    public function dataGetMessagePrefix()
    {
        return array(
            // expect null, empty params
            'expect null, empty params' => array(
                'expected' => null,
                'params' => array(),
            ),
            // expect null, empty params
            'expect prefix, NOT empty params' => array(
                'expected' => "\033[",
                'params' => array(
                    'key' => 'value',
                ),
            ),
        );
    }

    /**
     * Tests the PhpCli\Output::newline method.
     */
    public function testNewline()
    {
        $sut = new \PhpCli\Output;
        $result = $sut->newline();
        $this->assertEquals($sut, $result);
    }
}
