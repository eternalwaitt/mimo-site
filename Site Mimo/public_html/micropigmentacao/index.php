<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Image helper functions for WebP support
require_once '../inc/image-helper.php';

// Service configuration
$serviceName = 'Micropigmentação';
$headerClass = 'micro-header';
$headerTitle = 'MICROPIGMENTAÇÃO';
$includeGTM = false;

// Define tabs
$tabs = [
    ['id' => 'sobrancelha', 'label' => 'SOBRANCELHAS', 'active' => true],
    ['id' => 'labial', 'label' => 'LÁBIOS', 'active' => false],
    ['id' => 'despig', 'label' => 'DESPIGMENTAÇÃO', 'active' => false]
];

// Tab content - Sobrancelhas
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 microsobrancelha" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">Nanopigmentação</h3>
                <p style="letter-spacing: 0.8px">
                    <b>Nanopigmentação</b> é uma técnica de fio a fio hiper realista realizada na sobrancelha com um instrumento chamado tebori. A Mimo tem se destacado pela naturalidade no procedimento de Nanopigmentação e isso nos tem feito muito feliz, pois esse resultado é fruto de um trabalho árduo de pesquisas, treinamentos e estudos das nossas profissionais. Desenvolvemos uma técnica própria e uma forma diferente de trabalhar do mercado, e é muito importante que você conheça nosso perfil para identificação com o nosso trabalho e ideologia, como também para que fique segura da sua escolha.
                </p>
                <p style="letter-spacing: 0.8px">
                    Existem três pilares que sustentam a naturalidade do nosso procedimento:
                </p>
                <ul style="letter-spacing: 0.8px">
                    <li><b>Uso de pigmento orgânico:</b> entendemos sobrancelha como uma parte do rosto responsável pela transmissão de emoções, que, por sua vez, sofre interferências de um contexto pessoal, político e social que você esteja inserida. Enxergamos a sobrancelha como uma moldura capaz de passar para o mundo uma mensagem única, própria e cíclica. Nesse sentido, o pigmento orgânico proporciona uma durabilidade semipermanente ao procedimento, de 6 (seis) meses a 1 (um) ano, sendo absorvido pelo corpo no decorrer do tempo. Além disso, os riscos de alteração de tons são minimizados quando a cliente segue todos os cuidados recomendados.</li>
                    <li><b>Não utilizamos medidas:</b> na Mimo o trabalho é artístico e todo feito à mão. As profissionais utilizam o visagismo e conversam muito com a cliente para alcance do design adequado. Não acreditamos em medidas padrões para os diversos rostos e personalidades, sendo o desenho explicado pela profissional e aprovado pela cliente antes da realização dos fios.</li>
                    <li><b>Não acreditamos em correções de assimetria:</b> em trabalho conjunto com dermatologistas, observamos que as correções de assimetrias de sobrancelhas, podem, por vezes, nos deixar mais desarmônicas. Para alcance de um resultado natural é necessário o respeito às assimetrias que são naturais do nosso corpo. Um bom exemplo disso, são as imagens obtidas nos aplicativos que duplicam um lado do rosto. Além disso, algumas correções causam dependência da cliente à um procedimento de Nanopigmentação, uma vez que os fios serão por um bom período de tempo extraídos de um determinado lugar e terão maior dificuldade de nascer. Na Mimo seguimos o desenho natural da sua sobrancelha.</li>
                </ul>
                <p class="textDarkGrey" style="font-size: 17px;">Valor: R$ 499,00 (em até 3x no cartão de crédito) (retoque de 30 à 45 dias incluso, se necessário)</p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['sobrancelha'] = ob_get_clean();

// Tab content - Lábios
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 microlips" style="float:left;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">Revitalização Labial</h3>
                <p style="letter-spacing: 0.8px">
                    A <b>revitalização labial</b> da Mimo buscar oferecer naturalidade através de uma harmonização da colorimetria dos lábios. Esse procedimento de micropigmentação é feito com um aparelho chamado dermógrafo, e pode levar até 2 horas. A durabilidade varia de organismo pra organismo, muitas vezes necessário retoque em até 70 dias após o primeiro procedimento, e dura até 2 anos.
                </p>
                <p style="letter-spacing: 0.8px">
                    <b>Para agendamento:</b>
                </p>
                <ul style="letter-spacing: 0.8px">
                    <li>Necessário hidratar os lábios durante 1 semana antes com nívea de tampa azul.</li>
                    <li>Tomar um relaxante muscular, de sua preferência, meia hora antes do procedimento.</li>
                    <li>Tomar antiviral, de sua preferência, 2 dias antes do procedimento.</li>
                </ul>
                <p style="letter-spacing: 0.8px">
                    <b>Cuidados:</b>
                </p>
                <ul style="letter-spacing: 0.8px">
                    <li>Não molhar nas primeiras 24h.</li>
                    <li>Hidratar com nívea de tampa azul durante 30 dias.</li>
                    <li>Não passar maquiagem por 30 dias.</li>
                    <li>Não beijar durante 5 dias.</li>
                    <li>Não expor ao sol por 15 dias.</li>
                    <li>Não arrancar a pele labial.</li>
                </ul>
                <p class="textDarkGrey" style="font-size: 17px;">Valor: R$ 449,90 (retoque de 30 à 45 dias incluso, se necessário)</p>
            </div>
        </div>
        <div class="col-md-12 container my-5">
            <div class="col-md-4" style="float:left;width: 200px; height: 200px; overflow: hidden;">
                <?php echo picture_webp('MimoGloss.png', 'Mimo Gloss', '', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
            </div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MIMO GLOSS</h3>
                <p style="letter-spacing: 0.8px">
                    O <b>Mimo Gloss</b> é um procedimento de hidratação labial, onde é feita esfoliação com produtos específicos e é aplicado ácido hialurônico com nanoagulhas superficiais para hidratação acontecer de dentro pra fora durante um mês, aproximadamente. Não é um procedimento invasivo e é preciso se atentar nas contra-indicações do procedimento.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">R$ 150,00</p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['labial'] = ob_get_clean();

// Tab content - Despigmentação
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container my-5">
            <div class="col-md-4 despig" style="float:left;width: 200px;"></div>
            <div class="col-md-8 service-content">
                <h3 class="textPink font-weight-bold">MIMO DESPIGMENTAÇÃO</h3>
                <p style="letter-spacing: 0.8px">
                    A Remoção de Sobrancelha na Mimo é feita de forma segura e eficaz com o laser ND YAG, onde o pigmento é absorvido pelo nosso organismo e é eliminado através da nossa urina.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">Valor: R$ 180,00</p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['despig'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
