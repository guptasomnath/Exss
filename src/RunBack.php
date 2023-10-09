<?php

namespace Exssah\Exss;

use React\EventLoop\Loop;
use React\Promise\Promise;

class RunBack
{
    //it will return a promise
    public static function run(object $callback)
    {
        $loop = Loop::get();
        $promise = new Promise(function ($resolve, $reject) use ($callback, $loop) {
            $loop->addTimer(0, function () use ($callback, $resolve, $reject) {
                $resolve($callback());
            });
        });

        return $promise;
    }
}
