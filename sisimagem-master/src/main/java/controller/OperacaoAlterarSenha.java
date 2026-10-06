package controller;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Usuario;
import model.DAOTrim;
import utilitaria.Whirlpool;

public class OperacaoAlterarSenha extends Operacao {

	
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

		Usuario usuario = (Usuario) request.getSession().getAttribute("usuario");

		if (!request.getParameter("senha").equals("")) {
			usuario.setSenha(Whirlpool.criptografaSenha(request.getParameter("senha")));
		}

		try {
			DAOTrim daoTrim = new DAOTrim();

			daoTrim.obterConexaoDesenv();

			if (daoTrim.alterarUsuario(usuario)) {

				request.setAttribute("mensagem", "Senha alterada com sucesso!");
			} else {
				request.setAttribute("mensagem", "Falha na execucao da operacao");

			}
			super.setProxPagina("../views/index.jsp");
		}

		catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execucao da operacao");
			throw new OperacaoException(e.getMessage());
		}
	}
}