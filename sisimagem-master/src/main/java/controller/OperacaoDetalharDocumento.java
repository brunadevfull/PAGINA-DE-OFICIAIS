package controller;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Documento;
import model.DAOTrim;

public class OperacaoDetalharDocumento extends Operacao {

	private static Logger log = LoggerFactory.getLogger(OperacaoDetalharDocumento.class);
	
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request

		String idArquivo = request.getParameter("idArquivo");

		try {
			DAOTrim daoTrim = new DAOTrim();
			// daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();
			log.debug("VAI LISTAR DOCUMENTO POR ID");
			// busca do request a lista de documento
			Documento documento = daoTrim.listaDocumentoporId(idArquivo);
			log.debug("CONSEGUIU LISTA DOCUMENTO POR ID");
                        
                        daoTrim.obterConexaoDesenv();
			documento = daoTrim.addAnexo(documento);
			log.info("CONSEGUIU ADD ANEXO");
			if (documento == null) {
				log.debug("Documento is null");
				request.setAttribute("mensagem", "N�o foi poss�vel realizar a opera��o");
			}
			request.getSession().setAttribute("documento", documento);
		}

		catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execu��o da opera��o");
			throw new OperacaoException(e.getMessage());
		}

		super.setProxPagina("../views/detalhaDocumento2.jsp");
	}

}