package controller;

import javax.servlet.http.HttpServletRequest;

import bean.Usuario;
import model.DAOTrim;
import utilitaria.Whirlpool;

public class OperacaoIncluirUsuario extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

		Usuario usuario = new Usuario();
		usuario.setUsuario(request.getParameter("rlcname"));
		usuario.setSenha(Whirlpool.criptografaSenha(request.getParameter("senha")));
		usuario.setGrupo(request.getParameter("rjgname"));
		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();
			// verifica se existe
			if (daoTrim.verificaExisteUsuario(usuario.getUsuario())) {
				request.setAttribute("mensagem", "Usuário " + usuario.getUsuario() + " já cadastrado no sistema");
				super.setProxPagina("../views/cadastroUsuario.jsp");
				return;
			}
			
			daoTrim.obterConexaoDesenv();
			
			if (daoTrim.inserirUsuario(usuario)) {

				request.setAttribute("mensagem",
						"Usuário " + usuario.getUsuario() + " inclu�do no sistema com sucesso.");
			} else {
				request.setAttribute("mensagem", "Falha na execução da operação");

			}
			super.setProxPagina("../views/content.jsp");
		}

		catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execução da operação");
			throw new OperacaoException(e.getMessage());

		}
	}
}