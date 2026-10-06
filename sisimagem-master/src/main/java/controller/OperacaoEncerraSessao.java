package controller;

import java.sql.SQLException;

import javax.servlet.http.HttpServletRequest;

import model.DAOTrim;

public class OperacaoEncerraSessao extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// Atualiza objeto Projeto com os parametros do request
	/*	try {			
			DAOTrim daoTrim = new DAOTrim();
			daoTrim.destroy();
		} catch (SQLException e) {
			throw new OperacaoException(e.getMessage());
		} catch (Throwable e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}*/		
		
		request.getSession().invalidate();

		super.setProxPagina("../views/sair.jsp");
	}
}