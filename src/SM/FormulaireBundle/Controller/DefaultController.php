<?php

namespace SM\FormulaireBundle\Controller;

use SM\FormulaireBundle\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    public function listAction( )
    {
        $repository = $this->getDoctrine()->getRepository(Membre::class);

        $memebers = $repository->findAll();
        return $this->render('SMFormulaireBundle:Default:list.html.twig', array(
            'members' => $memebers));
        
    }
    public function indexAction(Request $request )
    {
        $membre = new Membre();

        //form information
        $formBuilder =  $this->get('form.factory')->createBuilder(FormType::class, $membre);
        $formBuilder->add('nom', 'text')
                    ->add('prenom', 'text')
                    ->add('genre', 'choice', array(
                        'choices' => array('m' => 'Homme', 'f' => 'Femme'),
                        'expanded' => true,
                        'multiple' => false
                    ))
                    ->add('nationalite', ChoiceType::class, array(
                        'choices'  => array(
                            'MA' => 'Maroc',
                            'FR' => 'France',
                            'IT' => 'Italy',
                        )))
                    ->add('submit', SubmitType::class);

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {


            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($membre);
                $em->flush();
            }
        }
        //view
        return $this->render('SMFormulaireBundle:Default:index.html.twig', array(
            'form' => $form->createView()));


    }

}
