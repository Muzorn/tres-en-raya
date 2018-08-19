<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ficha;
use AppBundle\Entity\Jugador;
use AppBundle\Entity\Partida;
use AppBundle\Entity\Tablero;
use AppBundle\Entity\TipoFicha;
use AppBundle\Entity\Turno;
use AppBundle\Form\JugadoresPartidaType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $partidaRepo = $em->getRepository('AppBundle:Partida');

        //Tratamos de obtener la última Partida jugada (esté o no finalizada)
        $partida = $partidaRepo->findOneBy([], ['inicio' => 'DESC']);

        $tablero = $fichas = $matrizFichas = $sigJugadorTurno = null;

        //Formulario de inicialización de Partida: introducción de apodos de los jugadores
        $formJugadores = $this->createForm(JugadoresPartidaType::class, null);

        if ($partida) {
            /* @var Tablero $tablero */
            $tablero = $partida->getTablero();
            /* @var Ficha :array $fichas */
            $fichas = $tablero->getFichas();

            //Obtenemos el siguiente Jugador en poner Ficha
            $sigJugadorTurno = $partidaRepo->getSiguienteJugadorTurno($partida);

            $matrizFichas = $em->getRepository('AppBundle:Tablero')->getMatrizFichasPuestas($tablero);
        }

        //Cargamos la Partida
        return $this->render('default/partida.html.twig', [
            'partida' => $partida,
            'tablero' => $tablero,
            'fichas' => $matrizFichas,
            'sigJugadorTurno' => $sigJugadorTurno,
            'formJugadores' => $formJugadores->createView()
        ]);
    }

    /**
     * @Route("/nueva-partida", name="nueva_partida")
     */
    public function nuevaPartidaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tipoFichaRepository = $em->getRepository('AppBundle:TipoFicha');
        $jugadorRepository = $em->getRepository('AppBundle:Jugador');

        //Formulario de inicialización de Partida: introducción de apodos de los jugadores
        $formJugadores = $this->createForm(JugadoresPartidaType::class, null);

        $formJugadores->handleRequest($request);

        if ($formJugadores->isSubmitted() && $formJugadores->isValid()) {
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

            //Jugadores y apodos
            $apodos = $formJugadores->getData();

            $apodoJugador1 = $apodos['jugador_1_apodo'];
            $apodoJugador2 = $apodos['jugador_2_apodo'];

            if ($apodoJugador1 === $apodoJugador2) {
                $this->addFlash('error', '¡Error al incializar la partida: no puede haber dos jugadores con el mismo apodo!');
                return $this->redirectToRoute('homepage');
            }

            $jugador1 = $jugadorRepository->findOneBy(['apodo' => $apodoJugador1]);
            $jugador2 = $jugadorRepository->findOneBy(['apodo' => $apodoJugador2]);

            if (!$jugador1)
                $jugador1 = new Jugador();
            if (!$jugador2)
                $jugador2 = new Jugador();

            $jugador1->setApodo($apodoJugador1);
            $jugador2->setApodo($apodoJugador2);

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

            $this->addFlash('notice', '¡La partida ha comenzado!');

            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('error', '¡Error al incializar la partida!');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/partida/{partida_id}/poner-ficha/{fila}-{columna}", name="poner_ficha")
     *
     * @ParamConverter("partida", options={"id" : "partida_id"})
     *
     */
    public function ponerFichaAction(Partida $partida = null, $fila, $columna)
    {
        if (!$partida)
            return $this->redirectToRoute('homepage');

        $em = $this->getDoctrine()->getManager();
        $partidaRepo = $em->getRepository('AppBundle:Partida');
        $tableroRepository = $em->getRepository('AppBundle:Tablero');
        $tipoFichaRepository = $em->getRepository('AppBundle:TipoFicha');

        $tablero = $partida->getTablero();
        $dimensionTablero = $tablero->getDimension();

        $numFichasPuestas = $tableroRepository->getNumeroFichasPuestas($tablero);

        //Sólo permitimos poner Ficha si la Partida sigue en curso
        if ($partida->getEnCurso() || !$partida->getFinalizada()) {
            //Comprobamos cuántas Fichas hay puestas en el Tablero para saber si podemos poner una
            if ($numFichasPuestas >= 0 && $numFichasPuestas <= ($dimensionTablero - 1)) {
                //Tipos de Ficha
                $tipoFichaX = $tipoFichaRepository->findOneBy(['simbolo' => 'X']);
                $tipoFichaO = $tipoFichaRepository->findOneBy(['simbolo' => 'O']);

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

                $tablero->addFicha($ficha);

                $ficha->setJugador($jugador);
                $ficha->setPosFila($fila);
                $ficha->setPosColumna($columna);
                $ficha->setTablero($tablero);

                try {
                    //Jugamos turno
                    $turno = new Turno();
                    $em->persist($turno);

                    $partida->addTurno($turno);
                    $turno->setPartida($partida);

                    $turno->setFicha($ficha);
                    $turno->setJugadoPor($jugador);

                    $em->flush();
                } catch (UniqueConstraintViolationException $e) {
                    $this->addFlash('error', '¡Ya hay una ficha puesta en esa casilla!');

                    return $this->redirectToRoute('homepage');
                }
            }
        }

        //Cada vez que se pone una Ficha, comprobamos si se ha hecho tres en raya
        $matrizFichas = $tableroRepository->getMatrizFichasPuestas($tablero);
        $ganador = $partidaRepo->obtenerGanador($partida, $matrizFichas);

        if (!$ganador && $numFichasPuestas + 1 === $dimensionTablero) { //Empate
            return $this->redirectToRoute('finalizar_partida', [
                'partida_id' => $partida->getId(),
                'ganador_id' => null
            ]);
        }
        if ($ganador) {
            return $this->redirectToRoute('finalizar_partida', [
                'partida_id' => $partida->getId(),
                'ganador_id' => $ganador->getId()
            ]);
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/partida/{partida_id}/finalizar/{ganador_id}", name="finalizar_partida", defaults={"ganador_id" = null})
     *
     * @ParamConverter("partida", options={"id" : "partida_id"})
     * @ParamConverter("ganador", options={"id" : "ganador_id"})
     */
    public function finalizarPartida(Partida $partida, Jugador $ganador = null)
    {
        $em = $this->getDoctrine()->getManager();

        $empate = false;

        if (!$ganador)
            $empate = true;

        $partida->setEmpate($empate);
        $partida->setGanador($ganador);
        $partida->setFinalizada(true);
        $partida->setFin(new \DateTime("now"));
        $partida->setEnCurso(false);

        //Actualizamos el estado de la partida
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}
