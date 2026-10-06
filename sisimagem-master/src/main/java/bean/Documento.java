package bean;

import java.util.LinkedList;
import java.util.List;

public class Documento {

	// campos de consulta
	private Integer idArquivo;
	private String beneficiario;
	private String consignado;
	private String dataProtocolo;
	private String nipMatricula;
	private String numeroDocumento;
	private String observacoes;
	private String oficioJudicialAnexo;
	private String origem;
	private String protocolo;
	private String tipoDocumento;
	private String recordNumber;
	private String cpf;
	private String dataCriacaoLegado;
	private String dataProtocoloLegado;
	private String entidadeConsignataria;
	private String urgente;

	// campos de entrada
	private String responsavel;
	private String classificacao;

	// campos de localiza��o do arquivo
	private String title;
	private String dataDocumento;
	private String dataInclusao;
	private String pasta;
	private String nomeArquivoCompleto;
	private String nomeArquivoOriginal;
	private String recordType;
	private String fullrecordid;
	private Integer rccontaineruri;
	private String recordNumberPai;

	public List<Anexo> listaAnexo = new LinkedList<Anexo>();

	public Documento() {
		super();
		this.beneficiario = "";
		this.consignado = "";
		this.dataProtocolo = "";
		this.nipMatricula = "";
		this.numeroDocumento = "";
		this.observacoes = "";
		this.oficioJudicialAnexo = "";
		this.origem = "";
		this.protocolo = "";
		this.tipoDocumento = "";
		this.cpf = "";
		this.entidadeConsignataria = "";
		this.dataDocumento = "";
		this.dataInclusao = "";
		this.pasta = "";
		this.nomeArquivoOriginal = "";
		this.dataCriacaoLegado = "";
		this.dataProtocoloLegado = "";
		this.urgente = "";
		this.classificacao = "";
	}

	public List<Anexo> getListaAnexo() {
		return listaAnexo;
	}

	public void setListaAnexo(Anexo anexo) {
		this.listaAnexo.add(anexo);
	}

	public String getBeneficiario() {
		if (beneficiario == null) {
			return "";
		}
		return beneficiario;
	}

	public void setBeneficiario(String beneficiario) {
		if (beneficiario == null) {
			this.beneficiario = "";
		} else {
			this.beneficiario = beneficiario;
		}
	}

	public String getConsignado() {
		if (consignado == null) {
			return "";
		}
		return consignado;
	}

	public void setConsignado(String consignado) {
		if (consignado == null) {
			this.consignado = "";
		} else {
			this.consignado = consignado;
		}
	}

	public String getDataProtocolo() {
		if (dataProtocolo == null) {
			return "";
		}
		return dataProtocolo;
	}

	public void setDataProtocolo(String dataProtocolo) {
		if (dataProtocolo == null) {
			this.dataProtocolo = "";
		} else {
			this.dataProtocolo = dataProtocolo;
		}
	}

	public String getNipMatricula() {
		if (nipMatricula == null) {
			return "";
		}
		return nipMatricula;
	}

	public void setNipMatricula(String nipMatricula) {
		if (nipMatricula == null) {
			this.nipMatricula = "";
		} else {
			this.nipMatricula = nipMatricula;
		}
	}

	public String getNumeroDocumento() {
		if (numeroDocumento == null) {
			return "";
		}
		return numeroDocumento;
	}

	public void setNumeroDocumento(String numeroDocumento) {
		if (numeroDocumento == null) {
			this.numeroDocumento = "";
		} else {
			this.numeroDocumento = numeroDocumento;
		}
	}

	public String getObservacoes() {
		if (observacoes == null) {
			return "";
		}
		return observacoes;
	}

	public void setObservacoes(String observacoes) {
		if (observacoes == null) {
			this.observacoes = "";
		} else {
			this.observacoes = observacoes;
		}
	}

	public String getOficioJudicialAnexo() {
		if (oficioJudicialAnexo == null) {
			return "";
		}
		return oficioJudicialAnexo;
	}

	public void setOficioJudicialAnexo(String oficioJudicialAnexo) {
		if (oficioJudicialAnexo == null) {
			this.oficioJudicialAnexo = "";
		} else {
			this.oficioJudicialAnexo = oficioJudicialAnexo;
		}
	}

	public String getOrigem() {
		if (origem == null) {
			return "";
		}
		return origem;
	}

	public void setOrigem(String origem) {
		if (origem == null) {
			this.origem = "";
		} else {
			this.origem = origem;
		}
	}

	public String getProtocolo() {
		if (protocolo == null) {
			return "";
		}
		return protocolo;
	}

	public void setProtocolo(String protocolo) {
		if (protocolo == null) {
			this.protocolo = "";
		} else {
			this.protocolo = protocolo;
		}
	}

	public String getTipoDocumento() {
		if (tipoDocumento == null) {
			return "";
		}
		return tipoDocumento;
	}

	public void setTipoDocumento(String tipoDocumento) {
		if (tipoDocumento == null) {
			this.tipoDocumento = "";
		} else {
			this.tipoDocumento = tipoDocumento;
		}
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

	public String getRecordNumberPai() {
		if (recordNumberPai == null) {
			return "";
		}
		return recordNumberPai;
	}

	public void setRecordNumberPai(String recordNumberPai) {
		if (recordNumberPai == null) {
			this.recordNumberPai = "";
		} else {
			this.recordNumberPai = recordNumberPai;
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
		/*
		 * SimpleDateFormat dfEntrada = new
		 * SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		 * dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));
		 * 
		 * SimpleDateFormat dfBd = new SimpleDateFormat("yyyyMMddHHmmss");
		 * dfBd.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2")); try { Date d =
		 * dfBd.parse(dataInclusao); dataInclusao = dfEntrada.format(d);
		 * this.dataInclusao = dataInclusao; } catch (ParseException e) { //
		 * TODO Auto-generated catch block e.printStackTrace(); }
		 */
		if (dataInclusao == null) {
			this.dataInclusao = "";
		} else {

			this.dataInclusao = dataInclusao;

		}

	}

	public String getPasta() {
		if (pasta == null) {
			return "";
		}
		return pasta;
	}

	public void setPasta(String pasta) {
		if (pasta == null) {
			this.pasta = "";
		} else {
			this.pasta = pasta;
		}
	}

	public String getCpf() {
		if (cpf == null) {
			return "";
		}
		return cpf;
	}

	public void setCpf(String cpf) {
		if (cpf == null) {
			this.cpf = "";
		} else {
			this.cpf = cpf;
		}
	}

	public String getDataProtocoloLegado() {
		if (dataProtocoloLegado == null) {
			return "";
		}
		return dataProtocoloLegado;
	}

	public void setDataProtocoloLegado(String dataProtocoloLegado) {
		if (dataProtocoloLegado == null) {
			this.dataProtocoloLegado = "";
		} else {
			this.dataProtocoloLegado = dataProtocoloLegado;
		}
	}

	public String getEntidadeConsignataria() {
		if (entidadeConsignataria == null) {
			return "";
		}
		return entidadeConsignataria;
	}

	public void setEntidadeConsignataria(String entidadeConsignataria) {
		if (entidadeConsignataria == null) {
			this.entidadeConsignataria = "";
		} else {
			this.entidadeConsignataria = entidadeConsignataria;
		}
	}

	public String getUrgente() {
		if (urgente == null) {
			return "";
		}
		return urgente;
	}

	public void setUrgente(String urgente) {
		if (urgente == null) {
			this.urgente = "";
		} else {
			this.urgente = urgente;
		}
	}

	public void setIdArquivo(Integer idArquivo) {
		this.idArquivo = idArquivo;
	}

	public Integer getIdArquivo() {
		return idArquivo;
	}

	public void setDataCriacaoLegado(String dataCriacaoLegado) {
		if (dataCriacaoLegado == null) {
			this.dataCriacaoLegado = "";
		} else {
			this.dataCriacaoLegado = dataCriacaoLegado;
		}
	}

	public String getDataCriacaoLegado() {
		if (dataCriacaoLegado == null) {
			return "";
		}
		return dataCriacaoLegado;
	}

	public void setNomeArquivoCompleto(String nomeArquivoCompleto) {
		if (nomeArquivoCompleto == null) {
			this.nomeArquivoCompleto = "";
		} else {
			this.nomeArquivoCompleto = nomeArquivoCompleto;
		}
	}

	public String getNomeArquivoCompletoTela() {
		return nomeArquivoCompleto.replace("+", "/");
	}

	public String getNomeArquivoCompleto() {
		if (nomeArquivoCompleto == null) {
			return "";
		}
		return nomeArquivoCompleto;
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

	public void setNomeArquivoOriginal(String nomeArquivoOriginal) {
		this.nomeArquivoOriginal = nomeArquivoOriginal;
	}

	public String getNomeArquivoOriginal() {
		return nomeArquivoOriginal;
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

	public void setRccontaineruri(Integer rccontaineruri) {
		this.rccontaineruri = rccontaineruri;

	}

	public Integer getRccontaineruri() {
		return rccontaineruri;
	}

}