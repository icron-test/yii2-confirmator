<?php
namespace tests;

use icron\confirmator\providers\IProvider;

class TestProvider implements IProvider
{
    public function send($destination, $code) {
        return true;
    }
}
 