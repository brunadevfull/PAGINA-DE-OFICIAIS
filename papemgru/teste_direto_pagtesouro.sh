#!/usr/bin/env bash
set -euo pipefail

# Proxy
export http_proxy="http://11111062:BruN%402025GlowUp@proxy-1dn.mb:6060"
export https_proxy="http://11111062:BruN%402025GlowUp@proxy-1dn.mb:6060"

# Token que você já usa hoje (DGOM)
TOKEN="${1:-}"
if [[ -z "$TOKEN" ]]; then
  echo "Uso: ./teste_direto_pagtesouro.sh <TOKEN>"
  exit 1
fi

# Payload mínimo
cat > /tmp/payload_pagtesouro.json <<'JSON'
{
  "cat": "PAPEM",
  "codigoServico": 11859,
  "vencimento": "2026-12-31",
  "competencia": "202604",
  "nomeContribuinte": "TESTE INTEGRACAO",
  "cnpjCpf": "12345678901",
  "valorDescontos": 0,
  "valorOutrasDeducoes": 0,
  "valorMulta": 0,
  "valorJuros": 0,
  "valorOutrosAcrescimos": 0,
  "valorPrincipal": 10.5,
  "nomeUG": "TESTE",
  "cod_om": "000",
  "cat_servico": "PAPEM",
  "codRubrica": "n/a",
  "nomeRubrica": "n/a",
  "motivo": "Teste direto sem DGOM",
  "tributavel": 1,
  "nomeOC": "TESTE",
  "cod_oc": "000",
  "valorBrutoExercAnt": 10.5,
  "codSiapeNip": "123456",
  "modoNavegacao": "2",
  "tema": "tema-light",
  "NatDev": "Pagamento de Pessoal"
}
JSON

echo "== Teste DIRETO STN =="
curl -sS -D /tmp/stn_headers.txt -o /tmp/stn_body.json \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  --data @/tmp/payload_pagtesouro.json \
  https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento || true

echo "--- HEADERS STN ---"
head -n 30 /tmp/stn_headers.txt || true
echo "--- BODY STN ---"
cat /tmp/stn_body.json || true
echo

echo "== Teste DGOM =="
curl -sS -D /tmp/dgom_headers.txt -o /tmp/dgom_body.json \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  --data @/tmp/payload_pagtesouro.json \
  https://pagtesouro.dgom.mb:3000/handle || true

echo "--- HEADERS DGOM ---"
head -n 30 /tmp/dgom_headers.txt || true
echo "--- BODY DGOM ---"
cat /tmp/dgom_body.json || true
