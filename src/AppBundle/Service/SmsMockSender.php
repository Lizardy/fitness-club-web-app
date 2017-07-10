<?php

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SmsMockSender
{
    public function __construct(){}

    public function send(Request $request)
    {
        $phone = $request->query->get('phone');
        $message = $request->query->get('message');
        $success = mt_rand(0, 100) % 2;
        return $success ? new Response('success', 200) : new Response('not available', 503);
    }
}
