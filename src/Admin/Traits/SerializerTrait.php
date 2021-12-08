<?php

namespace App\Admin\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

Trait SerializerTrait
{
    protected function sendResponse ($response, $context = array())
    {
        if (empty($context))
        {
            $normalizer = new ObjectNormalizer();
        }
        else
        {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
            $normalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter);
        }
        $serializer = new Serializer([new DateTimeNormalizer(['datetime_format' => 'd-m-Y H:i:s e']), $normalizer], [new JsonEncoder()]);
        return new Response($serializer->serialize($response, "json", $context), $response['status'], ['content-type' => 'application/json', 'Access-Control-Allow-Origin' => '*']);
    }
}
