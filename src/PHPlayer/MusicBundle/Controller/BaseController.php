<?php

namespace PHPlayer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
	public function json($object) {
		$response = new Response(json_encode($object));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}
