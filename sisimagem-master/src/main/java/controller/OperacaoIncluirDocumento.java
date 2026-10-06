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

import bean.Documento;
import bean.Usuario;
import java.time.Clock;
import model.DAOTrim;

public class OperacaoIncluirDocumento extends Operacao {
	Documento documento;
	String mensagem;
         
	private static Logger log = LoggerFactory.getLogger(OperacaoIncluirDocumento.class);
	
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
            System.out.println(request);
		// pega os parametros do request
		// documento do session
		// anexo da lista
		// inserir anexo no documento
                 System.out.println("Entrou no Executar !");
		// super.setProxPagina("../views/incluirDocumento.jsp");
		super.setProxPagina("../views/content.jsp");
                System.out.println("Passou aqui !");
		HttpSession session = request.getSession();
                 System.out.println("Passou HttpSession");
		Documento documento = (Documento) session.getAttribute("documento");
                System.out.println(documento);
		if (documento == null) {
                    System.out.println("Entrou no if");
			documento = new Documento();
		}
		log.debug("documento antes de incluir tem respons�el: " + documento.getResponsavel());

		try {
			// pega o usu�rio logado para ser o respons�vel pelo documento
			documento.setResponsavel(((Usuario) request.getSession().getAttribute("usuario")).getUsuario());
			// processo somente se for multipart content

			if (ServletFileUpload.isMultipartContent(request)) {
                           System.out.println(request);
				try {

					DiskFileItemFactory d = new DiskFileItemFactory();
					ServletFileUpload servletFileUpload = new ServletFileUpload(d);
					List<FileItem> multiparts = servletFileUpload.parseRequest(request);
                                        System.out.println(request);                                       
					for (FileItem item : multiparts) {
                                            System.out.println("Entrou no for !");
						if (item.isFormField()) {
                                                    System.out.println("Entrou no if !");
							Utilitaria.setDocumento(item.getFieldName(), new String(item.get()), documento);
						} else if (!item.isFormField()) {
                                                    System.out.println("Entrou no if else !");
							documento.setNomeArquivoCompleto(Utilitaria.montaNomeArquivo(
									request.getSession().getServletContext().getInitParameter("path"), item.getName()));

							File fileNovo = new File(request.getSession().getServletContext().getInitParameter("path")
									+ documento.getNomeArquivoCompleto().replace("+", File.separator));

							item.write(fileNovo);

						}
					}

				} catch (Exception ex) {
					request.setAttribute("mensagem", "Falha no Recebimento do Documento");
					log.debug("Falha no Recebimento do Arquivo \n devido a \n" + ex);
                                        System.out.println(request); 
					return;
				}

			} else {
				request.setAttribute("mensagem", "Desculpe este Servlet s� lida com pedido de upload de arquivos");
				return;
			}

			log.debug("nome do documento no trim: " + documento.getNomeArquivoCompleto());
			DAOTrim daoTrim = new DAOTrim();

		//log.debug("vai conectar no bd");
			daoTrim.obterConexaoDesenv();
			// daoTrim.obterConexao();

			if (daoTrim.inserirDocumento(documento)) {
				mensagem = "Documento " + documento.getRecordNumber() + " inserido no sistema!";
				

			} else {
				mensagem = "Falha na execução da operação";
			}
			session.removeAttribute("documento");
			request.setAttribute("mensagem", mensagem);
			daoTrim.destroy();

		} catch (Exception e) {
			request.setAttribute("mensagem", "Falha na execu��o da opera��o");
			throw new OperacaoException(e.getMessage());
		} catch (Throwable e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}