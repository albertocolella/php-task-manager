<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Task;

/**
 * @Route("/api/v1/tasks", name="task")
 */
class TaskController extends Controller
{
    /**
     * @Route("", name="task_insert")
     * @Method({"POST"})
     */
    public function insertAction(Request $request)
    {
      $task = new Task();
      $task->setName('A Foo Bar');
      $task->setDescription('Lorem ipsum dolor');

      $em = $this->getDoctrine()->getManager();
      $em->persist($task);
      $em->flush();

      $response = new Response();
      $response->setContent(json_encode(array(
        "message"=>'Created product id '.$task->getId(),
      )));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    /**
     * @Route("/{id}", name="task_show")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        $task = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $response = new Response();
        $response->setContent(json_encode(array(
          "entity"=>$task,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("", name="task_showall")
     * @Method({"GET"})
     */
    public function showAllAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();
        /*if(count($tasks)>0){
          $class = new \ReflectionClass(get_class($tasks[0]));
          $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        //  var_dump($methods);
        }
        var_dump(json_encode($tasks[0]));*/
        $response = new Response();
        $response->setContent(json_encode($tasks));
        // $response->setContent($tasks);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{id}", name="task_update")
     * @Method({"PUT"})
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $task->setName('New task name!');
        $em->flush();

        return $this->redirectToRoute('task');
    }
}
