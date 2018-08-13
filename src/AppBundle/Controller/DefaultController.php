<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ficha;
use AppBundle\Entity\Jugador;
use AppBundle\Entity\Partida;
use AppBundle\Entity\Tablero;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $partidaRepo = $em->getRepository('AppBundle:Partida');

        //Comprobamos si hay una posible partida en curso
        $partida = $partidaRepo->findOneBy([
            'enCurso' => true,
            'fin' => null
        ]);

        if ($partida) {
            /* @var Tablero $tablero */
            $tablero = $partida->getTablero();
            /* @var Ficha :array $fichas */
            $fichas = $tablero->getFichas();

            //Redirigimos a la inicialización de la partida
            return $this->render('default/partida.html.twig', [
                'partida' => $partida,
                'tablero' => $tablero,
                'fichas' => $fichas
            ]);
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'partida' => $partida
        ]);
    }

    /**
     * @Route("/nueva-partida", name="nueva_partida")
     */
    public function nuevaPartidaAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Creamos una Partida
        $partida = new Partida();
        $partida->setInicio(new \DateTime("now"));
        $partida->setEnCurso(true);
        $partida->setFinalizada(false);
        $partida->setEmpate(false);

        //Creamos un Tablero
        $tablero = new Tablero();
        $tablero->setNumColumnas(3);
        $tablero->setNumFilas(3);

        //Creamos dos Jugadores
        $jugador1 = new Jugador();
        $jugador2 = new Jugador();
        $jugador1->setApodo("Jugador 1")
            ->setNombre("Jugador 1")
            ->setApellido("Jugador 1");
        $jugador2->setApodo("Jugador 2")
            ->setNombre("Jugador 2")
            ->setApellido("Jugador 2");

        //Asociamos Partida, Tablero y Jugadores
        $partida->setTablero($tablero);
        $tablero->setPartida($partida);
        $partida->setJugador1($jugador1);
        $partida->setJugador2($jugador2);

        $em->persist($tablero);
        $em->persist($partida);
        $em->persist($jugador1);
        $em->persist($jugador2);

        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}
