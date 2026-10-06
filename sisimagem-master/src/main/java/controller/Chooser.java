package controller;

import java.io.File;
import java.io.IOException;

import javax.swing.JFileChooser;
import javax.swing.filechooser.FileNameExtensionFilter;

public class Chooser {

	public void abrirPaint(String nomeArquivo) {

		try {
			String comando = "mspaint.exe " + nomeArquivo;
			Process processo = Runtime.getRuntime().exec(comando);

		} catch (IOException e) {
			System.out.println("Erro: " + e.getMessage() + "\n" + e.getCause());
		}
	}

	public void abrirDigitalizador(String nomeArquivo) {

		try {
			String comando = "esfiledl.exe " + nomeArquivo;
			Process processo = Runtime.getRuntime().exec(comando);
		} catch (IOException e) {
			System.out.println("Erro: " + e.getMessage() + "\n" + e.getCause());
		}
	}

	public void abrirPaint() {

		try {
			String comando = "mspaint.exe";
			// String comando = "cmd.exe /c start";

			Process processo = Runtime.getRuntime().exec(comando);
		} catch (IOException e) {
			System.out.println("Erro: " + e.getMessage() + "\n" + e.getCause());
		}
	}

	public void cria(String flag) {

		JFileChooser chooser = new JFileChooser();

		// Define o diret�rio inicial
		// File diretorio = new File("c:\\trim\\001\\201501");
		// File file = new File("c:\\trim\\001\\201501\\teste.tif");
		// chooser.setCurrentDirectory(diretorio);

		FileNameExtensionFilter filter = new FileNameExtensionFilter("Arquivos & Imagens", "jpg", "gif", "png", "tif",
				"jpeg", "pdf", "ass", "rtf", "mht", "xls", "log", "docx", "doc", "odt", "htm");

		chooser.setFileFilter(filter);
		// chooser.setSelectedFile(file);

		chooser.setDialogTitle("Escolhendo o arquivo");
		int returnVal = chooser.showOpenDialog(null);

		if (returnVal == JFileChooser.APPROVE_OPTION) {
			File file = new File(chooser.getSelectedFile().getAbsolutePath());
			System.out.println(file);
		}
	}

	public String cria() {

		JFileChooser chooser = new JFileChooser();

		// Define o diret�rio inicial
		File diretorio = new File("c:\\trim");
		chooser.setCurrentDirectory(diretorio);

		FileNameExtensionFilter filter = new FileNameExtensionFilter("Arquivos & Imagens", "jpg", "gif", "png", "tif",
				"jpeg", "pdf", "ass", "rtf", "mht", "xls", "log", "docx", "doc", "odt", "htm");

		chooser.setFileFilter(filter);
		chooser.setDialogTitle("Escolhendo o arquivo");
		int returnVal = chooser.showOpenDialog(null);

		if (returnVal == JFileChooser.APPROVE_OPTION) {

			return chooser.getSelectedFile().getAbsolutePath();
		} else {
			return null;
		}
	}

	private static void setFileFilter(FileNameExtensionFilter filter) {
		// TODO Auto-generated method stub
	}
}