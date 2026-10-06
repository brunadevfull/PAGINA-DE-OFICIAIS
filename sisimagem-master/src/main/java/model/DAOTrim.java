package model;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.SQLSyntaxErrorException;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.TimeZone;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Anexo;
import bean.Documento;
import bean.Grupo;
import bean.Usuario;
import controller.OperacaoException;
import controller.Utilitaria;

public class DAOTrim {

	
	private static Logger log = LoggerFactory.getLogger(DAOTrim.class);

	Connection conn = null;

	public Connection obterConexaoDesenv() {
	try {
					if(conn == null || conn.isClosed()){
						Class.forName("oracle.jdbc.driver.OracleDriver").newInstance();
						log.debug("Carregando o Driver ---> ");
			
						conn = DriverManager 
								.getConnection( 
										"jdbc:oracle:thin:@(description = " 
									   /*
										+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.9.96.8)(PORT = 1521)))"
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SID = SISPAGD))) " 
										*/
										
                                                                                        
										/*+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.9.17.227)(PORT = 1521)))"
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SID = trimbd))) "
                                                                                    , "trim", "trim");*/
										
										  /*
										+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = exa01-ctim-hml-scan.ctim.mb)(PORT = 1521)))" //10.9.17.153
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SERVICE_NAME =ctimhomo_TRIM.paas.oracle.com)"
										+ "   (FAILOVER_MODE="
										+ "    (TYPE=select)"
										+ "     (METHOD=basic))) "
										, "trim", "trim");(SID = SISPAGD))) " 
										*/
										
										// A linha abaixo sera ajustada pela esteira com o ambiente correto de AJUSTAR_INICIAL_AMBIENTE para hml ou prd.
										// + " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = exa01-ctim-hml-scan.ctim.mb)(PORT = 1521)))" 
										+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = exa01-ctim-AJUSTAR_INICIAL_AMBIENTE-scan.ctim.mb)(PORT = 1521)))" 
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										// A linha abaixo sera ajustada pela esteira com o ambiente correto de MUDAR_AMBIENTE para homo ou prod.
										//+ "  (SERVICE_NAME = ctimhomo_TRIM.paas.oracle.com)))"
										+ "  (SERVICE_NAME = ctimMUDAR_AMBIENTE_TRIM.paas.oracle.com)))"
										, "trim", "trim");
																				
										/*+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = exa01-ctim-prd-scan.ctim.mb)(PORT = 1521)))" //10.9.17.153
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SERVICE_NAME = ctimprd_TRIM.paas.oracle.com)))"
										, "trim", "trim");*/
						log.debug("Driver Conectado    --> " + conn); 
		
           
					}	
		} catch (SQLException e) {
			log.debug("erro sqlException");
			e.printStackTrace();
		} catch (Exception c) {
			log.debug(c.getMessage());
			c.printStackTrace();
		}
		
		return conn;
		
	}
	
	public Connection obterConexaoDesenvTeste() {
	try {
					if(conn == null || conn.isClosed()){
						Class.forName("oracle.jdbc.driver.OracleDriver").newInstance();
						log.debug("Carregando o Driver ---> ");
			
						conn = DriverManager 
								.getConnection( 
										"jdbc:oracle:thin:@(description = " 
									   /*
										+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.9.96.8)(PORT = 1521)))"
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SID = SISPAGD))) " 
										*/
										
										+ " (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.9.96.14)(PORT = 1521)))"
										+ "(CONNECT_DATA = "
										+ " (SERVER = DEDICATED) "
										+ "  (SID = sispagd))) "
										, "trim", "trim");
						log.debug("Driver Conectado    --> " + conn);
		
           
					}	
		} catch (SQLException e) {
			log.debug("erro sqlException");
			e.printStackTrace();
		} catch (Exception c) {
			log.debug(c.getMessage());
			c.printStackTrace();
		}
		
		return conn;
	}
	public void destroy() throws Throwable {
		// TODO Auto-generated method stub
		log.debug("passou no finalize do daoTrim");
		try {
			conn.close();
			log.debug("conection close" + conn.toString());
		} catch (SQLException e) {
			throw new OperacaoException(e.getMessage());
		}
		super.finalize();
	}


	public int contaField() {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql = " select count(*) from tsexfield ";
		try {
			stmt = conn.prepareStatement(sql);

			// Executa sql
			results = stmt.executeQuery();
			while (results.next()) {
				return results.getInt(1);
			}
			results.close();
			stmt.close();

		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}
		return 0;

	}

	public String[] buscaProximoRecordId(int idTipoRegistro) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String[] recordID = new String[2];
		String sql = " select NVL(max( " + " case "
				+ " when RCRECTYPEURI in( 4,5,6,7,8) then substr(fullrecordid,12,9) "
				+ " when RCRECTYPEURI = 12 then substr(fullrecordid,0,9) " + " end ),0)+1 proximoNumero "
				+ " from tsrecord " + " where ";
		if (idTipoRegistro == 12) {
			sql = sql + " substr(fullrecordid,11,4) = to_char(sysdate, 'yyyy') and RCRECTYPEURI = " + idTipoRegistro;
		} else {
			sql = sql + " substr(fullrecordid,7,4) = to_char(sysdate, 'yyyy') and RCRECTYPEURI = " + idTipoRegistro;
		}
		try {
			stmt = conn.prepareStatement(sql);
			int resultado = 0;
			// Executa sql
			results = stmt.executeQuery();
			while (results.next()) {
				resultado = results.getInt(1);
			}
			if (resultado > 0) {
				SimpleDateFormat ft = new SimpleDateFormat("yyyy");
				if (idTipoRegistro == 4) {
					recordID[0] = "PP41D/" + ft.format(new Date()) + "-" + resultado;
					recordID[1] = "PP41D/" + ft.format(new Date()) + "-"
							+ Utilitaria.formatNumeroZeroEsquerda(9, resultado);

				}
				if (idTipoRegistro == 5) {
					recordID[0] = "PP42D/" + ft.format(new Date()) + "-" + resultado;
					recordID[1] = "PP42D/" + ft.format(new Date()) + "-"
							+ Utilitaria.formatNumeroZeroEsquerda(9, resultado);
				}
				if (idTipoRegistro == 7) {
					recordID[0] = "PP41R/" + ft.format(new Date()) + "-" + resultado;
					recordID[1] = "PP41R/" + ft.format(new Date()) + "-"
							+ Utilitaria.formatNumeroZeroEsquerda(9, resultado);
				}
				if (idTipoRegistro == 8) {
					recordID[0] = "PP42R/" + ft.format(new Date()) + "-" + resultado;
					recordID[1] = "PP42R/" + ft.format(new Date()) + "-"
							+ Utilitaria.formatNumeroZeroEsquerda(9, resultado);
				}
				if (idTipoRegistro == 12) {
					recordID[0] = resultado + "-" + ft.format(new Date());
					recordID[1] = Utilitaria.formatNumeroZeroEsquerda(9, resultado) + "-" + ft.format(new Date());
				}
			}
			results.close();
			stmt.close();			
			
			//conn.close();
			
			//log.info("close conection" + conn.toString());
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		return recordID;

	}

	public Documento listaDocumentoporId(String parametro) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		Documento documento = null;
		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));

		sql = " SELECT  pai.recordid recordNumberPai, rec.uri idArquivo, nvl(exfieldname,' ') nomeCampo, nvl(evfieldval,' ') valorCampo, "
				+ " rec.recordid recordNumber, rec.FULLRECORDID, decode(rec.rcstructuredtitle, null, '' , rec.rcstructuredtitle||' - ')||rec.title title, "
				+ "	rec.regdatetime dataDocumento, rec.creationdatetime dataInclusao, " + "	t.RECTYPENAME pasta, "
				+ " resid nomeArquivoCompleto, " + "  loc.lcname responsavel " + " FROM  tsrecord rec "
				+ " left join TSEXFIELDV dv on (evobjecturi = rec.uri) "
				+ " left JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) " + "	join tsrecelec on (rec.uri = tsrecelec.uri) "
				+ " left join tslocation loc on (tsrecelec.renameuri = loc.uri)"
				+ " join   TSRECTYPE t ON (t.URI = Rec.RCRECTYPEURI)"
				+ " left  join   tslocation lt  ON (t.ownerlocation = lt.uri)"
				+ " left join tsrecord pai on (pai.uri = rec.RCCONTAINERURI) " + " where rec.uri =   " + parametro;

		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");

			// Documento documentoAnterior = documento;
			int idArquivo = 0;
			while (results.next()) {

				idArquivo = results.getInt("idArquivo");
				// verifica se � um novo documento
				// e popula somente os campos que se repetem no resultado da
				// consulta
				if (documento == null || documento.getIdArquivo() != idArquivo) {

					// cria um novo documento
					documento = new Documento();
					// popula os atributos que aparecem em todos os registros do
					// mesmo documento
					documento.setIdArquivo(results.getInt("idArquivo"));
					documento.setRecordNumber(results.getString("recordNumber"));
					documento.setTitle(results.getString("title"));
					try {
						documento.setDataDocumento(
								dfEntrada.format(dfBD.parse(results.getString("dataDocumento").substring(0, 14))));
					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataDocumento(results.getString("dataDocumento"));
						e.printStackTrace();
					}
					try {
						documento.setDataInclusao(
								dfEntrada.format(dfBD.parse(results.getString("dataInclusao").substring(0, 14))));
					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataInclusao(results.getString("dataInclusao"));
						e.printStackTrace();
					}
					documento.setPasta(results.getString("pasta"));
					documento.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
					documento.setFullrecordid(results.getString("FULLRECORDID"));
					documento.setRecordNumberPai(results.getString("recordNumberPai"));
					documento.setResponsavel(results.getString("responsavel"));
				}
				// popula os campos que s�o exclusivos em cada resultado da
				// consulta
				if (results.getString("nomeCampo").trim().equals("Benefici�rio")) {
					documento.setBeneficiario(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Consignado")) {
					documento.setConsignado(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("CPF")) {
					documento.setCpf(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Data de Cria��o (Legado)")) {
					documento.setDataCriacaoLegado(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Data de Protocolo")) {

					try {
						documento.setDataProtocolo(
								dfEntrada.format(dfBD.parse(results.getString("valorCampo").substring(0, 14))));
					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataProtocolo(results.getString("valorCampo"));
						e.printStackTrace();
					}
				} else if (results.getString("nomeCampo").trim().equals("Data de Protocolo (Legado)")) {
					documento.setDataProtocoloLegado(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Entidade Consignat�ria")) {
					documento.setEntidadeConsignataria(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("NIP/Matr�cula")) {
					documento.setNipMatricula(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("N�mero do Documento")) {
					documento.setNumeroDocumento(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("OBSERVA��ES")) {
					documento.setObservacoes(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Of�cio Judicial Anexo")) {
					documento.setOficioJudicialAnexo(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Origem")) {
					documento.setOrigem(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Protocolo")) {
					documento.setProtocolo(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Tipo de Documento")) {
					documento.setTipoDocumento(results.getString("valorCampo"));
				} else if (results.getString("nomeCampo").trim().equals("Urgente")) {
					documento.setUrgente(results.getString("valorCampo"));
				}

			} // end do while
			results.close();
			stmt.close();
			conn.close();
			log.debug("conection close" + conn.toString());
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}
		return documento;
	}

	public List<Documento> listaDocumento(List<Integer> parametro, Map<String, String> mCamposPesquisa) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;
		boolean flagAnd = false;

		List<Documento> lista = new LinkedList<Documento>();
		/*
		 * SELECT t.RECTYPENAME pasta,rec.uri idArquivo, exfieldname nomeCampo,
		 * evfieldval valorCampo, rec.recordid recordNumber,
		 * decode(rcstructuredtitle, null, '' , rcstructuredtitle)||title title,
		 * rec.regdatetime dataDocumento, rec.creationdatetime dataInclusao,
		 * tsrecelec.resid nomeArquivoCompleto, loc.lcname responsavel
		 * 
		 * FROM tsrecord rec -- registro JOIN TSEXFIELDV dv on (evobjecturi =
		 * rec.uri)-- field de pesquisa e seus valores JOIN TSEXFIELD f ON(
		 * f.URI=EVFIELDURI) join tsrecelec on (rec.uri = tsrecelec.uri) --
		 * localiza��o do registro (USU�RIO QUE INSERIU O RECORD) join
		 * tslocation loc on (tsrecelec.renameuri = loc.uri) -- usu�rio logado
		 * join TSRECTYPE T ON (T.URI = Rec.RCRECTYPEURI) -- TIPO DE DOCUMENTO
		 * left join tslocation lt ON (t.ownerlocation = lT.uri) -- PERFIL DO
		 * DOCUMENTO (PAPEM41, PAPEM42) WHERE loc.lcname= '4223' and
		 * lt.lcname='PAPEM42'
		 */
		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));

		// pai.recordid recordNumberPai,
		sql = " SELECT rec.uri idArquivo, nvl(exfieldname,' ') nomeCampo, nvl(evfieldval,' ') valorCampo, "
				+ " recordid recordNumber, FULLRECORDID, decode(rcstructuredtitle, null, '' , rcstructuredtitle||' - ')||title title, "
				+ "	regdatetime dataDocumento, creationdatetime dataInclusao, " + "	t.RECTYPENAME pasta, "
				+ " resid nomeArquivoCompleto, " + "  loc.lcname responsavel " + " FROM  tsrecord rec "
				+ " left join TSEXFIELDV dv on (evobjecturi = rec.uri) "
				+ " left JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) " + "	join tsrecelec on (rec.uri = tsrecelec.uri) "
				+ " left join tslocation loc on (tsrecelec.renameuri = loc.uri)"
				+ " join   TSRECTYPE t ON (t.URI = Rec.RCRECTYPEURI)"
				+ " left  join   tslocation lt  ON (t.ownerlocation = lt.uri)" +
				// " left join tsrecord pai on (pai.uri = rec.RCCONTAINERURI) "+
				" where 1 = 1  ";
		log.debug("mcampos pesquisa");
		if (mCamposPesquisa.containsKey("perfil")) {
			sql = sql + " and lt.lcname = '" + mCamposPesquisa.get("perfil") + "' ";
			mCamposPesquisa.remove("perfil");
		}

		Set<String> chaves = mCamposPesquisa.keySet();
		log.debug("chaves: ");
		for (String chave : chaves) {

			if (!mCamposPesquisa.get(chave).equals("") && mCamposPesquisa.get(chave) != null) {
				log.debug(chave + " : " + mCamposPesquisa.get(chave));
				/* if(flagAnd){ */
				sql = sql + " and " + chave + " like '%" + mCamposPesquisa.get(chave) + "%' ";
				/* }else { */
				flagAnd = true;
				/*
				 * sql = sql + " and ("+chave + " like '%"+
				 * mCamposPesquisa.get(chave) +"%' "; }
				 */

			}

		}
		// verifica se existe algum campo para realizar o filtro
		// se n�o conseguiu captar alguma chave (flagAnd continua false) ou se
		// n�o h� lista de id retornada da consulta anterior(parametro)
		if (!flagAnd && ((parametro == null) || (parametro.isEmpty()))) {
			log.debug("Não realiza a consulta por falta de parametro" + parametro + flagAnd);
			return null;
		}
		log.debug("passou para montar parte dos par�metros");
		if ((parametro != null) && (!parametro.isEmpty())) {
			Iterator<Integer> itParametro = parametro.iterator();
			int contParametro = 1;
			while (itParametro.hasNext()) {

				int val = itParametro.next();
				if (contParametro == 1) {
					if (flagAnd) {
						// sql = sql + " or tsrecelec.uri in ( " +val ;
						sql = sql + " and tsrecelec.uri in ( " + val;
					} else {

						sql = sql + " and tsrecelec.uri in ( " + val;
					}
				} else {
					sql = sql + " , " + val;
				}
				contParametro++;
			}
			if (contParametro > 1) {
				sql = sql + ")";
			}
		} // end do if parametro
		/*
		 * if(flagAnd){ sql = sql + ") order by idArquivo"; }else{
		 */
		sql = sql + " order by idArquivo";
		// }
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Documento documento = null;
			// Documento documentoAnterior = documento;
			int idArquivo = 0;
			while (results.next()) {

				idArquivo = results.getInt("idArquivo");
				// verifica se � um novo documento
				// e popula somente os campos que se repetem no resultado da
				// consulta
				if (documento == null || documento.getIdArquivo() != idArquivo) {

					// inseri o documento no lista
					if (documento != null) {
						lista.add(documento);
					}

					// cria um novo documento
					documento = new Documento();
					// popula os atributos que aparecem em todos os registros do
					// mesmo documento
					documento.setIdArquivo(results.getInt("idArquivo"));
					documento.setRecordNumber(results.getString("recordNumber"));
					documento.setTitle(results.getString("title"));
					try {
						documento.setDataDocumento(
								dfEntrada.format(dfBD.parse(results.getString("dataDocumento").substring(0, 13))));
					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataDocumento(results.getString("dataDocumento"));
						e.printStackTrace();
					}
					try {
						documento.setDataInclusao(
								dfEntrada.format(dfBD.parse(results.getString("dataInclusao").substring(0, 13))));

					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataInclusao(results.getString("dataInclusao"));
						e.printStackTrace();
					}

					documento.setPasta(results.getString("pasta"));
					documento.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
					documento.setFullrecordid(results.getString("FULLRECORDID"));
					documento.setResponsavel(results.getString("responsavel"));
					// documento.setRecordNumberPai(results.getString("recordNumberPai"));
					log.debug("preenche campo de documento");

				}

			    //popula os campos que são exclusivos em cada resultado da consulta
		    	if(results.getString("nomeCampo").trim().equals("Beneficiário")){
		    		documento.setBeneficiario(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Consignado")){
		    		documento.setConsignado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("CPF")){
		    		documento.setCpf(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Criação (Legado)")){
		    		documento.setDataCriacaoLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo")){
		    		try {
						documento.setDataProtocolo(dfEntrada.format(dfBD.parse(results.getString("valorCampo").substring(0, 13))));
					} catch (Exception e) {
						documento.setDataProtocolo(results.getString("valorCampo"));
						System.out.println("preenche campo de anexo");
						e.printStackTrace();
					}
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo (Legado)")){
		    		documento.setDataProtocoloLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Entidade Consignatária")){
		    		documento.setEntidadeConsignataria(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("NIP/Matrícula")){
		    		documento.setNipMatricula(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Número do Documento")){
		    		documento.setNumeroDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("OBSERVAÇÕES")){
		    		documento.setObservacoes(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Ofício Judicial Anexo")){
		    		documento.setOficioJudicialAnexo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Origem")){
		    		documento.setOrigem(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Protocolo")){
		    		documento.setProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Tipo de Documento")){
		    		documento.setTipoDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Urgente")){
		    		documento.setUrgente(results.getString("valorCampo"));
		    	}
		    	
			} // end do while
			if (documento != null) {
				lista.add(documento);
			}
			results.close();
			stmt.close();			
			conn.close();
			log.info("close conection" + conn.toString());
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}
		return lista;
	}

	public List<Documento> listaDocumento(Map parametro, Map<String, String> mCamposPesquisa) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;
		boolean flagAnd = false;

		List<Documento> lista = new LinkedList<Documento>();

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));

		sql = " SELECT rec.uri idArquivo, nvl(exfieldname,' ') nomeCampo, nvl(evfieldval,' ') valorCampo, "
				+ " recordid recordNumber, FULLRECORDID, decode(rcstructuredtitle, null, '' , rcstructuredtitle||' - ')||title title, "
				+ "	regdatetime dataDocumento, creationdatetime dataInclusao, " + "	t.RECTYPENAME pasta, "
				+ " resid nomeArquivoCompleto, " + "  loc.lcname responsavel " + " FROM  tsrecord rec "
				+ " left join TSEXFIELDV dv on (evobjecturi = rec.uri) "
				+ " left JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) " + "	join tsrecelec on (rec.uri = tsrecelec.uri) "
				+ " left join tslocation loc on (tsrecelec.renameuri = loc.uri)"
				+ " join   TSRECTYPE t ON (t.URI = Rec.RCRECTYPEURI)"
				+ " left  join   tslocation lt  ON (t.ownerlocation = lt.uri)" + " where 1 = 1  ";
		int contParametro = 0;
		Set s = parametro.entrySet();
		Iterator it = s.iterator();

		while (it.hasNext()) {

			Map.Entry<String, String[]> entry = (Map.Entry<String, String[]>) it.next();

			String key = entry.getKey();
			String[] value = entry.getValue();

			if (key.equals("cmd") || key.substring(0, 1).equals("r")) {
				continue;
			}
			if (value[0].toString().length() > 0) {
				contParametro++;
				if (contParametro == 1) {
					sql = sql + " ( exfieldname = '" + key + "' AND Upper(evfieldval) like '%"
							+ value[0].toString().trim().toUpperCase() + "%') ";
				} else {
					sql = sql + " OR ( exfieldname = '" + key + "' AND Upper(evfieldval) like '%"
							+ value[0].toString().trim().toUpperCase() + "%') ";
				}

			}

		}
		if (mCamposPesquisa.containsKey("perfil")) {
			sql = sql + " and lt.lcname = '" + mCamposPesquisa.get("perfil") + "' ";
			mCamposPesquisa.remove("perfil");
		}

		Set<String> chaves = mCamposPesquisa.keySet();
		for (String chave : chaves) {
			if (!mCamposPesquisa.get(chave).equals("") && mCamposPesquisa.get(chave) != null) {
				if (flagAnd) {
					sql = sql + " or " + chave + " like '%" + mCamposPesquisa.get(chave) + "%' ";
				} else {
					flagAnd = true;
					sql = sql + " and (" + chave + " like '%" + mCamposPesquisa.get(chave) + "%' ";
				}

			}

		}

		if (contParametro > 1) {
			sql = sql + ")";
		}
		if (flagAnd) {
			sql = sql + ") order by idArquivo";
		} else {
			sql = sql + " order by idArquivo";
		}
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Documento documento = null;
			// Documento documentoAnterior = documento;
			int idArquivo = 0;
			while (results.next()) {

				idArquivo = results.getInt("idArquivo");
				// verifica se � um novo documento
				// e popula somente os campos que se repetem no resultado da
				// consulta
				if (documento == null || documento.getIdArquivo() != idArquivo) {

					// inseri o documento no lista
					if (documento != null) {
						lista.add(documento);
					}

					// cria um novo documento
					documento = new Documento();
					// popula os atributos que aparecem em todos os registros do
					// mesmo documento
					documento.setIdArquivo(results.getInt("idArquivo"));
					documento.setRecordNumber(results.getString("recordNumber"));
					documento.setTitle(results.getString("title"));
					try {
						documento.setDataDocumento(
								dfEntrada.format(dfBD.parse(results.getString("dataDocumento").substring(0, 13))));
					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataDocumento(results.getString("dataDocumento"));
						e.printStackTrace();
					}
					try {
						documento.setDataInclusao(
								dfEntrada.format(dfBD.parse(results.getString("dataInclusao").substring(0, 13))));

					} catch (Exception e) {
						// TODO Auto-generated catch block
						documento.setDataInclusao(results.getString("dataInclusao"));
						e.printStackTrace();
					}

					documento.setPasta(results.getString("pasta"));
					documento.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
					documento.setFullrecordid(results.getString("FULLRECORDID"));
					documento.setResponsavel(results.getString("responsavel"));
					// documento.setRecordNumberPai(results.getString("recordNumberPai"));
					log.debug("preenche campo de documento");

				}

				 //popula os campos que são exclusivos em cada resultado da consulta
		    	if(results.getString("nomeCampo").trim().equals("Beneficiário")){
		    		documento.setBeneficiario(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Consignado")){
		    		documento.setConsignado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("CPF")){
		    		documento.setCpf(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Criação (Legado)")){
		    		documento.setDataCriacaoLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo")){
		    		try {
						documento.setDataProtocolo(dfEntrada.format(dfBD.parse(results.getString("valorCampo").substring(0, 13))));
					} catch (Exception e) {
						documento.setDataProtocolo(results.getString("valorCampo"));
						System.out.println("preenche campo de anexo");
						e.printStackTrace();
					}
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo (Legado)")){
		    		documento.setDataProtocoloLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Entidade Consignatária")){
		    		documento.setEntidadeConsignataria(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("NIP/Matrícula")){
		    		documento.setNipMatricula(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Número do Documento")){
		    		documento.setNumeroDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("OBSERVAÇÕES")){
		    		documento.setObservacoes(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Ofício Judicial Anexo")){
		    		documento.setOficioJudicialAnexo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Origem")){
		    		documento.setOrigem(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Protocolo")){
		    		documento.setProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Tipo de Documento")){
		    		documento.setTipoDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Urgente")){
		    		documento.setUrgente(results.getString("valorCampo"));
		    	}

			} // end do while
			if (documento != null) {
				lista.add(documento);
			}
			results.close();
			stmt.close();
			conn.close();
			
			log.info("close conection" + conn.toString());
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}
		return lista;
	}

	public List<Documento> listaDocumento(List<Integer> parametro) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		List<Documento> lista = new LinkedList<Documento>();
		sql = " SELECT  rec.uri idArquivo, exfieldname nomeCampo, evfieldval valorCampo, "
				+ " recordid recordNumber, FULLRECORDID, decode(rcstructuredtitle, null, '' , rcstructuredtitle)||title title, "
				+ "	regdatetime dataDocumento, creationdatetime dataInclusao, " + "	tsrecloc.rldescription pasta, "
				+ " resid nomeArquivoCompleto " + " FROM TSEXFIELDV dv JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) "
				+ "	join tsrecord rec on (evobjecturi = rec.uri) " + "	join tsrecelec on (rec.uri = tsrecelec.uri) "
				+ " left join tslocation loc on (tsrecelec.renameuri = loc.uri)"
				+ " left join tsrecloc on (loc.uri = tsrecloc.rllocuri and rec.uri = tsrecloc.rlrecuri) "
				+ " where 1 = 1  ";

		Iterator<Integer> itParametro = parametro.iterator();
		int contParametro = 1;
		while (itParametro.hasNext()) {

			int val = itParametro.next();
			if (contParametro == 1) {
				sql = sql + " and tsrecelec.uri in ( " + val;
			} else {
				sql = sql + " , " + val;
			}
			contParametro++;
		}
		sql = sql + ") order by idArquivo";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Documento documento = null;
			// Documento documentoAnterior = documento;
			int idArquivo = 0;
			while (results.next()) {

				idArquivo = results.getInt("idArquivo");
				// verifica se � um novo documento
				// e popula somente os campos que se repetem no resultado da
				// consulta
				if (documento == null || documento.getIdArquivo() != idArquivo) {

					// inseri o documento no lista
					if (documento != null) {
						lista.add(documento);
					}

					// cria um novo documento
					documento = new Documento();
					// popula os atributos que aparecem em todos os registros do
					// mesmo documento
					documento.setIdArquivo(results.getInt("idArquivo"));
					documento.setRecordNumber(results.getString("recordNumber"));
					documento.setTitle(results.getString("title"));
					documento.setDataDocumento(results.getString("dataDocumento"));
					documento.setDataInclusao(results.getString("dataInclusao"));
					documento.setPasta(results.getString("pasta"));
					documento.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
					documento.setFullrecordid(results.getString("FULLRECORDID"));

				}
				 //popula os campos que são exclusivos em cada resultado da consulta
		    	if(results.getString("nomeCampo").trim().equals("Beneficiário")){
		    		documento.setBeneficiario(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Consignado")){
		    		documento.setConsignado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("CPF")){
		    		documento.setCpf(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Criação (Legado)")){
		    		documento.setDataCriacaoLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo")){
		    		documento.setDataProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo (Legado)")){
		    		documento.setDataProtocoloLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Entidade Consignatária")){
		    		documento.setEntidadeConsignataria(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("NIP/Matrícula")){
		    		documento.setNipMatricula(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Número do Documento")){
		    		documento.setNumeroDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("OBSERVAÇÕES")){
		    		documento.setObservacoes(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Ofício Judicial Anexo")){
		    		documento.setOficioJudicialAnexo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Origem")){
		    		documento.setOrigem(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Protocolo")){
		    		documento.setProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Tipo de Documento")){
		    		documento.setTipoDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Urgente")){
		    		documento.setUrgente(results.getString("valorCampo"));
		    	}
			} // end do while
			if (documento != null) {
				lista.add(documento);
			}
			results.close();
			stmt.close();
		} catch (SQLException e) {
			log.debug("error" + e);
		}
		return lista;
	}

	public List<Documento> listaDocumento() {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		List<Documento> lista = new LinkedList<Documento>();
		sql = " SELECT  rec.uri idArquivo, exfieldname nomeCampo, evfieldval valorCampo, "
				+ " recordid recordNumber, FULLRECORDID, rcstructuredtitle ||' - '||title title, "
				+ "	regdatetime dataDocumento, creationdatetime dataInclusao, " + "	tsrecloc.rldescription pasta, "
				+ " resid nomeArquivoCompleto, " + " lcname responsavel "
				+ " FROM TSEXFIELDV dv JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) "
				+ "	join tsrecord rec on (evobjecturi = rec.uri) " + "	join tsrecelec on (rec.uri = tsrecelec.uri) "
				+ " join tslocation loc on (tsrecelec.renameuri = loc.uri)"
				+ " join tsrecloc on (loc.uri = tsrecloc.rllocuri and rec.uri = tsrecloc.rlrecuri) "
				+ " where  tsrecelec.uri = 290954 ";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Documento documento = null;
			// Documento documentoAnterior = documento;
			int idArquivo = 0;
			while (results.next()) {
				log.debug("While");
				idArquivo = results.getInt("idArquivo");
				// verifica se � um novo documento
				// e popula somente os campos que se repetem no resultado da
				// consulta
				if (documento == null || documento.getIdArquivo() != idArquivo) {
					log.debug("entrou no if documento == null ");
					// inseri o documento no lista
					if (documento != null) {
						lista.add(documento);
					}

					// cria um novo documento
					documento = new Documento();
					// popula os atributos que aparecem em todos os registros do
					// mesmo documento
					documento.setIdArquivo(results.getInt("idArquivo"));
					documento.setRecordNumber(results.getString("recordNumber"));
					documento.setTitle(results.getString("title"));
					documento.setDataDocumento(results.getString("dataDocumento"));
					documento.setDataInclusao(results.getString("dataInclusao"));
					documento.setPasta(results.getString("pasta"));
					documento.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
					documento.setResponsavel(results.getString("responsavel"));
					documento.setFullrecordid(results.getString("FULLRECORDID"));

				}
				  //popula os campos que são exclusivos em cada resultado da consulta
		    	if(results.getString("nomeCampo").trim().equals("Beneficiário")){
		    		documento.setBeneficiario(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Consignado")){
		    		documento.setConsignado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("CPF")){
		    		documento.setCpf(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Criação (Legado)")){
		    		documento.setDataCriacaoLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo")){
		    		documento.setDataProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Data de Protocolo (Legado)")){
		    		documento.setDataProtocoloLegado(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Entidade Consignatária")){
		    		documento.setEntidadeConsignataria(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("NIP/Matrícula")){
		    		documento.setNipMatricula(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Número do Documento")){
		    		documento.setNumeroDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("OBSERVAÇÕES")){
		    		documento.setObservacoes(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Ofício Judicial Anexo")){
		    		documento.setOficioJudicialAnexo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Origem")){
		    		documento.setOrigem(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Protocolo")){
		    		documento.setProtocolo(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Tipo de Documento")){
		    		documento.setTipoDocumento(results.getString("valorCampo"));
		    	}else if(results.getString("nomeCampo").trim().equals("Urgente")){
		    		documento.setUrgente(results.getString("valorCampo"));
		    	}
		    	
			} // end do while
			lista.add(documento);
			results.close();
			stmt.close();
		} catch (SQLException e) {
			log.debug("error" + e);
		}
		return lista;
	}

	public List<Integer> buscaIdDocumento(List<String[]> parametro) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		List<Integer> lista = new LinkedList<Integer>();
		sql = " SELECT evobjecturi idArquivo " + " FROM TSEXFIELDV dv JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) "
				+ " where  1 = 1 ";

		Iterator<String[]> itParametro = parametro.iterator();
		while (itParametro.hasNext()) {
			String val[] = itParametro.next();
			if (val[0].equals("CPF")) {
				sql = sql + " AND  exfieldname = '" + val[0]
						+ "' AND  replace(replace(evfieldval,'.',''),'-','')  like '%" + val[1] + "%' ";
			} else {
				sql = sql + " AND  exfieldname = '" + val[0] + "' AND evfieldval like '%" + val[1] + "%' ";
			}
			log.debug(sql);

		}

		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta");
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");

			while (results.next()) {
				lista.add(results.getInt("idArquivo"));
			} // end do while
			results.close();
			stmt.close();
		} catch (SQLException e) {
			log.debug("error" + e);
		}
		return lista;
	}

	public List<Integer> buscaIdDocumento(Map parametro) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		List<Integer> lista = new LinkedList<Integer>();
		sql = " SELECT evobjecturi idArquivo " + " FROM TSEXFIELDV dv JOIN TSEXFIELD f ON( f.URI=EVFIELDURI) "
				+ " where ";

		int contParametro = 1;
		Set s = parametro.entrySet();
		Iterator it = s.iterator();

		while (it.hasNext()) {

			Map.Entry<String, String[]> entry = (Map.Entry<String, String[]>) it.next();

			String key = entry.getKey();
			String[] value = entry.getValue();

			if (key.equals("cmd") || key.substring(0, 1).equals("r")) {
				continue;
			}
			if (value[0].toString().length() > 0) {
				if (contParametro == 1) {
					sql = sql + " ( exfieldname = '" + key + "' AND Upper(evfieldval) like '%"
							+ value[0].toString().trim().toUpperCase() + "%') ";
				} else {
					sql = sql + " OR ( exfieldname = '" + key + "' AND Upper(evfieldval) like '%"
							+ value[0].toString().trim().toUpperCase() + "%') ";
				}
				contParametro++;
			}

		}

		log.debug(sql);
		// somente executa consulta se houver algum par�metro preenchido
		if (contParametro > 1) {
			try {
				stmt = conn.prepareStatement(sql);
				log.debug("recebeu a consulta");
				// Executa sql
				results = stmt.executeQuery();
				log.debug("realizou a consulta");

				while (results.next()) {
					lista.add(results.getInt("idArquivo"));
				} // end do while
				results.close();
				stmt.close();
			} catch (SQLException e) {
				log.debug("error" + e);
			}
		}
		return lista;
	}

	public Documento addAnexo(Documento documento) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;

		Anexo anexo = null;

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));

		sql = "  select rec.uri idArquivo, recordid recordNumber, rcstructuredtitle ||' - '||title title, "
				+ "	regdatetime dataDocumento, creationdatetime dataInclusao " + "	, resid nomeArquivoCompleto "
				+ "	from tsrecord rec	join tsrecelec on (rec.uri = tsrecelec.uri) " + " where  RCCONTAINERURI =   "
				+ documento.getIdArquivo();

		try {
			stmt = conn.prepareStatement(sql);
			log.debug(sql);
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");

			while (results.next()) {

				anexo = new Anexo();

				anexo.setIdArquivo(results.getInt("idArquivo"));
				anexo.setRecordNumber(results.getString("recordNumber"));
				anexo.setTitle(results.getString("title"));
				try {
					anexo.setDataDocumento(
							dfEntrada.format(dfBD.parse(results.getString("dataDocumento").substring(0, 14))));

				} catch (Exception e) {
					// TODO Auto-generated catch block
					anexo.setDataDocumento(results.getString("dataDocumento"));
					e.printStackTrace();
				}
				try {
					anexo.setDataInclusao(
							dfEntrada.format(dfBD.parse(results.getString("dataInclusao").substring(0, 14))));

				} catch (Exception e) {
					// TODO Auto-generated catch block
					anexo.setDataInclusao(results.getString("dataInclusao"));
					e.printStackTrace();
				}

				anexo.setNomeArquivoCompleto(results.getString("nomeArquivoCompleto"));
				log.debug("vai inserir anexo em documento");
				documento.setListaAnexo(anexo);
				log.debug("inseriu anexo em documento");
			} // end do while
			results.close();
			stmt.close();
			conn.close();
			log.info("close conection" + conn.toString());
		} catch (SQLException e) {
			log.debug("error" + e);
		}
		return documento;
	}

	private boolean inserirRecLoc(String idArquivo, String responsavel, String pasta) {

		PreparedStatement stmt = null;
		String sql;
		Integer i;

		sql = " INSERT INTO TSRECLOC (URI, RLLOCURI, RLRECURI, RLFROMDATETIME, RLTODATETIME, RLDESCRIPTION) values ("
				+ proximoURI("TSRECLOC") + ", " + "(select uri from tslocation where lcname = '" + responsavel + "') , "
				+ idArquivo + ", " + "TO_CHAR(SYSDATE,'YYYYMMDDHHMMSS')" + ", " + "TO_CHAR(SYSDATE,'YYYYMMDDHHMMSS')"
				+ ", '" + pasta + "' )";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
		} catch (SQLSyntaxErrorException e) {
			log.debug("error" + e);
			return true;
		} catch (SQLException e) {
			return false;
		}

		return false;

	}// end do inserir RecLoc

	private boolean inserirRecElec(String idDocumento, String nomeDocumentoOriginal, String nomeDocumentoSistema,
			String responsavel) {
    log.debug("inseir REcLec");
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		String[] extensao = nomeDocumentoOriginal.split("\\.");
		log.debug(nomeDocumentoOriginal);

		sql = " INSERT INTO TSRECELEC (URI, RESID, REMODIFIEDDATETIME, REEXTENSION, REFILENAME, renameuri) values ("
				+ idDocumento + ", '" + nomeDocumentoSistema.trim() + "', " + " TO_CHAR(SYSDATE,'YYYYMMDDHHMMSS'), '"
				+ extensao[extensao.length - 1].trim() + "',' " + nomeDocumentoOriginal.trim() + "',"
				+ " (SELECT URI FROM tslocation loc where loc.lcname = '" + responsavel + "') )";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
		} catch (SQLSyntaxErrorException e) {
			log.debug("error" + e);
			return false;
		} catch (SQLException e) {
			log.debug("error" + e);
			return false;
		}
		
		System.out.println(responsavel);
		return true;

	}// end do inserir RecLoc

	private boolean inserirField(String idArquivo, String campo, String valor) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		sql = " INSERT INTO TSEXFIELDV (URI, EVFIELDURI, EVOBJECTURI, EVFIELDVAL, EVFIELDBOBTYPE) values ("
				+ proximoURI("TSEXFIELDV") + ", " + "(SELECT URI FROM TSEXFIELD WHERE EXFIELDNAME = '" + campo + "'), "
				+ idArquivo + ", '" + valor + "',6 )";

		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
		} catch (SQLException e) {
			log.debug( "eroor" + e);
			return false;
		}

		return false;

	}// end do inserir Field

	private boolean inserirRecord(Documento documento) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;
		// busca ID deste arquivo
		documento.setIdArquivo(proximoURI("TSRECORD"));
		// busca pr�ximo recordNumber
		log.debug("documento-setidArquivo :" + documento.getIdArquivo());
		String[] lRecordId = buscaProximoRecordId(Integer.parseInt(documento.getRecordType()));
		log.debug("buscaProximoRecordId");
		log.debug("documento.getRccontaineruri(): " + documento.getRccontaineruri() + "recordid :" + lRecordId[0]);
		documento.setRecordNumber(lRecordId[0]);
		// gera nome do t�tulo se n�o for preenchido na tela
		if (documento.getTitle().equals("")) {
			documento.setTitle(documento.getIdArquivo().toString());
		}
		SimpleDateFormat dataTimeBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dataTimeBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT-2"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));

		try {
			sql = " INSERT INTO TSRECORD (URI, TITLE, RCSTRUCTUREDTITLE, RECORDID, FULLRECORDID, REGDATETIME, CREATIONDATETIME, RCRECTYPEURI, RCCONTAINERURI, RCSCHEDULEURI  ) values ("
					+ documento.getIdArquivo() + ", '" + documento.getTitle() + "', '" + documento.getClassificacao()
					+ "', '" + documento.getRecordNumber() + "', '" + lRecordId[1] + "', '"
					+ dataTimeBD.format(dfEntrada.parse(documento.getDataDocumento())) + "', '"
					+ dataTimeBD.format(dfEntrada.parse(documento.getDataInclusao())) + "' , "
					+ documento.getRecordType() + " , ";

			// RCRECTYPEURI = 4 RCCONTAINERURI =1
			// RCRECTYPEURI = 12 RCCONTAINERURI =0
			if (documento.getRccontaineruri() != null && documento.getRccontaineruri() != 0) {
				sql = sql + documento.getRccontaineruri() + " , ";
			} else {
				if (documento.getRecordType().equals("4")) {
					sql = sql + " 1, ";
				} else if (documento.getRecordType().equals("12")) {
					sql = sql + " 0, ";

				}
			}
			sql = sql + "1 )";
			log.debug(sql);

			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
		} catch (ParseException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (SQLException e) {
			e.printStackTrace();
		}

		return false;

	}// end do inserir Record

	private boolean inserirRecord(Anexo anexo) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;
		String[] lRecordId = null;
		// busca ID deste arquivo
		anexo.setIdArquivo(proximoURI("TSRECORD"));
		// s� gera recordNumber se n�o tiver //busca pr�ximo recordNumber
		if (anexo.getRecordNumber().equals("") || anexo.getRecordNumber() == null) {
			lRecordId = buscaProximoRecordId(Integer.parseInt(anexo.getRecordType()));
			anexo.setRecordNumber(lRecordId[0]);
			anexo.setFullrecordid(lRecordId[1]);
		}
		// gera nome do t�tulo se n�o for preenchido na tela
		if (anexo.getTitle().equals("") || anexo.getTitle() == null) {
			anexo.setTitle(anexo.getIdArquivo().toString());
		}
		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		sql = " INSERT INTO TSRECORD (URI, TITLE, RCSTRUCTUREDTITLE, RECORDID, FULLRECORDID, REGDATETIME, CREATIONDATETIME, RCRECTYPEURI, RCCONTAINERURI, RCSCHEDULEURI  ) values ("
				+ anexo.getIdArquivo() + ", '" + anexo.getTitle() + "', '" + anexo.getClassificacao() + "', '"
				+ anexo.getRecordNumber() + "', '" + anexo.getFullrecordid() + "', '" + dfBD.format(new Date())
				+ "1', '" + dfBD.format(new Date()) + "1', " + anexo.getRecordType() + " , " + anexo.getIdDocumento()
				+ " , " + "1 )";
		log.debug(sql);

		try {
			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("REsult do update:" + i);
			if (i != 0) {
				return true;
			}
			stmt.close();
		} catch (SQLException e) {
			log.debug("error" + e);
		}

		return false;

	}// end do inserir Record

	private int proximoURI(String tabela) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql = " select max(uri) from  " + tabela;
		try {
			stmt = conn.prepareStatement(sql);

			// Executa sql
			results = stmt.executeQuery();
			while (results.next()) {
				return (results.getInt(1) + 1);
			}
			results.close();
			stmt.close();
		} catch (SQLException e) {
			log.debug("eroor" + e);
		}
		return 0;

	}

	public boolean inserirAnexo(Anexo anexo) {
		boolean flagTransation = true;

		/*
		 * Para cada anexo - 1 insert na tabela tsrecord cuja o valor do campo
		 * RCCONTAINERURI � o URI do documento - 1 insert na tabela tsrecelec
		 */
		log.debug("inserir anexo");
		try {
			conn.setAutoCommit(false);
			flagTransation = inserirRecord(anexo);

			if (flagTransation) {

				flagTransation = inserirRecElec(anexo.getIdArquivo().toString(), anexo.getNomeArquivoOriginal(),
						anexo.getNomeArquivoCompleto(), anexo.getResponsavel());
			}
			if (flagTransation) {
				log.debug("commit inserir");
				conn.commit();
			} else {
				log.debug("rollback inserir");
				conn.rollback();
			}
			conn.setAutoCommit(true);

		} catch (SQLException e) {
			log.debug( "eroor" + e);
			return flagTransation;
		}

		return flagTransation;
	}

	public Boolean inserirDocumento(Documento documento) {
		boolean flagTransation = true;
		// start uma transa��o onde s� ser� comitada se todos os inserts tiverem
		// sucesso
		/*
		 * Inserir documento no Trim - 1 insert na tabela tsrecord - 1 insert
		 * pra cada campo de pesquisa preenchido na tabela tsfielddv - 1 insert
		 * na tabela tsrecelec - 1 insert na tabela tsrecloc
		 * 
		 * Para cada anexo - 1 insert na tabela tsrecord cuja o valor do campo
		 * RCCONTAINERURI é o URI do documento - 1 insert na tabela tsrecelec
		 */
		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		SimpleDateFormat dfEntrada = new SimpleDateFormat("dd/MM/yyyy HH:mm:ss");
		dfEntrada.setTimeZone(TimeZone.getTimeZone("Etc/GMT+1"));
		try {
			conn.setAutoCommit(false);
			log.debug("entrou no insert doc");
			flagTransation = inserirRecord(documento);
			log.debug("entrou no insert doc rcord");
	    	if(flagTransation && !documento.getBeneficiario().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Beneficiário", documento.getBeneficiario());
	    	}
	    	if(flagTransation && !documento.getConsignado().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Consignado", documento.getConsignado());
	    	}
	    	if(flagTransation && !documento.getCpf().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "CPF", documento.getCpf());
	    	}
	    	
	    	if(flagTransation && !documento.getDataCriacaoLegado().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Data de Criação (Legado)", documento.getDataCriacaoLegado());
	    	}
	    	if(flagTransation && !documento.getDataProtocolo().equals("")){
	    		
	    		try {
					flagTransation = inserirField(documento.getIdArquivo().toString(), "Data de Protocolo",  dfBD.format(dfEntrada.parse(documento.getDataProtocolo())) );
				} catch (ParseException e) {
					// TODO Auto-generated catch block
					flagTransation = inserirField(documento.getIdArquivo().toString(), "Data de Protocolo",  documento.getDataProtocolo() );
					e.printStackTrace();
				}
	    	}
	    	 
	    	if(flagTransation && !documento.getDataProtocoloLegado().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Data de Protocolo (Legado)", documento.getDataProtocoloLegado());
	    	}
	    	
	    	if(flagTransation && !documento.getEntidadeConsignataria().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Entidade Consignatária", documento.getEntidadeConsignataria());
	    	}
	    	 
	    	if(flagTransation && !documento.getNipMatricula().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "NIP/Matrícula", documento.getNipMatricula());
	    	}
	    	 
	    	if(flagTransation && !documento.getNumeroDocumento().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Número do Documento", documento.getNumeroDocumento());
	    	}
	    	 
	    	if(flagTransation && !documento.getObservacoes().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "OBSERVAÇÕES", documento.getObservacoes());
	    	}
	    	 
	    	if(flagTransation && !documento.getOficioJudicialAnexo().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Ofício Judicial Anexo", documento.getOficioJudicialAnexo());
	    	}
	    	 
	    	if(flagTransation && !documento.getOrigem().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Origem", documento.getOrigem());
	    	} 
	    	 
	    	if(flagTransation && !documento.getProtocolo().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Protocolo", documento.getProtocolo());
	    	}
	    	
	    	if(flagTransation && !documento.getTipoDocumento().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Tipo de Documento", documento.getTipoDocumento());
	    	}
	    	
	    	if(flagTransation && !documento.getUrgente().equals("")){
	    		flagTransation = inserirField(documento.getIdArquivo().toString(), "Urgente", documento.getUrgente());
	    	}
	    	if(flagTransation){
	    		flagTransation = inserirRecElec(documento.getIdArquivo().toString(), documento.getNomeArquivoOriginal(), documento.getNomeArquivoCompleto(),documento.getResponsavel());
	    	}
	    	if(flagTransation){
	    		flagTransation = inserirRecLoc(documento.getIdArquivo().toString(), documento.getResponsavel(), documento.getPasta());
	    	}

			if (flagTransation) {
				log.debug("commit inserir");
				conn.commit();
			} else {
				log.debug("rollback inserir");
				conn.rollback();
			}
			conn.setAutoCommit(true);
			
			
			//conn.close();
			//log.info("close conection" + conn.toString());
		} catch (SQLException e) {
			log.debug("eroor" + e);
			return flagTransation;
		}
		return flagTransation;
	}

	public Usuario validaUsuarioSenha(Usuario usuario) {
		PreparedStatement stmt = null;
		ResultSet results = null;

		String sql = " select tslocation.Uri id ,trim(jgname) grupo, trim(lcidnumber) senha "
				+ " From Tslocation  Join Tsjurgroup on (Tsjurgroup.uri = lcjurgroup) " + " where lcname = '"
				+ usuario.getUsuario() + "' and (lcvalidto <> 'B' or lcvalidto is null)";
		// +"' and lcidnumber =
		// '"+Whirlpool.criptografaSenha(usuario.getSenha())+ "'";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);

			// Executa sql
			results = stmt.executeQuery();
			log.debug("passou");

			while (results.next()) {
				usuario.setId(results.getString("id"));
				usuario.setSenha(results.getString("senha"));
				usuario.setGrupo(results.getString("grupo"));
				log.debug("achou usuario: " + usuario.getGrupo());
				return usuario;

			}
			results.close();
			stmt.close();

		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		log.debug("retorna null na validaUsuarioSenha");
		return null;

	}

	public boolean verificaExisteUsuario(String usuario) {
		PreparedStatement stmt = null;
		ResultSet results = null;

		String sql = " select lcname usuario " + " From Tslocation  Join Tsjurgroup on (Tsjurgroup.uri = lcjurgroup) "
				+ " where lcname = '" + usuario + "' ";
		log.debug(sql);
		try {
			stmt = conn.prepareStatement(sql);

			// Executa sql
			results = stmt.executeQuery();
			log.debug("passou");

			while (results.next()) {

				return true;

			}
			results.close();
			stmt.close();
			
			conn.close();
			log.debug("conection close" +conn.toString());

		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		return false;

	}

	public boolean inserirUsuario(Usuario usuario) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		try {
			sql = " INSERT INTO TSLOCATION (URI, LCNAME, LCIDNUMBER, LCJURGROUP, LCVALIDFROM ) values ("
					+ proximoURI("TSLOCATION") + ", '" + usuario.getUsuario() + "', '" + usuario.getSenha() + "', "
					+ " (select uri from Tsjurgroup where trim(jgname) = '" + usuario.getGrupo() + "') , "
					+ dfBD.format(new Date()) + " )";
			log.debug(sql);

			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
			
			
			conn.close();
			log.debug("conection close" + conn.toString());
			
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		return false;

	}// end do inserir Usuario

	public List<Usuario> listaUsuario() {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;
		List<Usuario> lista = new LinkedList<Usuario>();

		sql = " SELECT tslocation.Uri id , Lcname usuario, Lcidnumber senha, trim(Jgname) grupo, LCVALIDTO  bloqueio"
				+ "	from tslocation join Tsjurgroup on (Tsjurgroup.uri = tslocation.Lcjurgroup)  ";

		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta: " + sql);
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Usuario usuario = null;
			while (results.next()) {
				usuario = new Usuario();
				usuario.setId(results.getString("id"));
				usuario.setUsuario(results.getString("usuario"));
				usuario.setGrupo(results.getString("grupo"));
				usuario.setBloqueio(results.getString("bloqueio"));
				lista.add(usuario);
			} // end do while
			results.close();
			stmt.close();
			
		} catch (SQLException e) {
			log.debug( "eroor" +e);
		}

		return lista;
	}

	public List<Grupo> listaGrupo() {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;
		List<Grupo> lista = new LinkedList<Grupo>();

		sql = " SELECT trim(Jgname) grupo " + "	from Tsjurgroup WHERE TRIM(JGNAME) IS NOT NULL ";

		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta: " + sql);
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Grupo grupo = null;
			while (results.next()) {
				log.debug("Pegou um grupo ");
				grupo = new Grupo();
				grupo.setNome(results.getString("grupo"));
				lista.add(grupo);
			} // end do while
			log.debug("Lista de grupo est� vazia: " + lista.isEmpty());
			results.close();
			stmt.close();
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		return lista;
	}

	public List<Usuario> listaUsuario(Map<String, String> mCamposPesquisa) {
		PreparedStatement stmt = null;
		ResultSet results = null;
		String sql;
		List<Usuario> lista = new LinkedList<Usuario>();

		sql = " SELECT tslocation.Uri id , Lcname usuario, Lcidnumber senha, trim(Jgname) grupo, LCVALIDTO bloqueio "
				+ "	from tslocation join Tsjurgroup on (Tsjurgroup.uri = tslocation.Lcjurgroup) " + " where 1=1  ";
		Set<String> chaves = mCamposPesquisa.keySet();
		for (String chave : chaves) {
			if (!mCamposPesquisa.get(chave).equals("") && mCamposPesquisa.get(chave) != null) {
				sql = sql + " and " + chave + " like '%" + mCamposPesquisa.get(chave) + "%' ";
			}

		}

		try {
			stmt = conn.prepareStatement(sql);
			log.debug("recebeu a consulta: " + sql);
			// Executa sql
			results = stmt.executeQuery();
			log.debug("realizou a consulta");
			Usuario usuario = null;
			while (results.next()) {
				usuario = new Usuario();
				usuario.setId(results.getString("id"));
				usuario.setUsuario(results.getString("usuario"));
				usuario.setGrupo(results.getString("grupo"));
				usuario.setBloqueio(results.getString("bloqueio"));
				lista.add(usuario);
			} // end do while
			results.close();
			stmt.close();
			conn.close();
			
			log.debug("conection close" + conn.toString());
			
			
		} catch (SQLException e) {
			log.debug( "eroor" + e);
		}

		return lista;
	}

	public boolean excluirUsuario(String id) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		try {
			sql = " DELETE TSLOCATION WHERE URI = " + id;
			log.debug(sql);

			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug( "count" +i);
			if (i != 0) {
				return true;
			}

			stmt.close();
			conn.close();
			log.debug("conection close" + conn.toString());
		} catch (SQLException e) {

		}

		return false;

	}// end do excluir Usuario

	public boolean alterarUsuario(Usuario usuario) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		try {
			sql = " UPDATE TSLOCATION  SET ";
			if (!usuario.getSenha().equals("")) {
				sql = sql + " LCIDNUMBER = '" + usuario.getSenha() + "', ";
			}
			sql = sql + " LCJURGROUP = (select uri from Tsjurgroup where trim(jgname) = '" + usuario.getGrupo()
					+ "') , " + " SYSLASTUPDATED = " + dfBD.format(new Date())
					// bloqueio de usuario

					+ " , LCVALIDTO = '" + usuario.getBloqueio() + "' " + " where URI = " + usuario.getId();
			log.debug(sql);

			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug("count" + i);
			if (i != 0) {
				stmt.close();
				conn.close();
				log.info("conection close" + conn.toString());
				return true;
			}

			stmt.close();
			conn.close();
			log.info("conection close" + conn.toString());
		} catch (SQLException e) {

		}

		return false;

	}// end do alterar Usuario

	public boolean bloquearUsuario(Usuario usuario) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		try {
			sql = " UPDATE TSLOCATION  SET  LCVALIDTO = 'B' , " + " SYSLASTUPDATED = " + dfBD.format(new Date())
					+ " where URI = " + usuario.getId();
			log.debug(sql);
			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();
			log.debug( "eroor" + i);
			if (i != 0) {
				return true;
			}

			stmt.close();
		} catch (SQLException e) {
			e.printStackTrace();
		}

		return false;

	}// end do bloquear Usuario

	public boolean alterarUsuarioUltimoAcesso(Usuario usuario) {
		PreparedStatement stmt = null;
		String sql;
		Integer i;

		SimpleDateFormat dfBD = new SimpleDateFormat("yyyyMMddHHmmss");
		dfBD.setTimeZone(TimeZone.getTimeZone("Etc/GMT"));

		try {
			sql = " UPDATE TSLOCATION  SET SYSLASTUPDATED = " + dfBD.format(new Date()) + " where URI = "
					+ usuario.getId();
			log.debug(sql);

			stmt = conn.prepareStatement(sql);
			i = stmt.executeUpdate();	
			
			if (i != 0) {
			
				stmt.close();
				conn.close();
				
				log.debug("close" + conn.toString());
				
				return true;
			}

			
			
		} catch (SQLException e) {

		}

		return false;

	}// end do alterar Usuario - ultimo acesso

}