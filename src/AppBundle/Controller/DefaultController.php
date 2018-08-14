<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ficha;
use AppBundle\Entity\Jugador;
use AppBundle\Entity\Partida;
use AppBundle\Entity\Tablero;
use AppBundle\Entity\TipoFicha;
use AppBundle\Entity\Turno;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

            $matrizFichas = $em->getRepository('AppBundle:Tablero')->getMatrizFichasPuestas($tablero);

            //Redirigimos a la inicialización de la partida
            return $this->render('default/partida.html.twig', [
                'partida' => $partida,
                'tablero' => $tablero,
                'fichas' => $matrizFichas
            ]);
        }

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
        $tipoFichaRepository = $em->getRepository('AppBundle:TipoFicha');

        //Inicializamos los tipos de Ficha básicos
        $tipoFichaX = $tipoFichaRepository->findOneBy(['simbolo' => 'X']);
        $tipoFichaO = $tipoFichaRepository->findOneBy(['simbolo' => 'O']);

        if (!$tipoFichaX) {
            $tipoFichaX = new TipoFicha();
            $tipoFichaX->setSimbolo("X");

            $em->persist($tipoFichaX);
        }

        if (!$tipoFichaO) {
            $tipoFichaO = new TipoFicha();
            $tipoFichaO->setSimbolo("O");

            $em->persist($tipoFichaO);
        }

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

    /**
     * @Route("/partida/{id}/poner-ficha/{fila}-{columna}", name="poner_ficha")
     *
     * @ParamConverter("partida", options={"mapping": {"id" : "id"}})
     *
     */
    public function ponerFichaAction(Partida $partida, $fila, $columna)
    {
        $em = $this->getDoctrine()->getManager();
        $tableroRepository = $em->getRepository('AppBundle:Tablero');
        $tipoFichaRepository = $em->getRepository('AppBundle:TipoFicha');

        $tablero = $partida->getTablero();
        $dimensionTablero = $tablero->getDimension();

        $numFichasPuestas = $tableroRepository->getNumeroFichasPuestas($tablero);

        //Comprobar si se puede poner ficha
        if ($numFichasPuestas >= 0 && $numFichasPuestas <= ($dimensionTablero - 1)) {
            //Tipos de Ficha
            $tipoFichaX = $tipoFichaRepository->findOneBy(['simbolo' => 'X']);
            $tipoFichaO = $tipoFichaRepository->findOneBy(['simbolo' => 'O']);

            //Jugamos turno
            $turno = new Turno();
            $em->persist($turno);

            //Ficha
            $ficha = new Ficha();
            $em->persist($ficha);

            //Todavía no se ha puesto ninguna ficha (primer turno) o turno del jugador 1
            if ($numFichasPuestas === 0 || ($numFichasPuestas % 2) === 0) {
                $jugador = $partida->getJugador1();
                $ficha->setTipo($tipoFichaX);
            }
            else { //Turno del jugador 2
                $jugador = $partida->getJugador2();
                $ficha->setTipo($tipoFichaO);
            }

            $partida->addTurno($turno);
            $turno->setPartida($partida);

            $tablero->addFicha($ficha);

            $ficha->setJugador($jugador);
            $ficha->setPosFila($fila);
            $ficha->setPosColumna($columna);
            $ficha->setTablero($tablero);

            $turno->setFicha($ficha);
            $turno->setJugadoPor($jugador);

            $em->flush();
        }
        else { //Tablero completo: ya se han puesto las 9 fichas
            /* @todo Lógica de comprobación de ganador */
        }

        return $this->redirectToRoute('homepage');
    }
}
