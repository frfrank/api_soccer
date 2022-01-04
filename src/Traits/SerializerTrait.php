<?php

namespace App\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait SerializerTrait
{
    protected function sendResponse ($response, $context = [])
    {
        return new Response($this->serialize($response, $context), $response['status'], ['content-type' => 'application/json']);
    }


    public function serialize ($response, $context = [])
    {
        if (empty($context))
        {
            $normalizer = new ObjectNormalizer();
        }
        else
        {
            $classMetadataFactory       = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
            $normalizer                 = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter);
        }

        $serializer = new Serializer([new DateTimeNormalizer(['datetime_format' => 'Y-m-d H:i:s']), $normalizer], [new JsonEncoder()]);

        return $serializer->serialize($response, 'json', $context);
    }


    public function normalize($object, $context = [])
    {
        if (empty($context))
        {
            $normalizer = new ObjectNormalizer();
        }
        else
        {
            $classMetadataFactory       = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
            $normalizer                 = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter);
        }

        $serializer = new Serializer([new DateTimeNormalizer(['datetime_format' => 'Y-m-d H:i:s']), $normalizer], [new JsonEncoder()]);

        return $serializer->normalize($object, null, $context);
    }
}
