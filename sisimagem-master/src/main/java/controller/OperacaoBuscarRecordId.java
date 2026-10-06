package controller;

import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Documento;
import model.DAOTrim;

public class OperacaoBuscarRecordId extends Operacao {
	Documento documento;
	String mensagem;
	private static Logger log = LoggerFactory.getLogger(OperacaoBuscarRecordId.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega o documento do request

		// inserir anexo no documento

		super.setProxPagina("../views/incluirDocumento.jsp");
		HttpSession session = request.getSession();
		Documento documento = (Documento) session.getAttribute("documento");

		try {

			DiskFileItemFactory d = new DiskFileItemFactory();
			ServletFileUpload servletFileUpload = new ServletFileUpload(d);
			List<FileItem> multiparts = servletFileUpload.parseRequest(request);

			for (FileItem item : multiparts) {

				if (item.isFormField()) {
					log.debug(item.getFieldName() + new String(item.get()));
					Utilitaria.setDocumento(item.getFieldName(), new String(item.get()), documento);
				}
			}

		} catch (Exception ex) {
			request.setAttribute("mensagem", "Falha no Recebimento do Documento");
			log.debug("Falha no Recebimento do Arquivo \n devido a \n" + ex);
			return;
		}

		DAOTrim daoTrim = new DAOTrim();
		daoTrim.obterConexaoDesenv();
		String[] lRecordId = daoTrim.buscaProximoRecordId(Integer.parseInt(documento.getRecordType()));
		documento.setRecordNumber(lRecordId[0]);
		documento.setFullrecordid(lRecordId[1]);

	}
}