<?php
// Suppress deprecation warnings (PHP 8.4 compatibility)
error_reporting(E_ALL & ~E_DEPRECATED);

// Service configuration
$serviceName = 'Esmalteria';
$headerClass = 'esmal-header';
$headerTitle = 'ESMALTERIA';
$includeGTM = true;

// Define tabs
$tabs = [
    ['id' => 'alongamentos', 'label' => 'alongamentos', 'active' => true],
    ['id' => 'banhodegel', 'label' => 'blindagem', 'active' => false],
    ['id' => 'manicure', 'label' => 'manicure & pedicure', 'active' => false]
];

// Tab content - Alongamentos
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container">
            <div class="col-md-12 m-auto" style="padding: 0;">
                <p style="letter-spacing: 0.8px">
                    A Mimo oferece o alongamento de Polygel, visando a agilidade de durabilidade do procedimento.
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 col-xs-12 imgaporcelana" style="float:left;"></div>
            <div class="col-md-8 col-xs-12 service-content">
                <h3 class="textPink font-weight-bold">Alongamento de Polygel com Molde F1</h3>
                <p style="letter-spacing: 0.8px">
                    O Alongamento de Polygel com Molde F1 une a força do acrílico e a leveza do gel, e é realizado de forma pré-moldada para facilitar a aplicação, resultando em unhas mais naturais e resistentes.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    <b>Primeira sessão:</b> R$230,00<br>
                    <b>Manutenção:</b> R$180,00<br>
                    <b>Manutenção acima de 30 dias:</b> R$200,00 <br>
                    Não faz reconstrução.<br><br>
                    <b>Duração:</b> Aproximadamente 4 semanas, podendo ser prolongado com os cuidados adequados. Indicamos manutenção a cada 21 dias<br>
                    <b>Tempo de Procedimento:</b> 1h30 <br><br>
                    <b>OBS:</b> Cutilagem e esmaltação simples (tradicional ou em gel) incluídas.
                </p>
                <p class="textDarkGrey" style="font-size: 16px;">
                    <b>IMPORTANTE:</b> A resistência e a durabilidade dos alongamentos vai depender do período de adaptação e os cuidados, podendo chegar até 30 dias. A manutenção dos alongamentos é opcional para mantê-los com aspecto saudável e natural. Porém, se a extensão passar dos 30 dias é feita a remoção e o processo é iniciado do zero.
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['alongamentos'] = ob_get_clean();

// Tab content - Blindagem
ob_start();
?>
<div class="col-md-12 mt-4 position-relative">
    <div class="text-left">
        <div class="col-md-12 container">
            <div class="col-md-12 m-auto" style="padding: 0;">
                <p style="letter-spacing: 0.8px">
                    A Blindagem de Unha não é um tratamento para as suas unhas crescerem mais fortes. É o produto aplicado sobre suas unhas naturais deixando-as mais firmes, impedindo que quebrem com facilidade.
                </p>
            </div>
        </div>
        <br />
        <div class="col-md-12 container my-5">
            <div class="col-md-4 col-xs-12 imgbporcelana" style="float:left;"></div>
            <div class="col-md-8 col-xs-12 service-content">
                <h3 class="textPink font-weight-bold">Blindagem de Unhas</h3>
                <p style="letter-spacing: 0.8px">
                    A Blindagem de Unhas ou blindagem de gel é uma camada de gel que permite que sua unha natural cresça sem quebrar. O objetivo da blindagem é proteger a unha e torná-la mais resistente a quebra, rachaduras e outros tipos de danos, prolongando também o tempo do esmalte. Na blindagem não é possível alongar a unha.
                </p>
                <p class="textDarkGrey" style="font-size: 17px;">
                    <b>Primeira sessão:</b> R$200 <br>
                    <b>Manutenção até 30 dias:</b> R$180 <br><br>
                    <b>Duração:</b> Aproximadamente 4 semanas, podendo ser prolongado com os cuidados adequados. Indicamos manutenção a cada 21 dias. <br><br>
                    <b>Tempo de Procedimento:</b> 2hs <br><br>
                    <b>OBS:</b> Cutilagem e esmaltação simples (tradicional ou em gel) incluídas.
                </p>
                <p class="textDarkGrey" style="font-size: 16px;">
                    <b>IMPORTANTE:</b> A resistência e a durabilidade do procedimento vai depender do período de adaptação e os cuidados, podendo chegar até 30 dias. A manutenção da blindagem é opcional para mantê-los com aspecto saudável e natural. Porém, se o procedimento passar dos 30 dias é feita a remoção e o processo é iniciado do zero.
                    <br><br>
                    Em caso de unhas extremamente fragilizadas, indicamos procurar um dermatologista.
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$tabContent['banhodegel'] = ob_get_clean();

// Tab content - Manicure & Pedicure
ob_start();
?>
<style>
    td {
        font-size: 17px;
    }
    .tableManicure table {
        margin: 0 auto;
        width: auto;
    }
    .tableManicure th,
    .tableManicure td {
        text-align: center;
        padding: 8px 20px;
    }
    .tableManicure th {
        background-color: #f2f2f2;
    }
</style>
<div class="col-md-12 mt-4 container m-auto tableManicure" style="display: flex;justify-content: center;">
    <div class="col-md-12" style="margin-bottom: 80px;">
        <table>
            <tr>
                <th colspan="2">ESMALTAÇÃO TRADICIONAL</th>
            </tr>
            <tr>
                <td>Manicure</td>
                <td>R$50</td>
            </tr>
            <tr>
                <td>Pedicure</td>
                <td>R$60</td>
            </tr>
            <tr>
                <td>Manicure e Pedicure</td>
                <td>R$80</td>
            </tr>
        </table>

        <br><br>

        <table>
            <tr>
                <th colspan="2">ESMALTAÇÃO EM GEL</th>
            </tr>
            <tr>
                <td>Manicure</td>
                <td>R$80</td>
            </tr>
            <tr>
                <td>Pedicure</td>
                <td>R$90</td>
            </tr>
            <tr>
                <td>Manicure e Pedicure</td>
                <td>R$120</td>
            </tr>
        </table>

        <br><br>

        <table>
            <tr>
                <th colspan="2">Nail Art Basic</th>
            </tr>
            <tr>
                <td>Esmalte magnético, esmalte jelly, esmalte refletivo, esmalte e pó holográfico, esmalte térmico</td>
            </tr>
            <tr>
                <td>Completo</td>
                <td>R$30,00</td>
            </tr>
            <tr>
                <td>Unitário</td>
                <td>R$4,00</td>
            </tr>
            <tr>
                <th colspan="2">Nail Art Simples</th>
            </tr>
            <tr>
                <td>Desenhos básicos, francesinha, e/ou mistura de serviços do "basic"</td>
                <td>R$50,00</td>
            </tr>
            <tr>
                <th colspan="2">Nail Art Personalizada</th>
            </tr>
            <tr>
                <td>Relevos em gel, desenhos abstratos, mistura de serviços de "nail art simples, basic"</td>
                <td>R$100,00</td>
            </tr>
            <tr>
                <th colspan="2">Nail Art Super Personalizada</th>
            </tr>
            <tr>
                <td>Desenhos a mão livre, misturando todos os serviços</td>
                <td>R$200,00</td>
            </tr>
        </table>
    </div>
</div>
<?php
$tabContent['manicure'] = ob_get_clean();

// Include the template
include '../inc/service-template.php';
?>
