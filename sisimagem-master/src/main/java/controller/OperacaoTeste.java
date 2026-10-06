package controller;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import bean.Documento;

public class OperacaoTeste extends Operacao {

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// Atualiza objeto Projeto com os parametros do request
		HttpSession session = request.getSession();
		String classificacao = (String) request.getParameter("classificacao");
		System.out.println("Classifica��o request: " + classificacao);
		Documento documento = (Documento) session.getAttribute("documento");
		System.out.println("Documento: " + documento);
		System.out.println("Classifica��o documento session: " + documento.getClassificacao());
		documento.setClassificacao(classificacao);
		System.out.println("Classifica��o documento controller: " + documento.getClassificacao());
		super.setProxPagina("../teste.jsp");
	}

}