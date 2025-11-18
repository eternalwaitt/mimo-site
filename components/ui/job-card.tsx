import Link from 'next/link'
import { cn } from '@/lib/utils'
import type { JobOpening } from '@/lib/types'

type JobCardProps = {
  job: JobOpening
  className?: string
}

/**
 * job card component - para vagas em aberto na página Trabalhe Aqui.
 * 
 * - título, área, descrição
 * - lista de requisitos (mostra 3 primeiros + contador)
 * - link para página individual da vaga
 * - hover com scale e shadow
 */
export function JobCard({ job, className }: JobCardProps) {
  return (
    <Link
      href={`/trabalhe-aqui/${job.slug}`}
      className={cn(
        'block rounded-xl bg-white p-6 shadow-md transition-all duration-400 hover:shadow-lg hover:scale-[1.02] border border-mimo-neutral-medium',
        className
      )}
    >
      <div className="mb-4">
        <span className="inline-block rounded-full bg-mimo-gold px-3 py-1 font-satoshi text-xs font-medium text-mimo-brown mb-2">
          {job.area}
        </span>
        <h3 className="font-bueno text-2xl font-bold text-mimo-brown mb-2">
          {job.title}
        </h3>
        <p className="font-satoshi text-mimo-blue leading-relaxed">
          {job.description}
        </p>
      </div>

      <div className="mb-4">
        <h4 className="font-bueno text-sm font-bold text-mimo-brown mb-2 uppercase tracking-wide">
          Requisitos
        </h4>
        <ul className="space-y-1">
          {job.requirements.slice(0, 3).map((req, index) => (
            <li key={index} className="font-satoshi text-sm text-mimo-blue flex items-start">
              <span className="text-mimo-gold mr-2">•</span>
              <span>{req}</span>
            </li>
          ))}
          {job.requirements.length > 3 && (
            <li className="font-satoshi text-sm text-mimo-brown font-medium">
              + {job.requirements.length - 3} mais requisitos
            </li>
          )}
        </ul>
      </div>

      <div className="text-center mt-6">
        <span className="font-satoshi text-mimo-brown font-medium hover:text-mimo-gold transition-colors">
          Ver detalhes da vaga →
        </span>
      </div>
    </Link>
  )
}

