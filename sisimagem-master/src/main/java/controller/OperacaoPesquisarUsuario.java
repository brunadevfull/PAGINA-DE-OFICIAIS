package controller;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Usuario;
import model.DAOTrim;

public class OperacaoPesquisarUsuario extends Operacao {

	private static Logger log = LoggerFactory.getLogger(OperacaoPesquisarUsuario.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

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
		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();

			List<Usuario> lista = daoTrim.listaUsuario(mCamposPesquisa);
			request.getSession().setAttribute("listaUsuario", lista);

			super.setProxPagina("../views/cadastroUsuario.jsp");
		}

		catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execução da operação");
			throw new OperacaoException(e.getMessage());
		}
	}
}