<?php
// Here you can initialize variables that will be available to your tests
class ApiTesterEx extends ApiTester{

    use \App\Tests\SendAPITrait;
    protected $entry = "/rice";

}