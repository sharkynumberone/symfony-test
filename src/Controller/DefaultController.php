<?php

namespace App\Controller;

use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    const TABLE_HEADERS = ['id', 'url', 'request', 'response', 'response_code', 'ip', 'date'];
    
    public function index()
    {
        return $this->json(['result' => random_int(1, 1000000)]);
    }

    public function admin(Request $request){
        if (!empty($request->get('ip'))){
            $logs = $this->getDoctrine()
                ->getRepository(Log::class)
                ->findBy(['ip' => $request->get('ip')]);
        } else {
            $logs = $this->getDoctrine()
                ->getRepository(Log::class)
                ->findAll();
        }

        return $this->render('list.html.twig', [
            'logs' => $logs,
            'headers' => self::TABLE_HEADERS,
            'ip' => $request->get('ip') ?? ''
        ]);
    }
}
