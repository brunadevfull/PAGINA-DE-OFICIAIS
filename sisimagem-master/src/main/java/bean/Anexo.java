package bean;

public class Anexo {
	private Integer idDocumento;
	private Integer idArquivo;
	private String recordNumber;
	private String title;
	private String dataDocumento;
	private String dataInclusao;
	private String nomeArquivoCompleto;
	private String nomeArquivoOriginal;
	private String classificacao;
	private String responsavel;
	private String recordType;
	private String fullrecordid;

	public void setIdArquivo(Integer idArquivo) {
		this.idArquivo = idArquivo;
	}

	public Integer getIdArquivo() {
		return idArquivo;
	}

	public String getRecordNumber() {
		if (recordNumber == null) {
			return "";
		}
		return recordNumber;
	}

	public void setRecordNumber(String recordNumber) {
		if (recordNumber == null) {
			this.recordNumber = "";
		} else {
			this.recordNumber = recordNumber;
		}
	}

	public String getTitle() {
		if (title == null) {
			return "";
		}
		return title;
	}

	public void setTitle(String title) {
		if (title == null) {
			this.title = "";
		} else {
			this.title = title;
		}
	}

	public String getDataDocumento() {
		if (dataDocumento == null) {
			return "";
		}
		return dataDocumento;
	}

	public void setDataDocumento(String dataDocumento) {
		if (dataDocumento == null) {
			this.dataDocumento = "";
		} else {
			this.dataDocumento = dataDocumento;
		}
	}

	public String getDataInclusao() {
		if (dataInclusao == null) {
			return "";
		}
		return dataInclusao;
	}

	public void setDataInclusao(String dataInclusao) {
		if (dataInclusao == null) {
			this.dataInclusao = "";
		} else {
			this.dataInclusao = dataInclusao;
		}
	}

	public void setNomeArquivoCompleto(String nomeArquivoCompleto) {
		if (nomeArquivoCompleto == null) {
			this.nomeArquivoCompleto = "";
		} else {
			this.nomeArquivoCompleto = nomeArquivoCompleto;
		}
	}

	public String getNomeArquivoCompleto() {
		if (nomeArquivoCompleto == null) {
			return "";
		}
		return nomeArquivoCompleto;
	}

	public void setIdDocumento(Integer idDocumento) {
		this.idDocumento = idDocumento;
	}

	public Integer getIdDocumento() {
		return idDocumento;
	}

	public String getClassificacao() {
		return classificacao;
	}

	public void setClassificacao(String classificacao) {
		if (classificacao == null) {
			this.classificacao = "";
		} else {
			this.classificacao = classificacao;
		}
	}

	public void setResponsavel(String responsavel) {
		if (responsavel == null) {
			this.responsavel = "";
		} else {
			this.responsavel = responsavel;
		}
	}

	public String getResponsavel() {
		return responsavel;
	}

	public void setRecordType(String recordType) {
		if (recordType == null) {
			this.recordType = "";
		} else {
			this.recordType = recordType;
		}
	}

	public String getRecordType() {
		return recordType;
	}

	public void setFullrecordid(String fullrecordid) {
		if (fullrecordid == null) {
			this.fullrecordid = "";
		} else {
			this.fullrecordid = fullrecordid;
		}
	}

	public String getFullrecordid() {
		return fullrecordid;
	}

	public void setNomeArquivoOriginal(String nomeArquivoOriginal) {
		this.nomeArquivoOriginal = nomeArquivoOriginal;
	}

	public String getNomeArquivoOriginal() {
		return nomeArquivoOriginal;
	}
}
