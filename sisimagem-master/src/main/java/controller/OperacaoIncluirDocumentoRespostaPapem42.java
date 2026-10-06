package controller;

import java.io.File;
import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Anexo;
import bean.Documento;
import bean.Usuario;
import model.DAOTrim;

public class OperacaoIncluirDocumentoRespostaPapem42 extends Operacao {
	Documento documento;
	String mensagem;
	private static Logger log = LoggerFactory.getLogger(OperacaoIncluirDocumentoRespostaPapem42.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega o documento do request

		// inserir anexo no documento

		super.setProxPagina("../views/content.jsp");
		HttpSession session = request.getSession();
		Documento documento = (Documento) session.getAttribute("documento");
		String titulo = null;
		log.debug("documento do anexo: " + documento.getIdArquivo());

		Anexo anexo = new Anexo();
		try {

			DiskFileItemFactory d = new DiskFileItemFactory();
			ServletFileUpload servletFileUpload = new ServletFileUpload(d);
			List<FileItem> multiparts = servletFileUpload.parseRequest(request);

			for (FileItem item : multiparts) {
				if (item.isFormField() && item.getFieldName().equals("titulo")) {
					titulo = new String(item.get());
					log.debug("titulo: " + titulo);

				}

				if (!item.isFormField()) {
					anexo.setNomeArquivoOriginal(item.getName());
					anexo.setNomeArquivoCompleto(Utilitaria.montaNomeArquivo(
							request.getSession().getServletContext().getInitParameter("path"), item.getName()));

					File fileNovo = new File(request.getSession().getServletContext().getInitParameter("path")
							+ anexo.getNomeArquivoCompleto().replace("+", File.separator));

					item.write(fileNovo);

				}
			}

		} catch (Exception ex) {
			request.setAttribute("mensagem", "Falha no Recebimento do Anexo");
			log.debug("Falha no Recebimento do Arquivo \n devido a \n" + ex);
			return;
		}
		anexo.setIdDocumento(documento.getIdArquivo());
		anexo.setRecordType("8");
		anexo.setClassificacao("PAPEM - PAPEM40 - PAPEM42");

		anexo.setResponsavel(((Usuario) request.getSession().getAttribute("usuario")).getUsuario());
		anexo.setTitle(titulo);
		DAOTrim daoTrim = new DAOTrim();
		daoTrim.obterConexaoDesenv();

		if (daoTrim.inserirAnexo(anexo)) {
			mensagem = "Anexo " + anexo.getRecordNumber() + " inserido no sistema!";

		} else {
			mensagem = "Falha na execu��o da opera��o";
		}
		session.removeAttribute("documento");
		request.setAttribute("mensagem", mensagem);
	}
}