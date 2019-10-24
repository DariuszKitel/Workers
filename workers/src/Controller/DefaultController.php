<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 01.06.2019
 * Time: 21:29
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index()
    {
        return $this->render('base.html.twig');
    }
}