package controller;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Documento;
import bean.Usuario;
import model.DAOTrim;

public class OperacaoIncluirDocumentoPapem42 extends Operacao {
	Documento documento;
	String mensagem;
	private static Logger log = LoggerFactory.getLogger(OperacaoIncluirDocumentoPapem42.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega o documento do request

		// inserir anexo no documento

		super.setProxPagina("../views/incluirDocumento.jsp");
		HttpSession session = request.getSession();
		Documento documento = (Documento) session.getAttribute("documento");
		log.debug("documento do anexo: " + documento.getIdArquivo());

		Documento documentoPapem42 = new Documento();

		documentoPapem42.setRccontaineruri(documento.getIdArquivo());
		documentoPapem42.setRecordNumberPai(documento.getRecordNumber());
		documentoPapem42.setRecordType("5");
		documentoPapem42.setClassificacao("PAPEM - PAPEM40 - PAPEM42");

		documentoPapem42.setResponsavel(((Usuario) request.getSession().getAttribute("usuario")).getUsuario());
		DAOTrim daoTrim = new DAOTrim();
		daoTrim.obterConexaoDesenv();
		String[] lRecordId = daoTrim.buscaProximoRecordId(Integer.parseInt(documentoPapem42.getRecordType()));
		documentoPapem42.setRecordNumber(lRecordId[0]);
		documentoPapem42.setFullrecordid(lRecordId[1]);
		session.removeAttribute("documento");
		session.setAttribute("documento", documentoPapem42);

	}
}