<?php

namespace App\EventSubscriber;

use App\Repository\ConferenceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $conferenceRepository;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepostory)
    {
        $this->twig = $twig;
        $this->conferenceRepository = $conferenceRepostory;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $conferences = $this->conferenceRepository->findAll();
        $this->twig->addGlobal('conferences', $conferences);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
