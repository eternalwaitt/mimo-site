import type { Metadata } from 'next'
import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { MIMO_CONTACT, MIMO_COMPANY } from '@/lib/constants/index'
import Link from 'next/link'

export const metadata: Metadata = {
  title: 'termos de uso',
  description: 'Termos de Uso do site da Mimo - Condições e regras para utilização do site.',
  alternates: {
    canonical: 'https://minhamimo.com.br/termos',
  },
  openGraph: {
    title: 'Termos de Uso | Mimo Salão',
    description: 'Termos de Uso do site da Mimo - Condições e regras para utilização do site.',
    url: 'https://minhamimo.com.br/termos',
    type: 'website',
  },
  robots: {
    index: true,
    follow: true,
  },
}

/**
 * página de termos de uso.
 * 
 * - condições de uso do site
 * - propriedade intelectual
 * - limitações de responsabilidade
 * - lei aplicável (brasileira)
 */
export default function TermosPage() {
  const lastUpdated = '2025-01-29'

  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero */}
        <section className="relative h-[40vh] min-h-[250px] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="font-bueno text-5xl md:text-6xl font-bold text-mimo-brown mb-4">
              Termos de Uso
            </h1>
            <p className="font-satoshi text-lg text-mimo-blue max-w-2xl mx-auto">
              Condições e regras para utilização do site
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
                  1. Aceitação dos Termos
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Ao acessar e utilizar o site <strong>minhamimo.com.br</strong>, você concorda 
                  com os termos e condições estabelecidos neste documento. Se você não concorda 
                  com estes termos, não deve utilizar o site.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Estes Termos de Uso regulam o uso do site da {MIMO_COMPANY.name} ({MIMO_COMPANY.legalName}), 
                  CNPJ {MIMO_COMPANY.cnpj}, localizada em {MIMO_CONTACT.address.street}, 
                  {MIMO_CONTACT.address.neighborhood}, {MIMO_CONTACT.address.city} - {MIMO_CONTACT.address.state}.
                </p>
              </div>

              {/* Uso do Site */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  2. Uso do Site
                </h2>
                <h3 className="font-bueno text-xl font-bold text-mimo-brown mb-3 mt-6">
                  2.1. Uso Permitido
                </h3>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Você pode utilizar o site para:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Obter informações sobre nossos serviços e produtos</li>
                  <li>Entrar em contato conosco para agendamentos e dúvidas</li>
                  <li>Navegar pelo conteúdo disponível</li>
                  <li>Compartilhar links do site em redes sociais</li>
                </ul>

                <h3 className="font-bueno text-xl font-bold text-mimo-brown mb-3 mt-6">
                  2.2. Uso Proibido
                </h3>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  É expressamente proibido:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Utilizar o site para fins ilegais ou não autorizados</li>
                  <li>Tentar acessar áreas restritas ou sistemas do site</li>
                  <li>Interferir no funcionamento do site ou em sua segurança</li>
                  <li>Reproduzir, copiar ou revender conteúdo do site sem autorização</li>
                  <li>Utilizar robôs, scripts ou ferramentas automatizadas para coletar dados do site</li>
                  <li>Transmitir vírus, malware ou qualquer código malicioso</li>
                  <li>Fazer engenharia reversa ou tentar extrair o código-fonte do site</li>
                </ul>
              </div>

              {/* Propriedade Intelectual */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  3. Propriedade Intelectual
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Todo o conteúdo do site, incluindo mas não limitado a textos, imagens, logotipos, 
                  gráficos, ícones, fotografias, vídeos, áudios, software e design, é de propriedade 
                  da {MIMO_COMPANY.name} ou de seus licenciadores e está protegido pelas leis de 
                  propriedade intelectual brasileiras e internacionais.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  A marca "Mimo" e todos os elementos visuais relacionados são marcas registradas 
                  ou marcas de serviço da {MIMO_COMPANY.name}. É proibido o uso não autorizado de 
                  qualquer elemento de propriedade intelectual sem prévia autorização por escrito.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Você pode compartilhar links do site e fazer citações curtas para fins informativos, 
                  desde que seja atribuído o crédito apropriado e fornecido um link para o site original.
                </p>
              </div>

              {/* Conteúdo do Usuário */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  4. Conteúdo Fornecido pelo Usuário
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Se você enviar comentários, sugestões, feedback ou qualquer outro conteúdo através 
                  do site ou de nossos canais de contato, você concede à {MIMO_COMPANY.name} uma 
                  licença não exclusiva, irrevogável e livre de royalties para usar, reproduzir, 
                  modificar, adaptar, publicar e distribuir tal conteúdo para qualquer finalidade, 
                  comercial ou não comercial.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Você declara e garante que possui todos os direitos necessários sobre o conteúdo 
                  enviado e que tal conteúdo não viola direitos de terceiros.
                </p>
              </div>

              {/* Links Externos */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  5. Links para Sites de Terceiros
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  O site pode conter links para sites de terceiros, incluindo redes sociais (Instagram, 
                  Facebook) e serviços externos (WhatsApp, Google Maps). Estes links são fornecidos 
                  apenas para sua conveniência.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  A {MIMO_COMPANY.name} não tem controle sobre o conteúdo ou políticas de privacidade 
                  desses sites externos e não se responsabiliza por eles. Recomendamos que você revise 
                  os termos de uso e políticas de privacidade de qualquer site que visite.
                </p>
              </div>

              {/* Disponibilidade */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  6. Disponibilidade do Site
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Nos esforçamos para manter o site disponível 24 horas por dia, 7 dias por semana. 
                  No entanto, não garantimos que o site estará sempre acessível ou livre de erros.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Podemos interromper, suspender ou modificar o site a qualquer momento, com ou sem 
                  aviso prévio, para manutenção, atualizações ou por qualquer outro motivo. Não nos 
                  responsabilizamos por qualquer perda ou dano resultante da indisponibilidade do site.
                </p>
              </div>

              {/* Limitação de Responsabilidade */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  7. Limitação de Responsabilidade
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  O site é fornecido "como está" e "conforme disponível", sem garantias de qualquer 
                  tipo, expressas ou implícitas.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  A {MIMO_COMPANY.name} não se responsabiliza por:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Interrupções, erros ou falhas no funcionamento do site</li>
                  <li>Perda de dados ou informações</li>
                  <li>Danos diretos, indiretos, incidentais ou consequenciais resultantes do uso ou 
                      impossibilidade de uso do site</li>
                  <li>Ações de terceiros, incluindo hackers ou malware</li>
                  <li>Conteúdo de sites de terceiros vinculados</li>
                </ul>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Em nenhuma circunstância nossa responsabilidade total excederá o valor pago por você 
                  pelo uso do site (que atualmente é zero, pois o site é gratuito).
                </p>
              </div>

              {/* Indenização */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  8. Indenização
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Você concorda em indenizar e isentar a {MIMO_COMPANY.name}, seus diretores, 
                  funcionários e parceiros de qualquer reclamação, dano, perda, responsabilidade 
                  e despesa (incluindo honorários advocatícios) decorrentes de:
                </p>
                <ul className="font-satoshi text-mimo-blue leading-relaxed list-disc list-inside space-y-2 mt-3 ml-4">
                  <li>Seu uso do site</li>
                  <li>Violação destes Termos de Uso</li>
                  <li>Violacao de direitos de terceiros</li>
                  <li>Conteúdo que você fornecer</li>
                </ul>
              </div>

              {/* Privacidade */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  9. Privacidade
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  O tratamento de seus dados pessoais é regido por nossa{' '}
                  <Link href="/privacidade" className="text-mimo-blue hover:text-mimo-brown underline">
                    Política de Privacidade
                  </Link>
                  , que faz parte integrante destes Termos de Uso. Ao utilizar o site, você concorda 
                  com a coleta e uso de informações conforme descrito na Política de Privacidade.
                </p>
              </div>

              {/* Modificações */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  10. Modificações dos Termos
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Reservamo-nos o direito de modificar estes Termos de Uso a qualquer momento. 
                  Alterações significativas serão comunicadas através de aviso em nosso site.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  O uso continuado do site após a publicação de alterações constitui sua aceitação 
                  dos novos termos. Se você não concordar com as alterações, deve cessar o uso do site.
                </p>
              </div>

              {/* Rescisão */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  11. Rescisão
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Podemos, a nosso exclusivo critério, encerrar ou suspender seu acesso ao site, 
                  com ou sem aviso prévio, por qualquer motivo, incluindo violação destes Termos de Uso.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Você também pode cessar o uso do site a qualquer momento. As disposições que, 
                  por sua natureza, devem sobreviver à rescisão continuarão em vigor.
                </p>
              </div>

              {/* Lei Aplicável */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  12. Lei Aplicável e Foro
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Estes Termos de Uso são regidos pelas leis da República Federativa do Brasil, 
                  independentemente de conflitos de leis.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Qualquer disputa relacionada a estes termos será resolvida exclusivamente nos 
                  tribunais competentes da cidade de {MIMO_CONTACT.address.city}, estado de {MIMO_CONTACT.address.state}, 
                  Brasil.
                </p>
              </div>

              {/* Disposições Gerais */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  13. Disposições Gerais
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Se qualquer disposição destes Termos de Uso for considerada inválida ou inexequível, 
                  as demais disposições permanecerão em pleno vigor e efeito.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  A falha em exercer qualquer direito previsto nestes termos não constitui renúncia 
                  a tal direito.
                </p>
                <p className="font-satoshi text-mimo-blue leading-relaxed mt-4">
                  Estes Termos de Uso constituem o acordo completo entre você e a {MIMO_COMPANY.name} 
                  em relação ao uso do site.
                </p>
              </div>

              {/* Contato */}
              <div>
                <h2 className="font-bueno text-3xl font-bold text-mimo-brown mb-4">
                  14. Contato
                </h2>
                <p className="font-satoshi text-mimo-blue leading-relaxed">
                  Se você tiver dúvidas sobre estes Termos de Uso, entre em contato conosco:
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
                      href={`mailto:${MIMO_CONTACT.email}?subject=Termos de Uso`}
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
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  )
}

