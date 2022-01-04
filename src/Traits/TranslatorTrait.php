<?php

namespace App\Traits;


use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorTrait
{
    public function trans($id, $options = [])
    {
        if(!property_exists($this, 'translator') or !is_a($this->translator, TranslatorInterface::class))
        {
            return $id;
        }

        $requestStack = $this->get('request_stack');

        $defaultOptions = [
            'parameters' => [],
            'domain' => 'messages',
            'locale' => $requestStack->getCurrentRequest()->getLocale(),
        ];
        $options = array_merge($defaultOptions, $options);

        return $this->translator->trans($id, $options['parameters'], $options['domain'], $options['locale']);
    }
}
