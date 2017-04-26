<?php

namespace Quicktech\CargonizerTest;

use PHPUnit\Framework\TestCase;
use Quicktech\Cargonizer\Util\XMLValidator;

class XMLValidatorTest extends TestCase
{
    /**
     * @test
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /.it is empty./
     */
    public function empty_xml_should_throw_invalid_argument_exception()
    {
        $validator = new XmlValidator();
        $validator->validate(null, null);
    }

    /**
     * @test
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /.valid path to XSD file./
     */
    public function invalid_schema_path_should_throw_invalid_argument_exception()
    {
        $validator = new XmlValidator();
        $validator->validate('<xml>test</xml>', null);
    }

    /**
     * @test
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Document types are not allowed.
     */
    public function doctype_should_throw_invalid_argument_exception()
    {
        $validator = new XmlValidator();
        $validator->validate('<!DOCTYPE html><xml>test</xml>', null);
    }

    /**
     * @test
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /.Expected is \( item2 \)./
     */
    public function invalid_xml_should_throw_invalid_argument_exception()
    {
        $xml = '<?xml version="1.0" ?>
            <root>
                <item1>foo1</item1>
            </root>
        ';

        $schemaFile = __DIR__ . '/../resources/schemas/test.xsd';

        $validator = new XmlValidator();
        $validator->validate($xml, $schemaFile);
    }

    /**
     * @test
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /.is not a valid value./
     */
    public function invalid_value_schema_should_throw_invalid_argument_exception()
    {
        $xml = '<?xml version="1.0" ?>
            <root>
                <item1>foo1</item1>
                <item2>invalid</item2>
            </root>
        ';

        $schemaFile = __DIR__ . '/../resources/schemas/test.xsd';

        $validator = new XmlValidator();
        $validator->validate($xml, $schemaFile);
    }
}