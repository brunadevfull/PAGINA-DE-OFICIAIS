# Recomendações de Stack e Arquitetura (rascunho)

Escrevi este documento para concentrar as recomendações de stack, arquitetura e boas práticas enquanto a decisão final não é tomada. Vou ajustando conforme os comparativos avançarem.

---

## ✅ Stack sugerida (rascunho)

### Backend
- **Runtime**: Node.js 20 LTS
- **Framework**: Express.js ou Fastify
- **Linguagem**: TypeScript 5.x
- **ORM**: Prisma 5.x
- **Banco de Dados**: PostgreSQL 15

### Frontend
- **Framework**: Next.js 14
- **Linguagem**: TypeScript 5.x
- **UI**: Tailwind CSS + shadcn/ui
- **Estado**: React Query + Zustand

### Infraestrutura
- **SO**: Ubuntu Server 22.04 LTS
- **Proxy**: Apache (reverse proxy)
- **Process Manager**: PM2
- **Cache**: Redis 7

---

## 🧱 Recomendações de arquitetura

- **Padrões possíveis**: MVC, Hexagonal, Clean Architecture (avaliar qual encaixa melhor).
- **Manter um front controller** (ou equivalente) para preservar o fluxo de comandos do sistema atual.
- **Separar camadas** (controller/handler, serviço, domínio, persistência).
- **DTOs claros** entre camadas para reduzir acoplamento.
- **Autenticação/Autorização isoladas** (módulo próprio + middleware).
- **Uploads e arquivos** com serviço dedicado (evitar lógica espalhada em controller).
- **Logs estruturados** desde o início (nível, contexto, requestId).

---

## 🛡️ Boas práticas técnicas

- **Queries parametrizadas** sempre.
- **Validações no backend** mesmo que exista validação no frontend.
- **Testes básicos**: login, pesquisa, inclusão e upload.
- **Migração de banco** com scripts reproduzíveis e validação de contagens.

---

## 📌 Observações pessoais

- Ainda estou comparando alternativas, então a stack acima é um ponto de partida.
- Vou ajustar este documento conforme decidir o caminho final.
