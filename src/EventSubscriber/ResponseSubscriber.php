<?php

namespace App\EventSubscriber;

use App\Entity\Log;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseSubscriber
 * @package App\EventSubscriber
 */
class ResponseSubscriber implements EventSubscriberInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $request_log = $request->get('request-log');

        if ($request_log) {
            /** @var ManagerRegistry $doctrine */
            $doctrine = $this->container->get('doctrine');
            $entityManager = $doctrine->getManager();

            $log = new Log();
            $log->setUrl($request->getRequestUri());
            $request_data = [
                'body'    => $request->getContent(),
                'headers' => $request->headers->all()
            ];
            $response_data = [
                'body'    => $response->getContent(),
                'headers' => $response->headers->all()
            ];
            $log->setRequest(json_encode($request_data));
            $log->setResponse(json_encode($response_data));
            $log->setResponseCode($response->getStatusCode());
            $log->setIp($request->getClientIp());
            $log->setDate(new DateTime());
            $entityManager->persist($log);
            $entityManager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
