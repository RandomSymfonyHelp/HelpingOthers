   /**
     * @Route("/", name="aanmelding_index", methods={"GET"})
     */
    public function index(AanmeldingRepository $aanmeldingRepository): Response
    {
        return $this->render('aanmelding/index.html.twig', [
            'aanmeldings' => $aanmeldingRepository->findAll(),
            'status' => '200',
        ]);
    }

    /**
     * @Route("/new", name="aanmelding_new", methods={"GET","POST"})
     * Bij deze functie voor het nieuw aanmaken van een aanmelding wordt eerst nagelopen of het toernooi is gesloten doormiddel van de 
     * gesubmitte data langs te lopen
     */
    public function new(AanmeldingRepository $aanmeldingRepository, TornooiRepository $tornooiRepository, Request $request): Response
    {
        $aanmelding = new Aanmelding();
        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gesloten = $form->getData()->getToernooi()->getGesloten();
            // die($gesloten);
            if($gesloten == 1)
            {
                return $this->render('aanmelding/index.html.twig',[
                    'aanmeldings' => $aanmeldingRepository->findAll(),
                    'status' => '404',
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aanmelding);
            $entityManager->flush();

            return $this->redirectToRoute('aanmelding_index');
        }

        return $this->render('aanmelding/new.html.twig', [
            'aanmelding' => $aanmelding,
            'form' => $form->createView(),
        ]);
    }


/*
{% if status == 404 %}
<script> alert('Dit toernooi is gesloten. Aanmedlingen kunnen niet meer gedaan worden') </script>
{% endif %}
*/
