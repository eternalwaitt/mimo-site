import { Header } from '@/components/layout/header'
import { Footer } from '@/components/layout/footer'
import { Button } from '@/components/ui/button'
import { getWhatsAppBookingUrl } from '@/lib/constants/index'

/**
 * página 404 personalizada - página não encontrada.
 * 
 * - mensagem criativa e engraçada relacionada a beleza/salão
 * - design consistente com o resto do site
 * - botões para voltar ou agendar
 */
export default function NotFound() {
  const whatsappUrl = getWhatsAppBookingUrl()

  return (
    <>
      <Header />
      <main id="main-content" className="pt-20">
        {/* Hero com mensagem criativa */}
        <section className="min-h-[70vh] flex items-center justify-center bg-mimo-neutral-light">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div className="max-w-2xl mx-auto">
              {/* Emoji ou ícone decorativo */}
              <div className="mb-6 text-6xl md:text-8xl">👑</div>
              
              {/* Título principal */}
              <h1 className="font-bueno text-4xl md:text-6xl font-bold text-mimo-brown mb-4">
                Deixou a coroa cair, princesa?
              </h1>
              
              {/* Subtítulo */}
              <p className="font-satoshi text-xl md:text-2xl text-mimo-blue mb-2">
                Essa página não existe
              </p>
              
              {/* Mensagem adicional */}
              <p className="font-satoshi text-lg text-mimo-blue/80 mb-8">
                Mas não se preocupe, vamos te ajudar a encontrar o que você precisa!
              </p>

              {/* Botões de ação */}
              <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <Button href="/" variant="primary">
                  Voltar para casa
                </Button>
                <Button href={whatsappUrl} variant="whatsapp" external>
                  Agendar no WhatsApp
                </Button>
              </div>

              {/* Links úteis */}
              <div className="mt-12 pt-8 border-t border-mimo-neutral-medium">
                <p className="font-satoshi text-sm text-mimo-blue mb-4">
                  Ou explore nossos serviços:
                </p>
                <div className="flex flex-wrap gap-3 justify-center">
                  <Button href="/servicos" variant="ghost">
                    Serviços
                  </Button>
                  <Button href="/galeria" variant="ghost">
                    Galeria
                  </Button>
                  <Button href="/sobre" variant="ghost">
                    Sobre
                  </Button>
                  <Button href="/mimo-hub" variant="ghost">
                    Mimo Hub
                  </Button>
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

