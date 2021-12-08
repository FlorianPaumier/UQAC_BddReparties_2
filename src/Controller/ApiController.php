<?php


namespace App\Controller;


use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\ControllerTrait;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractFOSRestController
{
    use ControllerTrait;

    protected function submit(FormInterface &$form, Request $request)
    {
        return $form->submit($request->request->all(), false);
    }

    protected function paginate($data, array $criteria = []): JsonResponse
    {
        $serializer = SerializerBuilder::create();
        $data = $serializer->build()->serialize($data, 'json');

        return new JsonResponse($data, 200);
    }

    protected function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
