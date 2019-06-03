<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 01.06.2019
 * Time: 21:37
 */

namespace App\Controller;


use App\Entity\WorkPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 *@Route("/work")
 */
class WorkController extends AbstractController
{

    /**
     * @Route("/{page}", name="work_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(WorkPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (WorkPost $item) {
                    return $this->generateUrl('work_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="work_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function post(WorkPost $workPost)
    {
        return $this->json($workPost
            //$this->getDoctrine()->getRepository(WorkPost::class)->find($id) zamiast tego działa param converter.
        );
    }

    /**
     * @Route("/post/{slug}", name="work_slug", methods={"GET"})
     */
    public function postBySlug(WorkPost $workPost)
    {
        return $this->json($workPost
            //$this->getDoctrine()->getRepository(WorkPost::class)->findOneBy(['slug' => $slug])
        );
    }

    /**
     * @Route("/add", name="work_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $workPost = $serializer->deserialize($request->getContent(), WorkPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($workPost);
        $em->flush();

        return $this->json($workPost);
    }

    /**
     * @Route("/post/{id}", name="work_delete", methods={"DELETE"})
     */
    public function delete(WorkPost $workPost)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPost);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}