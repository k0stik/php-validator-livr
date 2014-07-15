<?php

require 'vendor/autoload.php';

class TestSuite extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider positiveTestsProvider
     */
    public function testPositive($data, $test) {
        print "Positive test [$test] is run\n";

        $validator  = new Validator\LIVR( $data['rules'] );

        $output     = $validator->validate( $data['input'] );

        $this->assertFalse( $validator->getErrors(), 'Validator should contain no errors' );
        $this->assertEquals( $output, $data['output'], 'Validator should return validated data' );
    }

    /**
     * @dataProvider negativeTestsProvider
     */
    public function testNegative($data, $test) {
        print "Negative test [$test] is run\n";

        $validator  = new Validator\LIVR( $data['rules'] );
        $output     = $validator->validate( $data['input'] );

        $this->assertFalse($output ? true : false, 'Validator should return false');

        $this->assertEquals( $validator->getErrors(), $data['errors'], 'Validator should contain valid errors' );
    }

    public function positiveTestsProvider() {
        $dir = __DIR__ . '/test_suite/positive';

        $pull = array();

        if ( $handle = opendir( $dir ) ) {

            while ( false !== ( $entry = readdir($handle) ) ) {
                if ( $entry != "." && $entry != ".." ) {

                    $data = array(
                        'input'     => json_decode(file_get_contents("$dir/$entry/input.json"),  true),
                        'rules'     => json_decode(file_get_contents("$dir/$entry/rules.json"),  true),
                        'output'    => json_decode(file_get_contents("$dir/$entry/output.json"), true),
                    );

                    array_push($pull, [$data, $entry]);
                }
            }

            closedir($handle);
        }

        return $pull;
    }

    public function negativeTestsProvider() {
        $dir = __DIR__ . '/test_suite/negative';

        $pull = array();

        if ( $handle = opendir( $dir ) ) {

            while ( false !== ( $entry = readdir($handle) ) ) {
                if ( $entry != "." && $entry != ".." ) {

                    $data = array(
                        'input'     => json_decode(file_get_contents("$dir/$entry/input.json"),  true),
                        'rules'     => json_decode(file_get_contents("$dir/$entry/rules.json"),  true),
                        'errors'    => json_decode(file_get_contents("$dir/$entry/errors.json"), true),
                    );

                    array_push($pull, [$data, $entry]);
                }
            }

            closedir($handle);
        }

        return $pull;
    }
}

?>