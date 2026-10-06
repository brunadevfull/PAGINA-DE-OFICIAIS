package controller;

import javax.servlet.http.HttpServletRequest;

import model.DAOTrim;

public class OperacaoExcluirUsuario extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

		String idUsuario = request.getParameter("idUsuario");
		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();

			if (daoTrim.excluirUsuario(idUsuario)) {

				request.setAttribute("mensagem", "Usuário excluído do sistema");
			} else {
				request.setAttribute("mensagem", "Falha na execução da operação");

			}
			super.setProxPagina("../views/topo.jsp");
		}

		catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execução da operação");
			throw new OperacaoException(e.getMessage());
		}
	}
}