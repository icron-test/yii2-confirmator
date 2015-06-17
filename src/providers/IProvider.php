<?php

namespace icron\confirmator\providers;

interface IProvider
{
    public function send($destination, $code);
}
