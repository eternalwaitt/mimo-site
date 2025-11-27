<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Service configuration
$serviceName = 'Estética Facial';
$headerClass = 'facial-header';
$headerTitle = 'ESTÉTICA FACIAL';
$includeGTM = false;

// Custom modals
ob_start();
?>
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
                <a class="btn btnAgendamento"
                    href="https://api.whatsapp.com/send/?phone=5511994781012&text=Ol%C3%A1,+vim+pelo+site+e+queria+mais+informa%C3%A7%C3%B5es"
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
                <!-- Modal removido - agendamento via WhatsApp -->
            </div>
        </div>
    </div>
</div>
<?php
$customContentBeforeBanner = ob_get_clean();

// Define tabs
$tabs = [
    ['id' => 'limpeza', 'label' => 'limpeza de pele', 'active' => true],
    ['id' => 'microagulhamento', 'label' => 'microagulhamento', 'active' => false]
];

// Tab content - Limpeza de Pele
ob_start();
?>
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
            <div class="col-md-8 service-content">
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
            <div class="col-md-8 service-content">
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

    </div>
</div>
<?php
$tabContent['limpeza'] = ob_get_clean();

// Tab content - Microagulhamento
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5" style="padding-top: 50px;min-height: 300px;">
            <div class="col-md-4 imgmicroagulhamento" style="float:left;"></div>
            <div class="col-md-8 service-content">
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
<?php
$tabContent['microagulhamento'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
