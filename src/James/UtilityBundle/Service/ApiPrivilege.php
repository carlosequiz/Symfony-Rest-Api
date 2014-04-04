<?php

namespace James\UtilityBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiPrivilege{
    protected $container;
    protected $em;
    protected $security_context;

    public function __construct(ContainerInterface $container){
     $this->container = $container;
    }

    public function verifyKey($key)
    {
      if($key == $this->container->getParameter('api_key')){
        return true;
      }

      return false;
    }
}