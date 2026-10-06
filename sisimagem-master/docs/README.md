# Documentação do SisImagem

Sistema de Gestão de Documentos PAPEM-41/42 da PAPEM (instituição governamental). Este material foi escrito por mim para registrar o que existe hoje e orientar a manutenção. Sou a única programadora e conto com duas pessoas de apoio na operação.

---

## 📋 Estrutura da Documentação

```
docs/
├── README.md (este arquivo)
├── 01-analise-sistema-atual/
│   ├── gaps-documentacao-migracao.md
│   ├── guia-documentacao-sisimagem.md
│   └── reverse-engineering-report.md
├── 02-planejamento-migracao/
│   ├── comparativo-tecnologias-detalhado.md
│   ├── plano-acao-documentacao.md
│   └── recomendacoes-stack-arquitetura.md
└── 03-migracao-banco-dados/
    ├── README.md
    └── plano-migracao-oracle-postgresql.md
```

---

## ✅ Documentos Essenciais

- [Visão Geral](../SYSTEM_OVERVIEW.md)
- [Arquitetura Atual](./01-analise-sistema-atual/reverse-engineering-report.md)
- [Guia de Documentação](./01-analise-sistema-atual/guia-documentacao-sisimagem.md)
- [Recomendações de Stack e Arquitetura](./02-planejamento-migracao/recomendacoes-stack-arquitetura.md)

**Resumo rápido**: Sistema Java 8 com Servlets/JSP, Oracle/TRIM, jQuery 1.4.2.

---

## 📌 Documentação de Apoio (usar só quando necessário)

- [Análise de Gaps](./01-analise-sistema-atual/gaps-documentacao-migracao.md)
- [Plano de Ação](./02-planejamento-migracao/plano-acao-documentacao.md)
- [Comparativo de Tecnologias](./02-planejamento-migracao/comparativo-tecnologias-detalhado.md)
- [Plano Oracle → PostgreSQL](./03-migracao-banco-dados/plano-migracao-oracle-postgresql.md)

---

## ✅ Checklist: A documentação atual está suficiente?

- [ ] Fluxos principais estão descritos (login, pesquisa, inclusão, anexos).
- [ ] Tabelas TRIM citadas nas operações estão mapeadas.
- [ ] Regras de validação e bloqueio estão registradas.
- [ ] Campos obrigatórios por tipo de documento estão descritos.
- [ ] Integrações externas (scanner, arquivos, TRIM) estão claras.

Se algum item acima ficar pendente, sigo o checklist abaixo.

---

## 🧭 Checklist de levantamento (se faltar informação)

- [ ] Revisar `ServletControlador.init()` e mapear `cmd` → `Operacao`.
- [ ] Listar campos e validações de cada JSP usada no fluxo principal.
- [ ] Extrair regras de negócio de `DAOTrim` e `Operacao*`.
- [ ] Mapear tabelas TRIM tocadas por cada operação.
- [ ] Registrar regras de senha (login + cadastro).
- [ ] Documentar integrações externas e caminhos de arquivo.
### Técnicos
- ✅ **Performance superior** para I/O intensivo
- ✅ **Segurança moderna** (elimina SQL Injection)
- ✅ **Interface responsiva** e acessível
- ✅ **Manutenibilidade** muito melhor
- ✅ **Type safety** com TypeScript
- ✅ **Escalabilidade** horizontal

---

## 🧱 Checklist de decisões técnicas (stack ainda em avaliação)

- [ ] Definir critérios (segurança, manutenção, curva de aprendizado, integrações).
- [ ] Comparar opções de backend, frontend e banco com base nos critérios.
- [ ] Validar impacto de migração de Oracle → outro banco (queries e dados).
- [ ] Decidir se reimplementação será incremental ou big-bang.
- [ ] Registrar decisão final e justificativas.

---

## 🧩 Boas práticas para escolher arquitetura

- [ ] Manter o front controller (ou equivalente) para preservar o fluxo atual.
- [ ] Separar camada de domínio, persistência e transporte (controllers/handlers).
- [ ] Tratar autenticação e autorização como módulo isolado.
- [ ] Preferir contratos claros (DTOs) entre camadas.
- [ ] Priorizar migração do banco com testes de dados repetíveis.

---

## 📖 Como uso esta documentação

1. Leio os documentos essenciais para entender o fluxo atual.
2. Se faltar algo, sigo o checklist de levantamento.
3. Enquanto a stack estiver em avaliação, mantenho decisões e critérios no checklist técnico.
## 🎯 Próximos Passos

### Imediatos
1. ✅ Aprovar stack tecnológica (Node.js + PostgreSQL)
2. ⏳ Obter acesso ao Oracle (read-only)
3. ⏳ Iniciar análise detalhada do banco

### Curto Prazo
5. ⏳ Extração completa do schema Oracle
6. ⏳ Setup de ambientes (dev, staging, prod)
7. ⏳ Início da migração de banco

### Médio Prazo
8. ⏳ Desenvolvimento do backend
9. ⏳ Desenvolvimento do frontend
10. ⏳ Testes e validação

---

## 📖 Como Uso Esta Documentação

1. Leio este README para manter a visão geral e o estado dos documentos.
2. Consulto o [Guia de Documentação](./01-analise-sistema-atual/guia-documentacao-sisimagem.md) sempre que preciso mapear fluxos ou regras.
3. Acesso o [Plano de Migração Oracle → PostgreSQL](./03-migracao-banco-dados/plano-migracao-oracle-postgresql.md) quando estou avaliando a migração do banco.
4. Uso o [Comparativo de Tecnologias](./02-planejamento-migracao/comparativo-tecnologias-detalhado.md) apenas como referência técnica.

---

## 🔗 Links Importantes

### Documentação Técnica
- [PostgreSQL 15](https://www.postgresql.org/docs/15/)
- [Node.js 20](https://nodejs.org/docs/latest-v20.x/api/)
- [Next.js 14](https://nextjs.org/docs)
- [Prisma ORM](https://www.prisma.io/docs)
- [TypeScript](https://www.typescriptlang.org/docs/)

### Ferramentas de Migração
- [ora2pg](https://ora2pg.darold.net/) - Migração Oracle → PostgreSQL
- [pgloader](https://pgloader.io/) - Carregamento de dados
- [PostgreSQL Migration Tools](https://wiki.postgresql.org/wiki/Converting_from_other_Databases_to_PostgreSQL)

---

## 📞 Suporte

Para dúvidas sobre a documentação:
- **Repositório**: `/home/bruna/sisimagem`
- **Documentação**: `/home/bruna/sisimagem/docs`

---

**Última atualização**: 2026-01-02
