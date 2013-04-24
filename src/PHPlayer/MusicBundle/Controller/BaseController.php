<?php

namespace PHPlayer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
	public function json($object, $statusCode = 200) {
		$response = new Response(json_encode($object));
		$response->headers->set('Content-Type', 'application/json');
		$response->setStatusCode($statusCode);
		return $response;
	}
}
