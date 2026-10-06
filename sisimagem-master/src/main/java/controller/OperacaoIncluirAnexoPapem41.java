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

public class OperacaoIncluirAnexoPapem41 extends Operacao {
	Documento documento;
	String mensagem;
	private static Logger log = LoggerFactory.getLogger(OperacaoIncluirAnexoPapem41.class);
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega o documento do request

		// inserir anexo no documento

		super.setProxPagina("../views/content.jsp");
		HttpSession session = request.getSession();
		Documento documento = (Documento) session.getAttribute("documento");
		log.debug("documento do anexo papem-41: " + documento.getIdArquivo());
		Anexo anexo = new Anexo();
		try {

			DiskFileItemFactory d = new DiskFileItemFactory();
			ServletFileUpload servletFileUpload = new ServletFileUpload(d);
			List<FileItem> multiparts = servletFileUpload.parseRequest(request);

			for (FileItem item : multiparts) {
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
		anexo.setRecordNumber(documento.getRecordNumber() + "-"
				+ Utilitaria.formatNumeroZeroEsquerda(2, documento.listaAnexo.size() + 1));
		anexo.setFullrecordid(documento.getFullrecordid() + "-"
				+ Utilitaria.formatNumeroZeroEsquerda(2, documento.listaAnexo.size() + 1));
		anexo.setRecordType("15");
		anexo.setClassificacao("PAPEM - PAPEM40 - PAPEM41");
		anexo.setResponsavel(((Usuario) request.getSession().getAttribute("usuario")).getUsuario());

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