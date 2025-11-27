import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { MIMO_CONTACT, MIMO_COMPANY, getWhatsAppContactUrl } from '@/lib/constants/index'

export const metadata: Metadata = {
  title: 'política de privacidade',
  description: 'Política de Privacidade da Mimo - Conheça como tratamos seus dados pessoais em conformidade com a LGPD.',
  alternates: {
    canonical: 'https://minhamimo.com.br/privacidade',
  },
  openGraph: {
    title: 'Política de Privacidade | Mimo Salão',
    description: 'Política de Privacidade da Mimo - Conheça como tratamos seus dados pessoais em conformidade com a LGPD.',
    url: 'https://minhamimo.com.br/privacidade',
    type: 'website',
  },
  robots: {
    index: true,
    follow: true,
  },
}

/**
 * página de política de privacidade - compliance lgpd.
 * 
 * - informações sobre coleta e tratamento de dados
 * - direitos do titular (lgpd art. 18)
 * - contato do encarregado de dados
 * - base legal e finalidade
 * - compartilhamento com terceiros (plausible analytics)
 */
export default function PrivacidadePage() {
  const lastUpdated = '2025-01-29'

  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero */}
        <section className="relative h-[40vh] min-h-[250px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Política de Privacidade
            </h1>
            <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
              Transparência sobre como tratamos seus dados pessoais
            </p>
            <p className="font-satoshi text-sm text-mimo-blue/70 mt-2">
              Última atualização: {new Date(lastUpdated).toLocaleDateString('pt-BR')}
            </p>
          </div>
        </section>

        {/* Conteúdo */}
        <section className="py-16 md:py-24 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div className="prose prose-lg max-w-none space-y-8">
              {/* Introdução */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  1. Introdução
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  A {MIMO_COMPANY.name} ({MIMO_COMPANY.legalName}), CNPJ {MIMO_COMPANY.cnpj}, 
                  está comprometida com a proteção da privacidade e dos dados pessoais de seus usuários, 
                  em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018) e 
                  demais regulamentações aplicáveis.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Esta Política de Privacidade descreve como coletamos, utilizamos, armazenamos e 
                  protegemos suas informações pessoais quando você utiliza nosso site.
                </p>
              </div>

              {/* Dados Coletados */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  2. Dados Coletados
                </h2>
                <h3 className="font-bueno text-xl font-bold text-mimo-brown mb-3 mt-6">
                  2.1. Dados de Navegação (Analytics)
                </h3>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Utilizamos o <strong>Plausible Analytics</strong>, uma ferramenta de análise de 
                  privacidade que coleta dados agregados e anonimizados sobre o uso do site, incluindo:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Páginas visitadas</li>
                  <li>Origem do tráfego (referrer)</li>
                  <li>Dispositivo e navegador utilizados</li>
                  <li>País de origem (nível agregado)</li>
                </ul>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  <strong>Importante:</strong> O Plausible Analytics não utiliza cookies, não coleta 
                  dados pessoais identificáveis (como IP completo, nome, email) e não cria perfis 
                  de usuários. Todos os dados são agregados e anonimizados.
                </p>

                <h3 className="font-bueno text-xl font-bold text-mimo-brown mb-3 mt-6">
                  2.2. Dados de Contato
                </h3>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Quando você entra em contato conosco via WhatsApp, email ou telefone, podemos 
                  coletar e armazenar:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Nome</li>
                  <li>Número de telefone</li>
                  <li>Endereço de email</li>
                  <li>Mensagens e histórico de comunicação</li>
                </ul>
              </div>

              {/* Finalidade */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  3. Finalidade do Tratamento
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Utilizamos seus dados pessoais para as seguintes finalidades:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Melhorar a experiência de navegação no site</li>
                  <li>Entender como os usuários interagem com nosso conteúdo</li>
                  <li>Responder a solicitações de contato e agendamentos</li>
                  <li>Cumprir obrigações legais e regulatórias</li>
                  <li>Garantir a segurança e integridade do site</li>
                </ul>
              </div>

              {/* Base Legal */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  4. Base Legal
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  O tratamento de dados pessoais é realizado com base nas seguintes hipóteses legais 
                  previstas na LGPD:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li><strong>Consentimento:</strong> Para dados de navegação via analytics (você pode optar por não utilizar o site)</li>
                  <li><strong>Execução de contrato:</strong> Para atendimento de solicitações de contato e agendamento</li>
                  <li><strong>Legítimo interesse:</strong> Para melhorias no site e segurança</li>
                  <li><strong>Cumprimento de obrigação legal:</strong> Quando exigido por lei</li>
                </ul>
              </div>

              {/* Compartilhamento */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  5. Compartilhamento de Dados
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Seus dados pessoais podem ser compartilhados apenas nas seguintes situações:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>
                    <strong>Plausible Analytics:</strong> Dados agregados e anonimizados são 
                    processados pelo Plausible (serviço hospedado na UE, em conformidade com GDPR)
                  </li>
                  <li>
                    <strong>Prestadores de serviço:</strong> Empresas que nos auxiliam na operação 
                    do site (hospedagem, infraestrutura), sempre sob rigorosos contratos de 
                    confidencialidade
                  </li>
                  <li>
                    <strong>Autoridades competentes:</strong> Quando exigido por lei ou ordem judicial
                  </li>
                </ul>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  <strong>Não vendemos, alugamos ou comercializamos seus dados pessoais.</strong>
                </p>
              </div>

              {/* Retenção */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  6. Retenção de Dados
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Mantemos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades 
                  descritas nesta política ou conforme exigido por lei:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li><strong>Dados de navegação:</strong> Armazenados de forma agregada e anonimizada pelo Plausible Analytics</li>
                  <li><strong>Dados de contato:</strong> Mantidos enquanto houver relacionamento comercial ou até solicitação de exclusão</li>
                  <li><strong>Dados legais:</strong> Conforme período de retenção exigido por lei (ex: documentos fiscais)</li>
                </ul>
              </div>

              {/* Direitos do Titular */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  7. Direitos do Titular (LGPD Art. 18)
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Você tem os seguintes direitos em relação aos seus dados pessoais:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li><strong>Confirmação e acesso:</strong> Saber se tratamos seus dados e acessá-los</li>
                  <li><strong>Correção:</strong> Solicitar correção de dados incompletos, inexatos ou desatualizados</li>
                  <li><strong>Anonimização, bloqueio ou eliminação:</strong> Solicitar a remoção de dados desnecessários, excessivos ou tratados em desconformidade com a LGPD</li>
                  <li><strong>Portabilidade:</strong> Receber seus dados em formato estruturado e interoperável</li>
                  <li><strong>Eliminação:</strong> Solicitar a exclusão de dados tratados com base em consentimento</li>
                  <li><strong>Revogação do consentimento:</strong> Retirar seu consentimento a qualquer momento</li>
                  <li><strong>Informação sobre compartilhamento:</strong> Saber com quem compartilhamos seus dados</li>
                  <li><strong>Oposição:</strong> Opor-se ao tratamento de dados em certas situações</li>
                </ul>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Para exercer seus direitos, entre em contato conosco através dos canais indicados 
                  na seção "Contato e Encarregado de Dados" abaixo.
                </p>
              </div>

              {/* Segurança */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  8. Segurança dos Dados
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Adotamos medidas técnicas e organizacionais adequadas para proteger seus dados 
                  pessoais contra acesso não autorizado, alteração, divulgação ou destruição, 
                  incluindo:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Criptografia de dados em trânsito (HTTPS/SSL)</li>
                  <li>Controles de acesso restritos</li>
                  <li>Monitoramento regular de segurança</li>
                  <li>Backups regulares</li>
                </ul>
              </div>

              {/* Cookies */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  9. Cookies e Tecnologias Similares
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Nosso site utiliza o <strong>Plausible Analytics</strong>, que não utiliza cookies 
                  nem tecnologias de rastreamento invasivas. Não coletamos dados pessoais identificáveis 
                  através de cookies.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Caso implementemos cookies no futuro, atualizaremos esta política e solicitaremos 
                  seu consentimento quando necessário.
                </p>
              </div>

              {/* Alterações */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  10. Alterações nesta Política
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Podemos atualizar esta Política de Privacidade periodicamente. A data da última 
                  atualização está indicada no topo desta página. Recomendamos que você revise esta 
                  política regularmente para se manter informado sobre como protegemos seus dados.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Alterações significativas serão comunicadas através de aviso em nosso site ou por 
                  outros meios apropriados.
                </p>
              </div>

              {/* Contato */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  11. Contato e Encarregado de Dados (DPO)
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Para exercer seus direitos, esclarecer dúvidas ou fazer reclamações sobre o 
                  tratamento de seus dados pessoais, entre em contato conosco:
                </p>
                <div className="bg-mimo-neutral-light rounded-lg p-6 mt-4">
                  <p className="font-satoshi text-mimo-blue leading-relaxed">
                    <strong>{MIMO_COMPANY.legalName}</strong>
                    <br />
                    CNPJ: {MIMO_COMPANY.cnpj}
                    <br />
                    <br />
                    <strong>Endereço:</strong>
                    <br />
                    {MIMO_CONTACT.address.street}
                    <br />
                    {MIMO_CONTACT.address.neighborhood}, {MIMO_CONTACT.address.city} - {MIMO_CONTACT.address.state}
                    <br />
                    CEP: {MIMO_CONTACT.address.zipCode}
                    <br />
                    <br />
                    <strong>Email:</strong>{' '}
                    <a
                      href={`mailto:${MIMO_CONTACT.email}?subject=LGPD - Política de Privacidade`}
                      className="text-mimo-blue hover:text-mimo-brown underline"
                    >
                      {MIMO_CONTACT.email}
                    </a>
                    <br />
                    <strong>Telefone:</strong>{' '}
                    <a
                      href={`tel:${MIMO_CONTACT.phone}`}
                      className="text-mimo-blue hover:text-mimo-brown underline"
                    >
                      {MIMO_CONTACT.phone}
                    </a>
                    <br />
                    <strong>WhatsApp:</strong>{' '}
                    <a
                      href={getWhatsAppContactUrl()}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-mimo-blue hover:text-mimo-brown underline"
                    >
                      Falar no WhatsApp
                    </a>
                  </p>
                </div>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Você também pode apresentar reclamações à Autoridade Nacional de Proteção de Dados 
                  (ANPD) através do site{' '}
                  <a
                    href="https://www.gov.br/anpd"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-mimo-blue hover:text-mimo-brown underline"
                  >
                    www.gov.br/anpd
                  </a>
                  .
                </p>
              </div>

              {/* Lei Aplicável */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  12. Lei Aplicável
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Esta Política de Privacidade é regida pela legislação brasileira, em especial 
                  pela Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018) e pelo Marco 
                  Civil da Internet (Lei nº 12.965/2014).
                </p>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

