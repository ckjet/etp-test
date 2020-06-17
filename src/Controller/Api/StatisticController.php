<?php

namespace App\Controller\Api;

use App\Entity\Statistic;
use App\Repository\StatisticRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    /**
     * @Route("/api/statistic", name="api_statistic")
     */
    public function index()
    {
        return $this->render('api/statistic/index.html.twig', [
            'controller_name' => 'StatisticController',
        ]);
    }

    /**
     * @Route("/api/statistic/list", name="api_statistic_list")
     */
    public function list(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var StatisticRepository $statisticRepo */
        $statisticRepo = $entityManager->getRepository(Statistic::class);
        $limit = (int)$request->query->get('limit');
        $offset = (int)$request->query->get('start');
        $ipQuery = $request->query->get('ipQuery', false);
        $data = [
            'success' => true,
            'metaData' => [
                'root' => 'data',
                'idProperty' => 'id',
                'totalProperty' => 'total',
                'fields' => [
                    ['name' => 'id', 'type' => 'int'],
                    ['name' => 'ip', 'type' => 'string'],
                    ['name' => 'browser', 'type' => 'string'],
                    ['name' => 'os', 'type' => 'string'],
                    ['name' => 'url_from', 'type' => 'string'],
                    ['name' => 'url_to', 'type' => 'string'],
                    ['name' => 'url_total', 'type' => 'string']
                ]
            ],
            'data' => [],
            'total' => 0
        ];
        $result = $statisticRepo->getItems($limit, $offset, $ipQuery);
        $data['data'] = $result['data'];
        $data['total'] = $result['total'];
        return new JsonResponse($data);
    }
}
