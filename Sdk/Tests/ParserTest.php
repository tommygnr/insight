<?php

/*
 * This file is part of the SensioLabsInsight package.
 *
 * (c) SensioLabs <contact@sensiolabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\Insight\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use SensioLabs\Insight\Sdk\Parser;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function getParseErrorsFailedIfDocumentIfInvalidTests()
    {
        return array(
            array(null),
            array(''),
            array('403'),
        );
    }

    /**
     * @expectedException \SensioLabs\Insight\Sdk\Exception\ApiParserException
     * @expectedExceptionMessage Could not transform this xml to a \DOMDocument instance.
     * @dataProvider getParseErrorsFailedIfDocumentIfInvalidTests
     */
    public function testParseErrorsFailedIfDocumentIfInvalid($xml)
    {
        $error = $this->parser->parseError($xml);
    }

    public function testParseErrors()
    {
        $xml = file_get_contents(__DIR__.'/fixtures/errors.xml');

        $error = $this->parser->parseError($xml);

        $expectedFields = array(
            'foo' => array(
                0 => 'This value should not be null.',
                1 => 'This value should not be blank.',
            ),
            'bar' => array(
                0 => 'This value should be equals to 6.',
            ),
        );

        $this->assertInstanceOf('SensioLabs\Insight\Sdk\Model\Error', $error);
        $this->assertSame($expectedFields, $error->getEntityBodyParameters());
    }

    public function tearDown()
    {
        $this->parser = null;
    }
}
