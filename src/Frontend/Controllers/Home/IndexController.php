<?php
namespace StarreDEV\Frontend\Controllers\Home;

use Sunrise\Http\Router\Annotation as Mapping;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Annotation\Route;
use Psr\Http\Message\ServerRequestInterface as Request;
use StarreDEV\Framework\Service\TwigService;

/**
 * Class IndexController
 *
 * @package StarreDEV\Frontend\Controllers
 */
class IndexController {

    /**
     * ApiController constructor.
     * @param TwigService $twig
     * @param ResponseFactory $response
     */
    public function __construct(
        private TwigService $twig,
        private ResponseFactory $response
    ) {}

    /**
     *
     * @param Request $request
     * @return Response
     */

    #[Route(
        name: 'index',
        path: '/',
        methods: ['GET'],
    )]
    
    public function index(Request $request) 
    {
        return $this->twig->render(
            $this->response->createResponse(200),
            'home/home.twig'
        );
    }
}
?>
