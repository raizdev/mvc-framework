<?php
namespace StarreDEV\Controllers;

use StarreDEV\Framework\Controller\BaseController;
use Sunrise\Http\Router\Annotation as Mapping;
use Psr\Http\Message\ServerRequestInterface as Request;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Annotation\Prefix;
use Sunrise\Http\Router\Annotation\Route;
use PHLAK\Config\Interfaces\ConfigInterface;

/**
 * Class ApiController
 *
 * @package StarreDEV\Frontend\Controllers
 */

#[Prefix('/api')]
class ApiController extends BaseController {
  
    /**
     * ApiController constructor.
     * @param ConfigInterface $config
     */
    public function __construct(
        private ConfigInterface $config
    ) {}

    /**
     *
     * @param Request $request
     * @return Response
     */

    #[Route(
        name: 'config',
        path: "(/{param})",
        methods: ['GET'],
    )]

    public function config(Request $request) {
        return $this->respond(
            (new ResponseFactory)->createResponse(200),
            response()->setData($this->config->get($request->getAttribute('param')))
        );
    }
}
?>