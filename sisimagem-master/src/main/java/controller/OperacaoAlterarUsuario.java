package controller;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Usuario;
import model.DAOTrim;
import utilitaria.Whirlpool;

public class OperacaoAlterarUsuario extends Operacao {

	
	private static Logger log = LoggerFactory.getLogger(OperacaoAlterarUsuario.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

		Usuario usuario = (Usuario) request.getSession().getAttribute("usuarioDetalhe");
		log.info("bloqueio: " + request.getParameter("bloqueio"));
		if (!request.getParameter("senha").equals("")) {
			usuario.setSenha(Whirlpool.criptografaSenha(request.getParameter("senha")));
		}
		usuario.setGrupo(request.getParameter("grupo"));
		usuario.setBloqueio(request.getParameter("bloqueio"));
		/*
		 * if(request.getParameter("bloqueio")!= null){
		 * usuario.setBloqueio("B");
		 * 
		 * }else{ usuario.setBloqueio(""); }
		 */

		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();

			if (daoTrim.alterarUsuario(usuario)) {

				request.setAttribute("mensagem", "Usuário " + usuario.getUsuario() + " alterado no sistema");
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