package controller;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Documento;
import model.DAOTrim;

public class OperacaoPesquisarDocumento extends Operacao {

	private static Logger log = LoggerFactory.getLogger(OperacaoPesquisarDocumento.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request
                System.out.println("Entrou no Executar !");
		Map m = request.getParameterMap();
		Map<String, String> mCamposPesquisa = new HashMap<String, String>();
		Set s = m.entrySet();
		Iterator it = s.iterator();

		while (it.hasNext()) {

			Map.Entry<String, String[]> entry = (Map.Entry<String, String[]>) it.next();

			String key = entry.getKey();
			String[] value = entry.getValue();

			if (key.substring(0, 1).equals("r")) {

				mCamposPesquisa.put(key.substring(1), value[0].toString());

			}
			log.debug(key + ": " + value[0].toString());

			log.debug("-------------------");
		}

		// super.setProxPagina("/exibiDocumento.jsp");
		super.setProxPagina("../views/exibiDocumento.jsp");
		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();
			/*
			 * log.debug(lParametro.size()); List<Integer> listaId =
			 * daoTrim.buscaIdDocumento(lParametro); if( (listaId == null) ||
			 * listaId.isEmpty()){
			 * log.debug("Lista de id retornou vazia"); return; }
			 * 
			 */
			List<Integer> listaId = null;

			if (!m.isEmpty()) {
				listaId = daoTrim.buscaIdDocumento(m);
			}
			if (((listaId == null) || listaId.isEmpty()) && (mCamposPesquisa.isEmpty())) {
				log.debug("Lista de id retornou vazia");
				request.setAttribute("listaDocumento", null);
				return;
			}

			// usu�rios externos (SASM) somente podem visualiza documentos do
			// papem-41
			if (((String) request.getSession().getAttribute("grupo")).equals("SASM")) {
				mCamposPesquisa.put("perfil", "PAPEM41");

			}
			log.debug("vai listar documentos");
			List<Documento> lista = null;			
			daoTrim.obterConexaoDesenv();
			lista = daoTrim.listaDocumento(listaId, mCamposPesquisa);
			log.debug("pegou lista de documentos");
			// List<Documento> lista = daoTrim.listaDocumento(listaId,
			// mCamposPesquisa);
			request.setAttribute("listaDocumento", lista);
		} catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execução da operação");
			throw new OperacaoException(e.getMessage());
		}
	}
}