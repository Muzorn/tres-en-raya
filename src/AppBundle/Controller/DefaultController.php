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

        //Tratamos de obtener la última Partida jugada (esté o no finalizada)
        $partida = $partidaRepo->findOneBy([], ['inicio' => 'DESC']);

        $tablero = $fichas = $matrizFichas = null;

        if ($partida) {
            /* @var Tablero $tablero */
            $tablero = $partida->getTablero();
            /* @var Ficha :array $fichas */
            $fichas = $tablero->getFichas();

            $matrizFichas = $em->getRepository('AppBundle:Tablero')->getMatrizFichasPuestas($tablero);
        }

        //Cargamos la Partida
        return $this->render('default/partida.html.twig', [
            'partida' => $partida,
            'tablero' => $tablero,
            'fichas' => $matrizFichas
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
        $partidaRepo = $em->getRepository('AppBundle:Partida');
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
