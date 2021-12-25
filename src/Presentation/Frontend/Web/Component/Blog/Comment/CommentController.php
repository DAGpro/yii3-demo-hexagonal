<?php

declare(strict_types=1);

namespace App\Presentation\Frontend\Web\Component\Blog\Comment;

use App\Core\Component\Blog\Application\CommentService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Yii\View\ViewRenderer;

final class CommentController
{
    private ViewRenderer $viewRenderer;

    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('component/blog/comments');
    }

    public function index(Request $request, CommentService $service, CurrentRoute $currentRoute): Response
    {
        $paginator = $service->getFeedPaginator();
        if ($currentRoute->getArgument('next') !== null) {
            $paginator = $paginator->withNextPageToken((string)$currentRoute->getArgument('next'));
        }

        if ($this->isAjaxRequest($request)) {
            return $this->viewRenderer->renderPartial('_comments', ['data' => $paginator]);
        }

        return $this->viewRenderer->render('index', ['data' => $paginator]);
    }

    private function isAjaxRequest(Request $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }
}
