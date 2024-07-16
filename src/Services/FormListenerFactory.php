<?php

namespace App\Services;

use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory
{
    public function __construct(private readonly SluggerInterface $slugger)
    {        
    }

    public function autoSlug ($field): callable
    {
        return function (PreSubmitEvent $event) use($field)
        {
            $data = $event->getData();
            if(empty($data['slug'])) {
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function timestamp (): callable
    {
        return function (PostSubmitEvent $event)
        {
            $timezone = new \DateTimeZone('Europe/Paris');
            $data = $event->getData();
            $data->setUpdatedAt(new \DateTimeImmutable('now', $timezone));
            if (!$data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable('now', $timezone));
            }
        };
    }
}