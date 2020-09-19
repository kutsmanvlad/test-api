<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Test;
use App\Entity\Question;
use App\Entity\Answer;

class TestController extends AbstractController
{
	
	private function IsToken($headers)
	{
		foreach($headers as $key=>$value){
			if($key == "token" && $value[0] == $_ENV['APP_SECRET_TOKEN']){
				return true;
			}
		}
		return false;
		
	}	
	    	
	/**
     * @Route("/createTest", name="createTest", methods={"POST"})
     */
	public function createTest(Request $request)
	{   if (!$this->IsToken($request->headers->all())){
			return $this->json([
					'error' => 'Wrong token.',
					'body' => []
				]);
		}
			
		$constraint = new Assert\Collection([
			'name' => new Assert\Length(['min' => 2]),
			'description' => new Assert\Length(['min' => 2])
		]);
		
		$validator = Validation::createValidator();
		$error = $validator->validate(['name' => $request->request->get('name'), 'description' => $request->request->get('description')], $constraint);
		
		if(0 !== count($error)){
			return $this->json([
				'error' => (string)$error,
				'body' => []
			]);
		}
		
		$test = new Test();
		$test->setNameTest($request->request->get('name'));
		$test->setDescription($request->request->get('description'));
		$test->setDateCreate(new \DateTime("NOW"));
		
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($test);
		$entityManager->flush();
		
		$entityManager->getRepository(Test::class)->createQueryBuilder('')
		->update(Test::class, 't')
		->set('t.Identifier', ':identifier')
		->setParameter('identifier', sha1($test->getId()))
		->where('t.id = :id')
		->setParameter('id', $test->getId())
		->getQuery()
		->execute();
		
		return $this->json([
            'error' => '',
			'body' => ['message' => 'Test created', 'id' => $test->getId()]
        ]);

	}
	
	/**
     * @Route("/createQuestion", name="createQuestion", methods={"POST"})
     */
	public function createQuestion(Request $request)
	{   
		if (!$this->IsToken($request->headers->all())){
			return $this->json([
					'error' => 'Wrong token.',
					'body' => []
				]);
		}
			
		$constraint = new Assert\Collection([
			'id_test' => new Assert\Type(['type' => 'digit']),
			'description' => new Assert\Length(['min' => 2])
		]);
		
		$validator = Validation::createValidator();
		$error = $validator->validate(['id_test' => $request->request->get('id_test'), 'description' => $request->request->get('description')], $constraint);
		
		if(0 !== count($error)){
			return $this->json([
				'error' => (string)$error,
				'body' => []
			]);
		}
		
		$question = new Question();
		$question->setIdTest($request->request->get('id_test'));
		$question->setDescription($request->request->get('description'));
		
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($question);
		$entityManager->flush();
		
		return $this->json([
            'error' => '',
			'body' => ['message' => 'Question created', 'id' => $question->getId()]
        ]);

	}
	
	/**
     * @Route("/infoTest", name="infoTest", methods={"GET"})
     */
	public function infoTest(Request $request)
	{   
		if (!$this->IsToken($request->headers->all())){
			return $this->json([
					'error' => 'Wrong token.',
					'body' => []
				]);
		}
		
		$em = $this->getDoctrine()->getManager();
		$statement = $em->getConnection()->prepare('SELECT t.id, t.name_test,(case when a.total is null then 0 else a.total end) as total
						FROM test t left join (select id_test, count(*) as total from `answer` GROUP BY id_test) a ON a.id_test = t.id');
        $statement->execute();
		$result = $statement->fetchAll();
				
		return $this->json([
            'error' => '',
			'body' => $result
        ]);

	}
	
	/**
     * @Route("/infoTestDiagram", name="infoTestDiagram", methods={"GET"})
     */
	public function infoTestDiagram(Request $request)
	{   
		if (!$this->IsToken($request->headers->all())){
			return $this->json([
					'error' => 'Wrong token.',
					'body' => []
				]);
		}
		
		$em = $this->getDoctrine()->getManager();
        
        $totalTest = $em->getRepository(Test::class)->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
			
		$statement = $em->getConnection()->prepare('select count(*) as total from test WHERE id IN (SELECT id_test from `answer`)');
        $statement->execute();
		$result = $statement->fetchColumn();	
		
		return $this->json([
            'error' => '',
			'body' => ['all_test' => $totalTest, 'only_answer' => $result]
        ]);

	}
	
	/**
     * @Route("/getTest/{id}", methods={"GET"})
     */
    public function getTest(string $id)
    {
		
		$manager = $this->getDoctrine()->getManager();
		
		$test = $manager->getRepository(Test::class)->createQueryBuilder('t')
		->select('t')
		->where('t.Identifier = :id')
		->setParameter('id', $id)
		->getQuery()
		->execute();
		
		if (count($test)==0){
			return $this->json([
				'error' => 'Test is avialible',
				'body' => []
			]);
		}
		$test = $test[0];
		
		$result = ['IdTest' => $test->getId(), 'DescriptionTest' => $test->getDescription(), 'NameTest' => $test->getNameTest(), 'questions' => array() ];
		
		$questions = $manager->getRepository(Question::class)->createQueryBuilder('q')
		->select('q')
		->where('q.IdTest = :id')
		->setParameter('id', $test->getId())
		->getQuery()
		->execute();
		
		foreach($questions as $question){
			$result['questions'][] = ['id' => $question->getId(), 'description' => $question->getDescription()];
		}
		
		return $this->json([
				'error' => '',
				'body' => $result
			]);
	}

}
