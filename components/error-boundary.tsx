'use client'

import { Component, type ReactNode } from 'react'
import { Button } from './ui/button'

type ErrorBoundaryProps = {
  children: ReactNode
  fallback?: ReactNode
}

type ErrorBoundaryState = {
  hasError: boolean
  error: Error | null
}

/**
 * error boundary component para capturar erros em componentes filhos.
 * 
 * útil para prevenir que erros em componentes individuais quebrem toda a página.
 * mostra uma mensagem amigável e opção de tentar novamente.
 */
export class ErrorBoundary extends Component<ErrorBoundaryProps, ErrorBoundaryState> {
  constructor(props: ErrorBoundaryProps) {
    super(props)
    this.state = { hasError: false, error: null }
  }

  static getDerivedStateFromError(error: Error): ErrorBoundaryState {
    return { hasError: true, error }
  }

  componentDidCatch(error: Error, errorInfo: React.ErrorInfo) {
    // Log error para serviço de monitoramento (ex: Sentry)
    if (process.env.NODE_ENV === 'development') {
      console.error('ErrorBoundary capturou um erro:', error, errorInfo)
    }
  }

  handleReset = () => {
    this.setState({ hasError: false, error: null })
  }

  render() {
    if (this.state.hasError) {
      if (this.props.fallback) {
        return this.props.fallback
      }

      return (
        <div className="min-h-[400px] flex items-center justify-center px-4">
          <div className="text-center max-w-md">
            <h2 className="font-bueno text-2xl font-bold text-mimo-brown mb-4">
              Ops! Algo deu errado
            </h2>
            <p className="font-satoshi text-mimo-blue mb-6">
              Desculpe, encontramos um problema ao carregar este conteúdo.
              Tente recarregar a página ou volte mais tarde.
            </p>
            <div className="flex gap-4 justify-center">
              <Button onClick={this.handleReset} variant="primary">
                Tentar novamente
              </Button>
              <Button href="/" variant="secondary">
                Voltar ao início
              </Button>
            </div>
            {process.env.NODE_ENV === 'development' && this.state.error && (
              <details className="mt-6 text-left">
                <summary className="font-satoshi text-sm text-mimo-blue cursor-pointer">
                  Detalhes do erro (dev only)
                </summary>
                <pre className="mt-2 p-4 bg-mimo-neutral-light rounded text-xs overflow-auto">
                  {this.state.error.toString()}
                  {this.state.error.stack}
                </pre>
              </details>
            )}
          </div>
        </div>
      )
    }

    return this.props.children
  }
}

