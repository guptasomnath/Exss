<?php

namespace Exssah\Exss;

use function React\Async\async as asyncFun;
use function React\Async\await as awaitFun;

use React\Socket\SocketServer;
use React\Http\HttpServer;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\Promise;

class Exss
{

    private static $server;
    private static $callBackFunList = array();
    
    function __construct()
     
    {

        self::$server = new HttpServer(asyncFun(function (ServerRequestInterface $request) {
            try {
                $finalResult = awaitFun($this->doOpraction($request));
                
                if($finalResult[0] != 'Route not found'){
                    return new Response(
                        $finalResult[1],
                        $finalResult[2],
                        $finalResult[3]
                    );
                }
                
                return new Response($finalResult[1], $finalResult[2], str_replace('{{This}}', $request->getUri()->getPath(), $finalResult[3]));

            } catch (\Throwable $th) {
                return new Response(400, ['Content-Type' => 'application/json'], $th);
            }
        }));
    }

    //whenever any request come from browser this function will call
    private function doOpraction($request)
    {
        $reqType = $request->getMethod();
        $reqPath = $request->getUri()->getPath();

        $resMethods = new Res();
        $reqMethods = new Req($request->getQueryParams(), (string)$request->getBody());

        if (isset(self::$callBackFunList[$reqPath . "?" . $reqType])) {
            $callBackFun = self::$callBackFunList[$reqPath . "?" . $reqType];

            return new Promise(function ($resolve, $reject) use ($callBackFun, $reqMethods, $resMethods) {
                $resolve($callBackFun($reqMethods, $resMethods));
            });

        }

        return new Promise(function ($resolve, $reject) {
            $resolve(['Route not found', 404, 
            array(
                'Content-Type' => 'text/plain'
            ),
             "Cannot GET '{{This}}'"]);
        });
    }


    public static function get($route, $callBackFun)
    { 
        self::$callBackFunList[$route . '?GET'] = $callBackFun;
    }

    public static function post($route, $callBackFun)
    {
        self::$callBackFunList[$route . '?POST'] = $callBackFun;
    }

    public static function error()
    {
    }

    public static function listen(int $PORT, $callBack)
    {
        $socket = new SocketServer("127.0.0.1:$PORT");
        self::$server->listen($socket);

        $callBack();
    }
}

class HelpReq
{
    public static $all_params = array();
    public static $all_bodies = array();
}

class Req
{

    function __construct($params, $bodies)
    {
        HelpReq::$all_params = $params;
        HelpReq::$all_bodies = json_decode($bodies, true);
    }

    public static function params($key)
    {
        if (isset(HelpReq::$all_params[$key])) {
            return HelpReq::$all_params[$key];
        }

        return null;
    }

    public static function body($key)
    {
        if (isset(HelpReq::$all_bodies[$key])) {
            return HelpReq::$all_bodies[$key];
        }

        return null;
    }
}


class Res
{
    public static $statusCode = 200;

    public static function send(string $message)
    {
        return ['plaintext', self::$statusCode,  array('Content-Type' => 'text/plain; charset=utf-8') , $message];
    }

    public static function sendJson(array $json)
    {
        return ['json', self::$statusCode, array('Content-Type' => 'application/json'), $json];
    }

    public static function render(string $html)
    {
        return ['html', self::$statusCode,  array('Content-Type' => 'text/html; charset=utf-8'),  $html];
    }

    public static function chunks($data)
    {
        return ['chunks', self::$statusCode,  array('Content-Type' => 'text/html; charset=utf-8'),  $data];
    }
}
