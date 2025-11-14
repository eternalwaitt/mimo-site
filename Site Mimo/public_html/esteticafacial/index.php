<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Oferecendo o que há de melhor no mundo da Estética com preços acessíveis. Você merece esse mimo.">
    <meta name="author" content="Michelle Fernandes">

    <title>MINHA MIMO - Estética Facial</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400|EB+Garamond:400,400i" rel="stylesheet">
    <link href="../Akrobat-Regular.woff" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- CSS -->
    <link href="../bootstrap/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../product.css" rel="stylesheet">
    <link href="../servicos.css" rel="stylesheet">
    <link href="../form/css/font-awesome.min.css" rel="stylesheet">
    <link href="../form/main.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <?php include "../inc/header-inner.php"; ?>

    <!-- Modal Impedimento -->
    <div class="modal fade" id="exampleModalCenterNav" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="textPink text-center my-3">ATENÇÃO!</h3>
                    <p class="textDarkGrey text-center">
                        Para concluir o agendamento é necessário depósito de <b>R$20</b> para cada procedimento
                        agendado, a fim de evitar segurarmos vagas na agenda e haver falta no dia. Solicitamos que seja
                        depositado em até <b>48 horas</b> após o pré-agendamento para confirmar seu horário. Caso não
                        seja efetuado o agendamento é cancelado para não segurar vaga na agenda.
                        <br><br>
                        <b>O comprovante deve ser enviado para nossa equipe de atendimento via Whatsapp 11
                            99478-1012.</b>
                        <br><br>
                        <span class="textPink font-weight-bold">O restante do valor deverá ser pago no dia do
                            atendimento. Aceitamos dinheiro ou cartão débito/crédito.</span><br />
                        Pedimos por gentileza que se atente ao horário agendado, pois atrasos superiores a 10 minutos
                        podem ocasionar o cancelamento do seu atendimento sem a possibilidade de novo agendamento. <br>
                        Obs. O não comparecimento com aviso prévio de 48hrs acarretará na perda integral do valor
                        depositado. Caso não possa comparecer nos comunique com 48hrs de antecedência e o valor será
                        creditado para reagendar uma nova visita.
                        <br><br>
                        <span class="textPink font-weight-bold">Dados bancários:</span><br>
                        MIMO CENTRO DE BELEZA LTDA <br>
                        28.038.663/0001-14 <br>
                        BANCO ITAÚ: 341 <br>
                        AGÊNCIA VILLA LOBOS: 2954 <br>
                        CONTA CORRENTE: 17793-3<br>
                        PIX: 28.038.663/0001-14
                    </p>
                </div>
                <button data-dismiss="modal" class="d-none d-sm-block">
                    <a class="btn btnAgendamento"
                        href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                        target="_blank" data-target="#exampleModalCenterNav2" style="width: 100%">ENTENDI E QUERO
                        AGENDAR</a>
                </button>
                <button class="d-sm-none">
                    <a class="btn btnAgendamento" href="https://agendamento.salaovip.com.br/?slug=mimoestetica"
                        target="_blank" style="width: 100%">ENTENDI E QUERO AGENDAR</a>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenterNav2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="agendaWidgetSalaoNav" width="100%" height="520px"
                        style="border:none; border-radius: 10px;"
                        src="https://agendamento.salaovip.com.br/?slug=mimoestetica"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner -->
    <div class="position-relative overflow-hidden facial-header text-center text-white">
        <h2 style="line-height: 9; font-size: 45px;">ESTÉTICA FACIAL</h2>
    </div>

    <!-- Navigation -->
    <ul class="nav nav-pills mt-5 mb-5" id="pills-tab" role="tablist" style="padding-right: 10%;">
        <li class="nav-item voltarBtn">
            <a class="nav-link" href="../#services"
                style="font-size: 14px; width: 101%; margin-left: 36px; text-transform: lowercase;">
                &lt; voltar
            </a>
        </li>
        <li class="nav-item" style="margin: auto">
            <a class="nav-link active" id="pills-limpezaa" data-toggle="pill" href="#pills-limpeza" role="tab"
                aria-controls="pills-limpeza" aria-selected="true">
                limpeza de pele
            </a>
        </li>
        <li class="nav-item" style="margin: auto">
            <a class="nav-link" id="pills-microagulhamentoo" data-toggle="pill" href="#pills-microagulhamento"
                role="tab" aria-controls="pills-microagulhamento" aria-selected="false">
                microagulhamento
            </a>
        </li>
        <li class="nav-item" style="margin: auto">
            <a class="nav-link" id="pills-mcuidados" data-toggle="pill" href="#pills-cuidados" role="tab"
                aria-controls="pills-cuidados" aria-selected="false">
                mimo cuidados
            </a>
        </li>
    </ul>

    <!-- Content -->
    <div class="tab-content mb-5" id="pills-tabContent">
        <!-- Limpeza de Pele -->
        <div class="tab-pane fade show active" id="pills-limpeza" role="tabpanel" aria-labelledby="pills-limpeza">
            <div class="col-md-12 mt-4 position-relative">
                <div class="text-left">
                    <div class="col-md-12 container">
                        <div class="col-md-12 m-auto" style="padding: 0;">
                            <p style="letter-spacing: 0.8px">
                                A Mimo trabalha hoje com 03 tipos de limpeza de pele, tendo recomendações diferentes
                                para cada cliente. Este procedimento não é um tratamento, é um serviço para fazer a
                                remoção de impurezas. Dos tipos que trabalhamos, seguem:
                            </p>
                        </div>
                    </div>

                    <!-- Limpeza Mimo -->
                    <div class="col-md-12 container my-5">
                        <div class="col-md-4 imglmimo" style="float:left;"></div>
                        <div class="col-md-8" style="margin-left: 240px;">
                            <h3 class="textPink font-weight-bold">Limpeza de Pele Mimo</h3>
                            <p style="letter-spacing: 0.8px">
                                A <b>Limpeza de Pele</b> Mimo visa remover as secreções que ficam retidas na pele para
                                desobstruir os poros; remover comedões e milliuns; promover o afinamento, oxigenar a
                                pele, reestabelecer o equilíbrio hidro lipídico. Preparar a pele para tratamentos
                                estéticos.
                                Passo a passo: higienização > esfoliação > tonificação > emoliência > vapor de ozônio >
                                peeling ultrassônico > extração manual e com cureta > tonificação > alta frequência >
                                máscara > tonificação > protetor solar.
                            </p>
                            <p class="textDarkGrey" style="font-size: 17px;">
                                R$ 149,90
                            </p>
                            <p class="font-weight-bold textDarkGrey" style="font-size: 16px;">
                                O procedimento de limpeza de pele não tira todas as impurezas na primeira sessão. É
                                importante consultar nossas profissionais para a indicação da quantidade e, se
                                necessário, o tratamento correto para que o resultado desejado seja alcançado.
                            </p>
                            <div class="nav-item">
                                <a class="btn btnAgendamento"
                                    href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                                    target="_blank" data-target="#MImpedimento">AGENDAR</a>
                            </div>
                        </div>
                    </div>

                    <!-- Limpeza VIP -->
                    <div class="col-md-12 container my-5">
                        <div class="col-md-4 imglmimovip" style="float:left;"></div>
                        <div class="col-md-8" style="margin-left: 240px;">
                            <h3 class="textPink font-weight-bold">Limpeza de Pele Mimo VIP</h3>
                            <p style="letter-spacing: 0.8px">
                                <b>PELES COMEDOGÊNICAS:</b> A <b>Limpeza de Pele Mimo VIP</b> para peles comedogênicas
                                visa remover as secreções que ficam retidas na pele para desobstruir os poros; remover
                                acne, comedões e millius; promover o afinamento, oxigenar a pele, reestabelecer o
                                equilíbrio hidrolipídico; favorecer cicatrização. <br>
                                Passo a passo: higienização > esfoliação > peeling de diamante > emoliência > vapor de
                                ozônio > extração manual e com cureta > tonificação > alta frequência > máscara > sérum
                                > protetor solar. <br>
                                *todos os produtos utilizados são específicos para o tratamento de comedões. <br><br>

                                <b>PELES ACNEICAS:</b> A <b>Limpeza de Pele Mimo VIP</b> para peles acneicas visa
                                remover as secreções que ficam retidas na pele para desobstruir os poros; remover acne,
                                comedões e millius; promover o afinamento, oxigenar a pele, reestabelecer o equilíbrio
                                hidrolipídico; favorecer cicatrização. <br>
                                Passo a passo: higienização > esfoliação > emoliência > vapor de ozônio > extração
                                manual e com cureta > loção adstringente > alta frequência > máscara > sérum > protetor
                                solar. <br>
                                *todos os produtos utilizados são específico para o tratamento de pele acneica.
                            </p>
                            <p class="textDarkGrey" style="font-size: 17px;">
                                R$ 199,90
                            </p>
                            <p class="font-weight-bold textDarkGrey" style="font-size: 16px;">
                                O procedimento de limpeza de pele não tira todas as impurezas na primeira sessão. É
                                importante consultar nossas profissionais para a indicação da quantidade e, se
                                necessário, o tratamento correto para que o resultado desejado seja alcançado.
                            </p>
                            <div class="nav-item">
                                <a class="btn btnAgendamento"
                                    href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                                    target="_blank" data-target="#MImpedimento2">AGENDAR</a>
                            </div>
                        </div>
                    </div>

                    <!-- Combo GlowUp -->
                    <div class="col-md-12 container my-5">
                        <div class="col-md-4 imglmimovip" style="float:left;"></div>
                        <div class="col-md-8" style="margin-left: 240px;">
                            <h3 class="textPink font-weight-bold">Combo GlowUp</h3>
                            <p style="letter-spacing: 0.8px">
                                Limpeza de pele Mimo + Revitalift Mimo<br><br>
                                - <b>Limpeza de pele Mimo</b> é um tratamento para remover secreções e desobstruir os
                                poros, remover comedões, afinar a pele, oxigenar, reestabelecer o equilíbrio
                                hidrolipídico e preparar para tratamentos estéticos.<br>
                                - <b>Revitalift</b> é um tratamento de cuidados com a pele que utiliza uma máscara à
                                base de ácido retinoico em baixa concentração, criada especialmente para revitalizar a
                                pele.<br><br>

                                É um procedimento seguro e eficaz, que pode ser realizado em pessoas de todas as idades,
                                um tratamento versátil e benéfico para várias necessidades da pele, e pode ser realizada
                                uma vez por mês para obter melhores resultados em tratamento preventivo e estimulação de
                                colágeno.<br><br>
                            </p>
                            <p class="textDarkGrey" style="font-size: 17px;">
                                R$ 349,90
                            </p>
                            <div class="nav-item">
                                <a class="btn btnAgendamento"
                                    href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                                    target="_blank" data-target="#MImpedimento2">AGENDAR</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Microagulhamento -->
        <div class="tab-pane fade" id="pills-microagulhamento" role="tabpanel" aria-labelledby="pills-microagulhamento">
            <div class="col-md-12 mt-4 position-relative">
                <div class="text-left">
                    <div class="col-md-12 container my-5" style="padding-top: 50px;min-height: 300px;">
                        <div class="col-md-4 imgmicroagulhamento" style="float:left;"></div>
                        <div class="col-md-8" style="margin-left: 240px;">
                            <p style="letter-spacing: 0.8px">
                                O <b>Microagulhamento</b> é método de micro perfurações na pele que estimulam os
                                fibroblastos, produzindo colágeno e elastina. É indicado também na flacidez, cicatrizes
                                de acne, estrias, manchas, tratamento e prevenção de linhas de expressão. O procedimento
                                é realizado com a dermapen, com microagulhas de profundidade específica para cada tipo
                                de tratamento, visando chegar até a camada da derme. <br>
                                Para realização do procedimento, é necessário avaliação profissional.
                            </p>
                            <p class="textDarkGrey" style="font-size: 17px;">
                                Sessão individual: R$ 390,00 <br>
                                Pacote com 03 sessões: R$ 1053,00<br />
                                Pacote com 05 sessões: R$ 1755,00
                            </p>
                            <div class="nav-item mt-3">
                                <a class="btn btnAgendamento"
                                    href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                                    target="_blank" data-target="#exampleModalCenterImpedimento">AGENDAR</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mimo Cuidados -->
        <div class="tab-pane fade" id="pills-cuidados" role="tabpanel" aria-labelledby="pills-cuidados">
            <div class="col-md-12 container my-5">
                <div class="col-md-4 imglmimo" style="float:left;"></div>
                <div class="col-md-8" style="margin-left: 240px;">
                    <h3 class="textPink font-weight-bold">Revitalização Facial</h3>
                    <p style="letter-spacing: 0.8px">
                        A <b>Revitalização facial</b> é um procedimento personalizado para cada tipo de pele, criado
                        para restaurar as funções de hidratação, nutrição e oxigenação da pele, pois sofremos no dia a
                        dia casos de: radiação solar, poluição, entre outras coisas. O procedimento visa entregar
                        nutrientes e aminoácidos que são necessários para o tecido cutâneo funcionar de forma adequada.
                    </p>
                    <p style="font-size: 16px;">
                        Este procedimento inclui: <br>
                        - Sabonete de higienização; <br>
                        - Esfoliante Manual; <br>
                        - Peeling Ultrassônico para remover impurezas superficiais; <br>
                        - Máscara finalizadora; <br>
                        - Sérum potencializador.
                    </p>
                    <p class="textDarkGrey" style="font-size: 17px;">
                        R$ 80,00
                    </p>
                    <div class="nav-item">
                        <a class="btn btnAgendamento"
                            href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
                            target="_blank" data-target="#MImpedimentoCuidados">AGENDAR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="container">
        <div class="row">
            <div class="col-12 col-md my-2 py-2">
                <small class="d-block text-muted text-center" style="line-height: 3;">&copy; MIMO Estética 2018 | Todos
                    os direitos reservados</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../bootstrap/jquery/dist/jquery.slim.min.js"><\/script>')</script>
    <script src="../bootstrap/popper.js/dist/popper.min.js"></script>
    <script src="../bootstrap/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../form/main.js"></script>
    <script src="../main.js"></script>
    <script src="//code.tidio.co/ylbfxpiqcmi2on8duid7rpjgqydlrqne.js"></script>
</body>

</html>