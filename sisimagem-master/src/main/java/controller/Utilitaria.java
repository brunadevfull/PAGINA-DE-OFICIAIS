package controller;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.text.SimpleDateFormat;
import java.util.Date;

import bean.Documento;

public class Utilitaria {

	// Apenas para teste
	public static String montaNomeArquivo(String documento) {
		SimpleDateFormat ano = new SimpleDateFormat("yyyy");
		SimpleDateFormat mes = new SimpleDateFormat("MM");

		File f = new File(documento);
		String[] partesF = f.getName().split("\\.");
		System.out.println(partesF[partesF.length - 1]);
		Date d = new Date();

		String nomeCompletoArquivo = "001+" + ano.format(d) + mes.format(d) + "+"
				+ proximoArquivo("c:\\001\\" + ano.format(d) + mes.format(d));
		System.out.println(nomeCompletoArquivo);
		return nomeCompletoArquivo;

	}

	public static String formatNumeroZeroEsquerda(int tamanhoNumero, int numero) {

		String x = String.format("%0" + tamanhoNumero + "d", numero);

		return x;

	}

	public static String proximoArquivo(String caminho) {
		/*
		 * GERAR O NOME DO ARQUIVO 001+AnoMes+<sequencial>.<extens�o do arquivo>
		 */
		File diretorio = new File(caminho);

		return (diretorio.exists() ? formatNumeroZeroEsquerda(10, diretorio.listFiles().length + 1)
				: formatNumeroZeroEsquerda(10, 1));

	}

	public static String montaNomeArquivo(String caminho, String nomeArquivoOriginal) {
		SimpleDateFormat ano = new SimpleDateFormat("yyyy");
		SimpleDateFormat mes = new SimpleDateFormat("MM");

		File f = new File(nomeArquivoOriginal);
		System.out.println("Nome do arquivo original: " + nomeArquivoOriginal);
		String[] partesF = f.getName().split("\\.");

		Date d = new Date();
		File diretorioFileSystem = new File(caminho + "001" + File.separator + ano.format(d) + mes.format(d));
		System.out
				.println(" diret�rio de trabalho: " + caminho + "001" + File.separator + ano.format(d) + mes.format(d));
		if (!diretorioFileSystem.exists()) {
			diretorioFileSystem.mkdirs();
		}
		// System.out.println("Qtd arquivo no Diretorio:
		// "+diretorioFileSystem.listFiles().length);
		// System.out.println(partesF[1]);
		String nome = formatNumeroZeroEsquerda(10, diretorioFileSystem.listFiles().length + 1) + "." + partesF[1];
		// System.out.println(nome);
		String nomeCompletoArquivoparaBD = "001+" + ano.format(d) + mes.format(d) + "+" + nome;
		// System.out.println(nomeCompletoArquivoparaBD );
		String nomeCompletoArquivoparaFileSystem = caminho + "001" + File.separator + ano.format(d) + mes.format(d)
				+ File.separator + nome;
		// System.out.println(nomeCompletoArquivoparaFileSystem );
		File arquivoFileSystem = new File(nomeCompletoArquivoparaFileSystem);
		/*
		 * try { copy(f, arquivoFileSystem);
		 * System.out.println("Arquivo copiado"); } catch (IOException e) { //
		 * TODO Auto-generated catch block e.printStackTrace(); }
		 */
		// System.out.println(nomeCompletoArquivoparaBD);
		return nomeCompletoArquivoparaBD;
	}

	public static void copy(File src, File dst) throws IOException {
		InputStream in = new FileInputStream(src);
		OutputStream out = new FileOutputStream(dst); // Transferindo bytes de
														// entrada para sa�da
		byte[] buf = new byte[1024];
		int len;
		while ((len = in.read(buf)) > 0) {
			out.write(buf, 0, len);
		}
		in.close();
		out.close();
	}

	public static void setDocumento(String campo, String valor, Documento documento) {

		if (campo.equals("classificacao")) {
			documento.setClassificacao(valor);
		} else if (campo.equals("dataDocumento")) {
			documento.setDataDocumento(valor);
		} else if (campo.equals("dataInclusao")) {
			documento.setDataInclusao(valor);
		} else if (campo.equals("pasta")) {
			documento.setPasta(valor);
		} else if (campo.equals("nipMatricula")) {
			documento.setNipMatricula(valor);
		} else if (campo.equals("consignado")) {
			documento.setConsignado(valor);
		} else if (campo.equals("beneficiario")) {
			documento.setBeneficiario(valor);
		} else if (campo.equals("cpf")) {
			valor = valor.replace(".", "");
			valor = valor.replace("-", "");
			documento.setCpf(valor);
		} else if (campo.equals("origem")) {
			documento.setOrigem(valor);
		} else if (campo.equals("tipoDocumento")) {
			documento.setTipoDocumento(valor);
		} else if (campo.equals("numeroDocumento")) {
			documento.setNumeroDocumento(valor);
		} else if (campo.equals("protocolo")) {
			documento.setProtocolo(valor);
		} else if (campo.equals("dataProtocolo")) {
			documento.setDataProtocolo(valor);
		} else if (campo.equals("oficioJudicialAnexo")) {
			documento.setOficioJudicialAnexo(valor);
		} else if (campo.equals("observacoes")) {
			documento.setObservacoes(valor);
		} else if (campo.equals("responsavel")) {
			documento.setResponsavel(valor);
		} else if (campo.equals("nomeArquivoOriginal")) {
			documento.setNomeArquivoOriginal(valor);
		} else if (campo.equals("entidadeConsignataria")) {
			documento.setEntidadeConsignataria(valor);
		} else if (campo.equals("recordType")) {
			documento.setRecordType(valor);
		} else if (campo.equals("anexo")) {
			System.out.println("dentro da fun��o - anexo: " + valor);
		}

	}

}