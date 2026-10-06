package controller;

import java.util.List;

import javax.servlet.http.HttpServletRequest;

import bean.Usuario;
import model.DAOTrim;

public class OperacaoCadastroUsuario extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();

			// busca do request a lista de usuario
			List<Usuario> lista = null;
			lista = daoTrim.listaUsuario();
			request.setAttribute("listaUsuario", lista);
			// List<Grupo> grupo = null;
			lista = daoTrim.listaUsuario();

		} catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execu��o da opera��o");
			throw new OperacaoException(e.getMessage());
		}

		super.setProxPagina("../views/cadastroUsuario.jsp");
	}

}