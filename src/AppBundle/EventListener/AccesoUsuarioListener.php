<?php

  namespace AppBundle\EventListener;

  use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  use AppBundle\Controller\IAccesoUsuarioController;
  use Symfony\Bundle\FrameworkBundle\Routing\Router;
  /**
   *
   */

  class AccesoUsuarioListener
  {

    private $pass;
    private $router;

    public function __construct($contador_pass, Router $router)
    {
      $this->pass = $contador_pass;
      $this->router = $router;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
      $controller = $event->getController();
      $request = $event->getRequest();
      $session = $request->getSession();
      $cookies = $request->cookies;

      if($controller[0] instanceof IAccesoUsuarioController)
      {
        if(!$cookies->has('contador_cookie') )
        {
          $url = $this->router->generate('login');
          $response = new RedirectResponse($url);

         $event->setController(function() use ($url) {
           return new RedirectResponse($url);
          });
        }

      }
    }
  }

 ?>
