<?php

namespace App\Controller\Api;


use App\Entity\Category;
use App\Entity\PriceProduct;
use App\Repository\PriceProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\UrlHelper;




/**
 * Class ApiController
 * @package App\Controller
 *
 * @Route("/api")
 */

class ApiProductController extends ApiBaseController
{
    private $em;
    private $urlHelper;

    /**
     * ApiController constructor.
     *
     * @param $logger
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository, EntityManagerInterface $em, UrlHelper $urlHelper)
    {

        parent::__construct($logger, $userRepository,  $em);
        $this->em = $em;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @Route("/product", name="api-product",methods={"GET"})
     * @return Response
     */

    public function product()
    {
        try {
            $code = Response::HTTP_OK;
            $error = false;

            $product = $this->em->getRepository(PriceProduct::class)->findBy([
                'state' => 1
            ]);

            $route = str_replace('api', '', $this->urlHelper->getAbsoluteUrl('uploads/images/products/'));

            $data = array();
            foreach ($product as $prod) {
                array_push($data, (object)array(
                    'id' => $prod->getProduct()->getId(),
                    'typeName' => $prod->getProduct()->getCategory()->getName(),
                    'type' => $prod->getProduct()->getCategory()->getId(),
                    'name' => $prod->getProduct()->getName(),
                    'description' => $prod->getProduct()->getDescription(),
                    'price' => $prod->getPrice()/100,
                    'image' => $prod->getProduct()->getImage() ? $route . $prod->getProduct()->getImage() :''
                ));
            }

            $response = [
                'status' => $code,
                'error' => $error,
                'data' =>  [
                    'product' => $data,
                ]
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => true,
                'data' => 'Ha ocurrido un error al obtener los productos: {' . $e->getMessage() . '}'
            ];
        }

        return $this->sendResponse($response);
    }

    /**
     * @Route("/categories", name="api-categories",methods={"GET"})
     * @return Response
     */

    public function categories()
    {
        try {
            $code = Response::HTTP_OK;
            $error = false;

            $categories = $this->em->getRepository(Category::class)->findBy([
                'state' => 1
            ]);

            $route = str_replace('api', '', $this->urlHelper->getAbsoluteUrl('uploads/images/category/'));

            $data = array();
            foreach ($categories as $cat) {
                array_push($data, (object)array(
                    'id' => $cat->getId(),
                    'name' => $cat->getName(),
                    'image' =>  $cat->getImage() ? $route . $cat->getImage() : '',

                ));
            }

            $response = [
                'status' => $code,
                'error' => $error,
                'data' =>  [
                    'categories' => $data,
                ]
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => true,
                'data' => 'Ha ocurrido un error al obtener las categorias: {' . $e->getMessage() . '}'
            ];
        }

        return $this->sendResponse($response);
    }
}
