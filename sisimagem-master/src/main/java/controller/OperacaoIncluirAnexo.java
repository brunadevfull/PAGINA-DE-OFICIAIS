package controller;

import java.io.File;
import java.util.List;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;

import bean.Anexo;
import bean.Documento;
import bean.Usuario;
import model.DAOTrim;

public class OperacaoIncluirAnexo extends Operacao {
	Documento documento;
	String mensagem;

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega o documento do request

		// inserir anexo no documento

		super.setProxPagina("../views/content.jsp");

		Documento documento = (Documento) request.getAttribute("documento");
		Anexo anexo = new Anexo();

		try {
			// pega o usu�rio logado para ser o respons�vel pelo documento
			anexo.setResponsavel(((Usuario) request.getSession().getAttribute("usuario")).getUsuario());
			anexo.setIdDocumento(documento.getIdArquivo());
			anexo.setClassificacao(documento.getClassificacao());

			// Gera nome do arquivo para Documento no formato da Aplica��o e
			// copia para aplica��o

			// processo somente se for multipart content

			if (ServletFileUpload.isMultipartContent(request)) {
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
					request.setAttribute("mensagem", "Falha no Recebimento do Documento");
					System.out.println("Falha no Recebimento do Arquivo \n devido a \n" + ex);
					return;
				}

			} else {
				request.setAttribute("mensagem", "Desculpe este Servlet s� lida com pedido de upload de arquivos");
				return;
			}

			System.out.println("nome do documento no trim: " + documento.getNomeArquivoCompleto());
			DAOTrim daoTrim = new DAOTrim();

			System.out.println("vai conectar no bd");
			daoTrim.obterConexaoDesenv();
			// daoTrim.obterConexao();

			if (daoTrim.inserirAnexo(anexo)) {
				mensagem = "Anexo " + documento.getRecordNumber() + " inserido no sistema!";

			} else {
				mensagem = "Falha na execu��o da opera��o";
			}

			request.setAttribute("mensagem", mensagem);

		} catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execu��o da opera��o");
			throw new OperacaoException(e.getMessage());
		}
	}
}