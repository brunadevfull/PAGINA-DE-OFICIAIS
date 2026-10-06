package controller;

import java.io.File;

import javax.servlet.http.HttpServletRequest;
import javax.swing.JFileChooser;
import javax.swing.filechooser.FileNameExtensionFilter;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Documento;

public class OperacaoLocalizarDocumento extends Operacao {
	Documento documento;
	String mensagem;
	
	private static Logger log = LoggerFactory.getLogger(OperacaoLocalizarDocumento.class);

	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {

		JFileChooser chooser = new JFileChooser();
		log.debug("criou o chooser");
		FileNameExtensionFilter filter = new FileNameExtensionFilter("Arquivos & Imagens", "jpg", "gif", "png", "tif",
				"jpeg", "pdf", "ass", "rtf", "mht", "xls", "log", "docx", "doc", "odt", "htm");

		chooser.setFileFilter(filter);
		chooser.setDialogTitle("Escolhendo o arquivo");
		int returnVal = chooser.showOpenDialog(null);
		log.debug("open dialog");
		if (returnVal == JFileChooser.APPROVE_OPTION) {
			File file = new File(chooser.getSelectedFile().getAbsolutePath());
			log.debug("file", file);
			request.setAttribute("arquivo", file);
			documento = (Documento) request.getSession().getAttribute("documento");
			documento.setNomeArquivoOriginal(file.toString());
		}
		super.setProxPagina("../views/incluirDocumento.jsp");
		// super.setProxPagina("../incluirDocumento.jsp");
	}
}