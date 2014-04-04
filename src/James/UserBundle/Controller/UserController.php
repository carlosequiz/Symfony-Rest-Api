<?php

namespace James\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use James\UserBundle\Entity\User;

class UserController extends Controller
{
	/**
    * indexAction
    * @Route("/", name="user_index")
    * 
    * @return template
    * @Template()
    */
    public function indexAction()
    {      
      return $this->render('UserBundle:User:index.html.twig');
    }

    /**
     * @Route("/users", name="create_user", options={"expose":true})
     * @Method("PUT")
     */
    public function createAction(Request $request)
    {	
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$content = $request->getContent();

    	$user_data = null;

    	if(!empty($content)){
    		$user_data = json_decode($content, true);
    	}

      	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository('UserBundle:User')->findOneByEmail($user_data['email']);

    	if($user){
    		return new JsonResponse(
				array('message' => 'User already exists!'),
				Response::HTTP_NOT_FOUND
			);
    	}

		$user = new User();
    	$user->setFirstName($user_data['firstName']);
    	$user->setLastName($user_data['lastName']);
    	$user->setEmail($user_data['email']);

    	$em->persist($user);
    	$em->flush();

        return new JsonResponse(
		     array('newUser' => User::toSerialize($user)),
		     Response::HTTP_OK
		);
    }

    /**
     * @Route("/user/{id}", name="get_user", options={"expose":true})
     * @Method("GET")
     */
    public function getUserAction(Request $request, $id)
    {
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$user = $em->getRepository('UserBundle:User')->findOneById($id);
    	return new JsonResponse(
			array('user' => $user->toJson()),
			Response::HTTP_OK
		);
    }

    /**
     * @Route("/user/{id}", name="update_user", options={"expose":true})
     * @Method("PUT")
     */ 
    public function updateUserAction(Request $request, $id)
    {	
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$content = $request->getContent();

    	$user_data = null;

    	if(!empty($content)){
    		$user_data = json_decode($content, true);
    	}

    	$em = $this->getDoctrine()->getManager();

    	$user = $em->getRepository('UserBundle:User')->findOneById($id);

    	if(!$user){
    		return new JsonResponse(
				Response::HTTP_NOT_FOUND
			);
    	}

    	$user->setFirstName($user_data['firstName']);
    	$user->setLastName($user_data['lastName']);
    	$user->setEmail($user_data['email']);

    	$em->persist($user);
    	$em->flush();

    	return new JsonResponse(
			Response::HTTP_OK
		);
    }

    /**
     * @Route("/user/{id}/delete", name="delete_user", options={"expose":true})
     * @Method("DELETE")
     */
    public function deleteUserAction(Request $request, $id)
    {	
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository('UserBundle:User')->findOneById($id);

    	if(!$user){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
	    	return $response;
    	}

		$em->remove($user);
		$em->flush();

		$response = new Response();
		$response->setStatusCode(Response::HTTP_NO_CONTENT);
    	return $response;
    }

    /**
     * @Route("/users", name="get_all_users", options={"expose":true})
     * @Method("GET")
     */
    public function getAllUsersAction(Request $request)
    {	
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$em = $this->getDoctrine();

    	$users = $em->getRepository('UserBundle:User')->searchUsers();

    	return new JsonResponse(
    		array('Users' => User::toArraySerialize($users)), 
    		Response::HTTP_OK
    	);
    }

    /**
     * @Route("/users/search", name="search_users", options={"expose":true})
     * @Method("POST")
     */
    public function searchAction(Request $request)
    {	
    	$api_privilege = $this->get('utility.api_privilege');

    	if(!$api_privilege->verifyKey($request->headers->get('api-key'))){
    		$response = new Response();
			$response->setStatusCode(Response::HTTP_UNAUTHORIZED);
	    	return $response;
    	}

    	$content = $request->getContent();

    	$searchTerms = null;

    	if(!empty($content)){
    		$searchTerms = json_decode($content, true);
    	}

    	$em = $this->getDoctrine();

    	$users = $em->getRepository('UserBundle:User')->searchUsers($searchTerms);
    	
    	//Add Pagination if needed

    	return new JsonResponse(
    		array('Users' => User::toArraySerialize($users)), 
    		Response::HTTP_OK
    	);
    }
}
